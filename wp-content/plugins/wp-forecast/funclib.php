<?php
/* This file is part of the wp-forecast plugin for wordpress */

/*  Copyright 2006-2012 Hans Matzen (email : webmaster at tuxlog dot de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if (!function_exists('fetchURL')) {

//
// this function fetches an url an returns it as one whole string
//
function fetchURL($url) 
{
  // get timeout parameter
  $timeout = get_option("wp-forecast-timeout");
  if ( $timeout =="")
    $timeout = 10;


  if ( function_exists("wp_remote_request") ) {
    // switch to wp-forecast transport
    switch_wpf_transport(true);
    
    $wprr_args = array(
	'timeout' => $timeout,
	'decompress' => true,
	'headers' => array(
	    'Connection' => 'Close',
	    'Accept' => '*/*'
	    ) 
	); 


    // use generic wordpress function to retrieve data
    $s = time();
    $resp = wp_remote_request($url, $wprr_args);
    $e = time();

    if ( is_wp_error($resp) )
	$blen = "-1";
    else
	$blen = strlen( $resp['body'] );

    // switch to wp-forecast transport
    switch_wpf_transport(false);

    if ( is_wp_error($resp) ) {
      $errcode = $resp->get_error_code();
      $errmesg = $resp->get_error_message($errcode);
      
      $erg="<ADC_DATABASE><FAILURE>Connection Error:".$errcode . "<br/>";
      $erg .= $errmesg ."</FAILURE></ADC_DATABASE>\n";
    } else
      $erg = $resp['body'];
    
    
  } else {
    // fallback to old fsockopen variant
    $url_parsed = parse_url($url);
    $host = $url_parsed["host"];
    if (!isset($url_parsed["port"])) 
      $port = 80;
    else 
      $port = $url_parsed["port"];
    
    $path = $url_parsed["path"];
    if ($url_parsed["query"] != "") $path .= "?" . $url_parsed["query"];
    $out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
    // open connection
    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
   
    $erg = "";
    if ($fp) {
      // set timeout for reading 
      stream_set_timeout($fp, $timeout);
      // send request
      fwrite($fp, $out);
      $body = false;
      // read answer
      while (!feof($fp)) {
	$s = fgets($fp, 1024);
	if ($body) $erg .= $s;
        if ($s == "\r\n") $body = true;
      }
      // close connection
      fclose($fp);
    } else {
      // error handling
      $erg="<ADC_DATABASE><FAILURE>Connection Error:".$errno. " >> ". $errstr ."</FAILURE></ADC_DATABASE>\n";
    }
  }  
  
  // workaround for bug in decompress function, class wp_http in wp 2.9
  $derg = @gzinflate($erg);
  if ($derg !== false)
      $erg = $derg;
  
 
  
  return $erg;
}


//
// converts the given wind parameters into a suitable windstring
//
function windstr($metric,$wspeed,$windunit) 
{
    // if its mph convert it to m/s
    if ($metric != 1)
	$wspeed = round($wspeed * 0.44704,0);
    
    // convert it to selected unit
    switch ($windunit) {
		case "ms":
			$wunit="m/s";
			break;
		case "kmh":
			$wspeed=round($wspeed*3.6,0);
			$wunit="km/h";
			break;
		case "mph":
			$wspeed=round($wspeed*2.23694,0);
			$wunit="mph";
			break;
		case "kts":
			$wspeed=round($wspeed*1.9438,0);
			$wunit="kts";
			break;
		case "bft":
			$wbft = 0;
			$bft = array(0.3, 1.6, 3.4, 5.5, 8.0, 10.8, 13.9, 17.2, 20.8, 24.5, 28.5, 32.7);
			foreach($bft as $b) {
				if ($wspeed < $b) {
					$wbft--;
					break;
				}
			$wbft++;
		}
		$wunit="bft";
		$wspeed = $wbft;
		break;
	}
	return $wspeed." ".$wunit;
}


//
// wrapper to make sure the correct option is used whether multisite or wp is used
// only wraps main parameters and pass through all others
//
function wpf_get_option($name)
{
    global $blog_id;
    
    if ( !function_exists("is_multisite") || ! is_multisite() || $blog_id==1 )
		return get_option($name);
    else {
		// this is the multisite part 
		if ( $name == "wpf_sa_defaults" or
			 $name == "wpf_sa_allowed" )
			return get_blog_option(1, $name);
		else 
			return get_blog_option($blog_id, $name);
    }
}

//
// wrapper for update option to hide differences between wp and wpmu
//
//
function wpf_update_option($name, $value)
{
    global $blog_id;
    
    if ( !function_exists("is_multisite") || ! is_multisite() || $blog_id==1)
	update_option($name, $value);
    else 
	update_blog_option($blog_id, $name, $value);    
}

//
// wrapper for add option to hide differences between wp and wpmu
//
//
function wpf_add_option($name, $value)
{
    global $blog_id;
    
    if ( !function_exists("is_multisite") || ! is_multisite() || $blog_id==1 )
	add_option($name, $value);
    else 
	add_blog_option($blog_id, $name, $value);
}

//
// reads all wp-forecast options and returns an array
//
function get_wpf_opts($wpfcid) 
{
  global $blog_id;

  $av=array();
  $opt = wpf_get_option("wp-forecast-opts".$wpfcid);

  if (! empty($opt)) 
  {
      // unpack if necessary
      $av=maybe_unserialize($opt);
  } 
  else if (get_option("wp-forecast-location".$wpfcid) !="" ) 
  {
      // get old widget options from database
      $av['service']      = get_option("wp-forecast-service".$wpfcid);
      $av['apikey1']      = get_option("wp-forecast-apikey1".$wpfcid);
      $av['apikey2']      = get_option("wp-forecast-apikey2".$wpfcid);
      $av['location']     = get_option("wp-forecast-location".$wpfcid);
      $av['locname']      = get_option("wp-forecast-locname".$wpfcid);
      $av['refresh']      = get_option("wp-forecast-refresh".$wpfcid); 
      $av['metric']       = get_option("wp-forecast-metric".$wpfcid); 
      $av['wpf_language'] = get_option("wp-forecast-language".$wpfcid);
      $av['daytime']      = get_option("wp-forecast-daytime".$wpfcid);
      $av['nighttime']    = get_option("wp-forecast-nighttime".$wpfcid);
      $av['dispconfig']   = get_option("wp-forecast-dispconfig".$wpfcid);
      $av['windunit']     = get_option("wp-forecast-windunit".$wpfcid);
      $av['currtime']     = get_option("wp-forecast-currtime".$wpfcid);
      $av['timeoffset']   = get_option("wp-forecast-timeoffset".$wpfcid);
      $av['title']        = get_option("wp-forecast-title".$wpfcid);
      // replace old options by new one row option
      wpf_add_option("wp-forecast-opts".$wpfcid,serialize($av));
      // remove old options from database
      delete_option("wp-forecast-location".$wpfcid);
      delete_option("wp-forecast-locname".$wpfcid);
      delete_option("wp-forecast-refresh".$wpfcid); 
      delete_option("wp-forecast-metric".$wpfcid); 
      delete_option("wp-forecast-language".$wpfcid);
      delete_option("wp-forecast-daytime".$wpfcid);
      delete_option("wp-forecast-nighttime".$wpfcid);
      delete_option("wp-forecast-dispconfig".$wpfcid);
      delete_option("wp-forecast-windunit".$wpfcid);
      delete_option("wp-forecast-currtime".$wpfcid); 
      delete_option("wp-forecast-title".$wpfcid);
      delete_option("wp-forecast-service".$wpfcid); 
      delete_option("wp-forecast-apikey1".$wpfcid);
      delete_option("wp-forecast-apikey2".$wpfcid);
  } 
  else 
  {
      $av=array();
  }

  // add expire options
  $av['expire']=get_option("wp-forecast-expire".$wpfcid);

  // add generic options
  $av['fc_date_format']=get_option("date_format");
  $av['fc_time_format']=get_option("time_format");
  $av['xmlerror']="";
  
  // set static uris for each provider
  $av['ACCU_LOC_URI']="http://forecastfox3.accuweather.com/adcbin/forecastfox/locate_city.asp?location="; 
  $av['ACCU_BASE_URI']="http://forecastfox3.accuweather.com/adcbin/forecastfox/weather_data.asp?";
  
  $av['DARKSKY_BASE_URI']="https://api.darksky.net/forecast/";
  
  //$av['BUG_LOC_URI']="http://#apicode#.api.wxbug.net/getLocationsXML.aspx?ACode=#apicode#&SearchString=";
  //$av['BUG_STAT_URI']="http://#apicode#.api.wxbug.net/getStationsXML.aspx?ACode=#apicode#&cityCode=";
  //$av['BUG_BASE_URI']="http://#apicode#.api.wxbug.net/getLiveWeatherRSS.aspx?ACode=#apicode#&cityCode=";
  //$av['BUG_FORC_URI']="http://#apicode#.api.wxbug.net/getForecastRSS.aspx?ACode=#apicode#&cityCode=";
  
  // if we use multisite then merge admin options
  if ( function_exists("is_multisite") && is_multisite() && $blog_id !=1)
  {

      // read defaults and allowed fields
      $defaults = maybe_unserialize(wpf_get_option("wpf_sa_defaults"));
      $allowed  = maybe_unserialize(wpf_get_option("wpf_sa_allowed"));
      // in case allowed is still empty
      if (!$allowed)
	  $allowed=array();

      // set wpf_maxwidgets for users
      global $blog_id, $wpf_maxwidgets;
      if ($blog_id > "1" and isset($defaults["wp-forecast-count"]))
	  $wpf_maxwidgets = $defaults["wp-forecast-count"];
     
      // map rest of fields
      foreach($allowed as $f => $fswitch)
      {
  	  $fname = substr($f,3); // strip ue_ prefix

  	  if ( $fswitch != "1" or ! isset($av[ $fname ]) )
  	  {
  	      // replace value in av with forced default
	      if (array_key_exists($fname, $defaults))
		  $av[ $fname ] = $defaults[$fname];
  	  }
      }
      
  }
  return $av;
}


//
// build the url from the parameters and fetch the weather-data
// return it as one long string
//
function get_weather($uri,$loc,$metric)
{  
  $url=$uri . "location=" . urlencode($loc) . "&metric=" . $metric; 
  
  $xml = fetchURL($url);
  
  return $xml;
}

//
// just return the css link
// this function is called via the wp_head hook
//
function wp_forecast_css($wpfcid="A") 
{
    $wpf_loadcss = wpf_get_option('wp-forecast-loadcss');
    if ($wpf_loadcss == 1 ) return;
    
    $def  = "wp-forecast-default.css";
    $user = "wp-forecast.css";
    
    if (file_exists( WP_PLUGIN_DIR . "/wp-forecast/" . $user))
		$def =$user;
    
    $plugin_url = plugins_url("wp-forecast/");
    echo '<link rel="stylesheet" id="wp-forecast-css" href="'. $plugin_url . $def . '" type="text/css" media="screen" />' ."\n";
}


//
// just return the css link when not using wordpress
// this function is called when showing widget directly via wp-forecast-show.php
//
function wp_forecast_css_nowp($wpfcid="A") 
{
    $wpf_loadcss = wpf_get_option('wp-forecast-loadcss');
    if ($wpf_loadcss == 1 ) return;
    
    $def  = "wp-forecast-default-nowp.css";
    $user = "wp-forecast-nowp.css";
    
    if (file_exists( WP_PLUGIN_DIR . "/wp-forecast/" . $user))
	$def =$user;
    
    $plugin_url = plugins_url("wp-forecast/");
    
    echo '<link rel="stylesheet" id="wp-forecast-nowp-css" href="'. $plugin_url . $def . '" type="text/css" media="screen" />' ."\n";
}


//
// returns the number's widget id used with wp-forecast
// maximum is 999999 :-)
//
function get_widget_id($number)
{
  // if negative take the first id
  if ($number < 0 )
    return "A";

  // the first widgets use chars above we go with 0 padded numbers
  if ( $number <= 25 ) 
    return substr("ABCDEFGHIJKLMNOPQRSTUVWXYZ",$number,1);
  else
    return str_pad($number, 6, "0", STR_PAD_LEFT);
}

//
// function tries to determine the icon path for icon number ino
//
function find_icon($ino) 
{
  $path = WPF_PATH . "/icons/".$ino;
  $ext=".gif";
  
  if ( file_exists($path.".gif") )
    $ext= ".gif";
  else if ( file_exists($path.".png") )
    $ext= ".png";
  else  if ( file_exists($path.".jpg") )
    $ext= ".jpg";
  else if ( file_exists($path.".GIF") )
    $ext= ".GIF";
  else if ( file_exists($path.".PNG") )
    $ext= ".PNG";
  else  if ( file_exists($path.".JPG") )
    $ext= ".JPG";
  else  if ( file_exists($path.".jpeg") )
    $ext= ".jpeg"; 
  else  if ( file_exists($path.".JPEG") )
    $ext= ".JPEG";
  return $ino . $ext;
}

function translate_winddir($wdir,$tdom)
{
  // translate winddir char by char
  $winddir="";
  for ($i=0;$i<strlen($wdir);$i++)
    $winddir=$winddir . __($wdir{$i},$tdom);
  return $winddir;
}



function translate_winddir_degree($wdir,$tdom)
{
	$wdirections = array( 
		'N' 	=> array(348.75, 360),
		'N' 	=> array(0,      11.25),
		'NNE' 	=> array(11.25,  33.75),
		'NE' 	=> array(33.75,  56.25),
		'ENE' 	=> array(56.25,  78.75),
		'E' 	=> array(78.75,  101.25),
		'ESE' 	=> array(101.25, 123.75),
		'SE' 	=> array(123.75, 146.25),
		'SSE' 	=> array(146.25, 168.75),
		'S' 	=> array(168.75, 191.25),
		'SSW' 	=> array(191.25, 213.75),
		'SW' 	=> array(213.75, 236.25),
		'WSW' 	=> array(236.25, 258.75),
		'W' 	=> array(258.75, 281.25),
		'WNW' 	=> array(281.25, 303.75),
		'NW' 	=> array(303.75, 326.25),
		'NNW' 	=> array(326.25, 348.75)
	);
	
	foreach ($wdirections as $d => $a) {
		if ($wdir >= $a[0] && $wdir < $a[1]) {
			$dir = $d;
		}
	}
  
	return translate_winddir($dir,$tdom);
}

}


/*
  functions to check the wordpress transport methods

  if wpf selected transport option is set to empty,
  then we are in probing mode and do not change the wordpress result
  else we only keep alive what was selected via admin dialog

*/

function wpf_check_fsockopen($use, $args = array()) 
{
    $sel_transport = wpf_get_option("wp-forecast-wp-transport");
    if ( $sel_transport == "" || $sel_transport == "default" )
	return $use;
    else if ( $sel_transport == "fsockopen" )
	return true;
    else
	return false;
}

function wpf_check_fopen($use, $args = array()) 
{ 
    $sel_transport = wpf_get_option("wp-forecast-wp-transport");
    if ( $sel_transport == "" || $sel_transport == "default" )
	return $use; 
    else if ( $sel_transport == "fopen" )
	return true;
    else
	return false;
}

function wpf_check_streams($use, $args = array()) 
{
    $sel_transport = wpf_get_option("wp-forecast-wp-transport");
    if ( $sel_transport == "" || $sel_transport == "default" )
	return $use;
    else if ( $sel_transport == "streams" )
	return true;
    else
	return false;
}

function wpf_check_exthttp($use, $args = array()) 
{
    $sel_transport = wpf_get_option("wp-forecast-wp-transport");
    if ( $sel_transport == "" || $sel_transport == "default" )
	return $use; 
    else if ( $sel_transport == "exthttp" )
	return true;
    else
	return false;
}

function wpf_check_curl($use, $args = array()) 
{
    $sel_transport = wpf_get_option("wp-forecast-wp-transport"); 
    if ( $sel_transport == "" || $sel_transport == "default" )
	return $use;
    else if ( $sel_transport == "curl" )
	return true;
    else
	return false;
}

// function to get the list of supported transports ignoring
// any preset method
if (! function_exists("get_wp_transports") )
    {
	function get_wp_transports()
	{
	    $tlist = array();
	    
	    // remove but store selected transport
	    $wp_transport = wpf_get_option("wp-forecast-wp-transport"); 
	    wpf_update_option("wp-forecast-wp-transport","default");
	    
	    // get wordpress default transports
	    $wplist = array();
	    
	    //if ( true === WP_Http_ExtHttp::test( array() ) ) 
		//$tlist[] = "exthttp";
	    
	    if ( true === WP_Http_Fsockopen::test( array() ) ) 
		$tlist[] = "fsockopen";
	    
	    if ( true === WP_Http_Streams::test( array() ) ) 
		$tlist[] = "streams";
	    
	    // disabled fopen, since this class sends no headers
	    //if ( true === WP_Http_Fopen::test( array() ) ) 
	    // $tlist[] = "fopen";
	    
	    if ( true === WP_Http_Curl::test( array() ) ) 
		$tlist[] = "curl";	
	    
	    
	    // write back selected transport
	    wpf_update_option("wp-forecast-wp-transport",$wp_transport);
	    
	    return $tlist;
	}
    }

//
// function to turn on/off wp-forecast preselected transport
// 
function switch_wpf_transport($sw)
{
    $wptrans = "default";

    if ($sw == true)
	$wptrans = wpf_get_option("wp-forecast-pre-transport");

    update_option("wp-forecast-wp-transport",$wptrans);
}

//
// function for plugin_locale filter hook
// only returns the first parameter
//
function wpf_lplug($locale,$domain) {
	// extract locale from domain
	$wpf_locale = substr($domain,12,5);
	return $wpf_locale;
}