<?php
/*
Plugin Name: wp-forecast
Plugin URI: http://www.tuxlog.de
Description: wp-forecast is a highly customizable plugin for wordpress, showing weather-data from accuweather.com.
Version: 6.7
Author: Hans Matzen
Author URI: http://www.tuxlog.de
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: lang
Text Domain: wp-forecast
*/

/*  
    Copyright 2006-2019  Hans Matzen 

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


//
// only use this in case of severe problems accessing the admin dialog
//
// preselected transport method for fetching the weather data
// valid values are
//       curl      - uses libcurl
//       fsockopen - uses fsockopen
//       streams   - uses fopen with streams
//       exthttp   - uses pecl http extension
// this will override every setting from the admin dialog
// you have to assure that the chosen transport is supported by the
// wordpress class WP_Http;
//
static $wp_forecast_pre_transport="";

//
// maximal number of widgets to use
//
$wpf_maxwidgets=8;

/* ---------- no parameters to change after this point -------------------- */
// define path to wp-forecast plugin
define( 'WPF_PATH', plugin_dir_path(__FILE__) );
// accuweather data functions
require_once("func_accu.php");
// DarkSky functions
require_once("func_darksky.php");

// OpenUV data functions
require_once("func_openuv.php");
// ipstack functions
require_once("func_ipstack.php");

// generic functions
require_once("funclib.php");
// include setup functions
require_once("wpf_setup.php");
// include admin options page
require_once("wp-forecast-admin.php");
// display functions
require_once("wp-forecast-show.php");
// shortcodes
require_once("shortcodes.php");
// support for wordpress autoupdate
require_once("wpf_autoupdate.php");
// super admin dialog
require_once("wpf_sa_admin.php");


global $blog_id;

//
// set cache with weather data for current parameters
// a wrapper function called via the init hook
//

function wp_forecast_init() 
{
    // first of all check if we have to set a hard given
    // transport method
    if (isset($wp_forecast_pre_transport) && wpf_get_option("wp-forecast-pre-transport") != $wp_forecast_pre_transport )	{
			wpf_update_option("wp-forecast-pre-transport",$wp_forecast_pre_transport);
	}

    $count=(int) wpf_get_option('wp-forecast-count');
    
    $weather=array();
    
    for ($i=0;$i<$count;$i++) {
		$wpfcid=get_widget_id($i);
	
		$wpf_vars=get_wpf_opts($wpfcid);
		
		// use visitors location for weather and UV
		if (isset($wpf_vars['visitorlocation']) && $wpf_vars['visitorlocation']) {
			
			// get users ip address
			$vip = ipstack_get_visitor_ip();			
			
			// get location information about ip address
			$vloc = ipstack_get_data( wpf_get_option("wp-forecast-ipstackapikey"), $vip);

			// find matching locations from accuweather
			global $loc;
			// search locations for accuweather
			$xml=get_loclist($wpf_vars['ACCU_LOC_URI'],$vloc['city']);
			$xml=utf8_encode($xml);
			accu_get_locations($xml); // modifies global array $loc
			$accu_loc = $loc;
				
			// guess the locations which might be correct matching the country
			$locations = Array();
			foreach( $accu_loc as $al) {
				if ( strpos( $al['state'], $vloc['country_name'] ) !== false )
					$locations[] = $al;
			}
						
			if (count($locations) == 1) {
				// if there is only one location in the array take this one
				$wpf_vars['location'] = $locations[0]['location'];
				$wtemp = accu_get_weather($wpf_vars['ACCU_BASE_URI'], $locations[0]['location'], $wpf_vars['metric']);
				$wpf_vars['loclatitude'] = everything_in_tags($wtemp, "lat");
				$wpf_vars['loclongitude'] = everything_in_tags($wtemp, "lon");
			} else {
				// get the weather data of all the weather locations in the array and find the best match on lat and lon
				$ldist = 99000.0;
				$lat1  = $vloc['latitude']; 
				$lon1  = $vloc['longitude']; 
				$tloc = "";
				$tname = "";
				foreach ( $locations as $ll) {
					$wtemp = accu_get_weather($wpf_vars['ACCU_BASE_URI'], $ll['location'], $wpf_vars['metric']);
					$lat2 = everything_in_tags($wtemp, "lat");
					$lon2 = everything_in_tags($wtemp, "lon");
					$cdist = distanceCalculation($lat1, $lon1, $lat2, $lon2);

					if ($cdist < $ldist) {
						$ldist = $cdist;
						$tloc = $ll['location'];
						$tname = $ll['city'];
						$tlat = $ll['latitude'];
						$tlon = $ll['longitude'];
					}
				}
				
				if ($tloc !="") {
					$wpf_vars['location'] = $tloc;
					$wpf_vars['loclatitude'] = $tlan;
					$wpf_vars['loclongitude'] = $tlon;
				} else {
					// nothing found set default values
					if ($wpf_vars['location'] == "") {
						$wpf_vars['location'] = "EUR|DE|GM003|BERLIN|";
						$tname = "Berlin Alexanderplatz";
					} else
						$tname = $wpf_vars['locname'];
					$vloc['latitude']  = 52.521918;
					$vloc['longitude'] = 13.413215;
					$wpf_vars['loclatitude'] = "52.521918";
					$wpf_vars['loclongitude'] = "13.413215";
				}
			}

			// make sure weather data is updated
			$wpf_vars['expire'] = 0;
			
			//update wpf_vars in case we changed lat and lon
			wpf_update_option("wp-forecast-opts" . $wpfcid, serialize($wpf_vars));
		}
				
		
		// check if we have to fetch the weather data or if we can use the cache
		if ($wpf_vars['expire'] < time()) {
			switch ($wpf_vars['service']) {
				case "accu":
					$w = accu_get_weather($wpf_vars['ACCU_BASE_URI'], $wpf_vars['location'], $wpf_vars['metric']);
					$weather=accu_xml_parser(utf8_encode($w));
					break;

				case "darksky":
					$w = darksky_get_weather($wpf_vars['DARKSKY_BASE_URI'], $wpf_vars['apikey1'],$wpf_vars['loclatitude'], $wpf_vars['loclongitude'], $wpf_vars['metric']);
					$weather=darksky_get_data($w, $wpf_vars);
					break;
			}

			// overwrite visitor specific data
			if (isset($wpf_vars['visitorlocation']) && $wpf_vars['visitorlocation']) {
				$weather['locname'] = $tname;
				$weather['lat']= $vloc['latitude'];
				$weather['lon']= $vloc['longitude'];
			}
			
			// get OpenUV data if wanted
			if (isset($wpf_vars['ouv_apikey']) && trim($wpf_vars['ouv_apikey']) != "") {
				$ouv = openuv_get_data($wpf_vars['ouv_apikey'], $weather['lat'], $weather['lon']);
				$weather['openuv'] = $ouv;
			}
						
			// store weather to database and set expire time
			// if the current data wasnt available use old data
			if ( count($weather)>0) {
				wpf_update_option("wp-forecast-cache".$wpfcid, serialize($weather));
				if ( empty($weather['failure']) or $weather['failure'] == "" )
					wpf_update_option("wp-forecast-expire".$wpfcid, time()+$wpf_vars['refresh']);
				else
					wpf_update_option("wp-forecast-expire".$wpfcid, 0); 
			}
		}
    }
    
    // javascript hinzufügen fuer ajax widget
	if (! is_admin() && get_option('wpf_sem_ajaxload') > 0)
		wp_enqueue_script('wpf_update', plugins_url('wpf_update.js', __FILE__),  array('jquery'),"9999");
}


//
// this function is called from your template
// to insert your weather data at the place you want it to be
// support to select language on a per call basis from Robert Lang
//
function wp_forecast_widget($args=array(),$wpfcid="A", $language_override=null)
{   
  if ($wpfcid == "?")
      $wpf_vars=get_wpf_opts("A");
  else
      $wpf_vars=get_wpf_opts($wpfcid);

  if (!empty($language_override)) {
    $wpf_vars['wpf_language']=$language_override;
  }

  if ($wpfcid == "?")
      $weather=maybe_unserialize(wpf_get_option("wp-forecast-cacheA"));
  else
      $weather=maybe_unserialize(wpf_get_option("wp-forecast-cache".$wpfcid));

  show($wpfcid,$args,$wpf_vars);
}

//
// this is the wrapper function for displaying from sidebar.php
// and not as a widget. since the parameters are different we need this
//
function wp_forecast($wpfcid="A", $language_override=null)
{ 
  wp_forecast_widget( array(), $wpfcid, $language_override);
}

//
// a function to show a range of widgets at once
//
function wp_forecast_range($from=0, $to=0, $numpercol=1, $language_override=null)
{
  global $wpf_maxwidgets;
  $wcount=1;

  // check min and max limit
  if ($from < 0)
    $from = 0;
  
  if ($to > $wpf_maxwidgets)
    $to = $wpf_maxwidgets;
  
  // output table header
  echo "<table><tr>";

  // out put widgets in a table
  for ($i=$from;$i<=$to;$i++) {

    if ( $wcount % $numpercol == 1)
      echo "<tr>";

    echo "<td>";
    wp_forecast( get_widget_id($i), $language_override);
    echo "</td>";

    if ( ($wcount % $numpercol == 0) and ($i< $to))
      echo "</tr>";

    $wcount += 1;
  }
  
  // output table footer
  echo "</tr></table>";
}

//
// a function to show a set of widgets at once
//
function wp_forecast_set($wset, $numpercol=1, $language_override=null)
{
  global $wpf_maxwidgets;
  $wcount=1;
  $wset_max= count($wset)-1;

  // output table header
  echo "<table><tr>";

  // out put widgets in a table
  for ($i=0;$i<=$wset_max;$i++) {

    if ( $wcount % $numpercol == 1)
      echo "<tr>";

    echo "<td>";
    wp_forecast( $wset[$i], $language_override);
    echo "</td>";

    if ( ($wcount % $numpercol == 0) and ($i< $wset_max))
      echo "</tr>";

    $wcount += 1;
  }
  
  // output table footer
  echo "</tr></table>";
}

//
// returns the widget data as an array 
//
function wp_forecast_data($wpfcid="A", $language_override=null) {
	$wpf_vars=get_wpf_opts($wpfcid);

	if (!empty($language_override)) {
		$wpf_vars['wpf_language']=$language_override;
	} 

	extract($wpf_vars);
	$w=maybe_unserialize(wpf_get_option("wp-forecast-cache".$wpfcid));

	$weather_arr=array();

	// read service dependent weather data
	switch ($wpf_vars['service']) {
	case "accu":
		$weather_arr= accu_forecast_data($wpfcid,$language_override);
		break;
	case "darksky":
		$weather_arr= darksky_forecast_data($wpfcid,$language_override);
		break;
	}

	// add openuv data to weather_arr
	if (array_key_exists('openuv', $w)) {
		$weather_arr['openuv'] = $w['openuv'];
	} else {
		$weather_arr['openuv'] = array();
	}
	
	return $weather_arr;
}


//
// set the choosen number of widgets, set at the widget page
//
function wpf_widget_setup() {
  global $wpf_maxwidgets;

  $count = $newcount = wpf_get_option('wp-forecast-count');
  if ( isset($_POST['wpf-count-submit']) ) {
    $number = (int) $_POST['wp-forecast-count'];
    if ( $number > $wpf_maxwidgets ) $number = $wpf_maxwidgets;
    if ( $number < 1 ) $number = 1;
    $newcount = $number;
  }
  if ( $count != $newcount ) {
    $count = $newcount;
    wpf_update_option('wp-forecast-count', $count);
    // add missing option to database
    wp_forecast_activate();
    // init the new number of widgets
    widget_wp_forecast_init($count);
  }
}

//
// form snippet to set the number of wanted widgets from
// the widget page
//
function wpf_widget_page() {
  global $wpf_maxwidgets;
  
  $count = $newcount = wpf_get_option('wp-forecast-count');
  
  // get locale 
  $locale = get_locale();
  if ( empty($locale) )
    $locale = 'en_US';
  // load translation 
  if(function_exists('load_plugin_textdomain')) {
  	add_filter("plugin_locale","wpf_lplug",10,2);
   	load_plugin_textdomain("wp-forecast_".$locale, false, dirname( plugin_basename( __FILE__ ) ) . "/lang/");
   	remove_filter("plugin_locale","wpf_lplug",10,2);
  }
  

  $out  = "<div class='wrap'><form method='POST' action='#'>";
  $out .= "<h2>WP-Forecast Widgets</h2>";
  $out .= "<p style='line-height: 30px;'>".__('How many wp-forecast widgets would you like?',"wp-forecast_".$locale)." ";
  $out .= "<select id='wp-forecast-count' name='wp-forecast-count'>";

  for ( $i = 1; $i <= $wpf_maxwidgets; ++$i ) {
    $out .= "<option value='$i' ";
    if ($count==$i)
      $out .= "selected='selected' ";
    $out .= ">$i</option>";
  } 
  $out .= "</select> <span class='submit'><input type='submit' name='wpf-count-submit' id='wpf-count-submit' value=".esc_attr(__('Save'))." /></span></p></form></div>";
  echo $out;

}

function reg_wpf_widget() {
	return register_widget("wpf_widget");
}

function reg_wpf_uv_widget() {
	return register_widget("wpf_uv_widget");
}

function widget_wp_forecast_init()
{

  global $wp_version,$wpf_maxwidgets;

  // include widget class
  require_once("class-wpf_widget.php");
  require_once("class-wpf_uv_widget.php");
  
  $count=(int) wpf_get_option('wp-forecast-count');

  // check for widget support
  if ( !function_exists('register_sidebar_widget') )
    return;

  // add fetch weather data to init the cache before any headers are sent
  add_action('init','wp_forecast_init');
  add_action('admin_init','wp_forecast_admin_init');
  
  // add css in header
  add_action('wp_enqueue_scripts', 'wp_forecast_css');
 
  for ($i=0;$i<=$wpf_maxwidgets;$i++) {
    $wpfcid = get_widget_id( $i );

    // register our widget and add a control
    $name = sprintf(__('wp-forecast %s'), $wpfcid);
    $id = "wp-forecast-$wpfcid"; 
    
    $uvname = sprintf(__('wp-forecast-uv %s'), $wpfcid);
    $uvid   = "wp-forecast-uv-$wpfcid"; 
    
    // add widget
    add_action('widgets_init', 'reg_wpf_widget');
    add_action('widgets_init', 'reg_wpf_uv_widget');
    
    wp_unregister_sidebar_widget($i >= $count ? 'wp_forecast_widget'.$wpfcid:'');
    
    wp_register_widget_control($id, $name, $i < $count ? 'wpf_admin_hint' : '',
			       array('width' => 300, 'height' => 150));
    
    wp_unregister_widget_control($i >= $count ? 'wpf_admin_hint'.$wpfcid : '');
  } 

  // add actions for setup the count of wanted wpf widgets
  add_action('sidebar_admin_setup', 'wpf_widget_setup');
  add_action('sidebar_admin_page', 'wpf_widget_page');

  // add filters for transport method check
  add_filter('use_fsockopen_transport','wpf_check_fsockopen');
  add_filter('use_fopen_transport','wpf_check_fopen');
  add_filter('use_streams_transport','wpf_check_streams');
  add_filter('use_http_extension_transport','wpf_check_exthttp');
  add_filter('use_curl_transport','wpf_check_curl');
}



// MAIN

// activating deactivating the plugin
register_activation_hook(__FILE__,'wp_forecast_activate');
register_deactivation_hook(__FILE__,'wp_forecast_deactivate');

// add option page 
add_action('admin_menu', 'wp_forecast_admin');

// add super admin options page (check for super admin is done inside)
add_action('admin_menu', 'wpmu_forecast_admin');

// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', 'widget_wp_forecast_init');