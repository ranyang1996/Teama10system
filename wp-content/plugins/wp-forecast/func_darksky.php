<?php
/* This file is part of the wp-forecast plugin for wordpress */

/*  Copyright 2019,2020  Hans Matzen  (email : webmaster at tuxlog dot de)

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
if (!function_exists('darksky_get_weather')) {
	
	function darksky_get_weather($baseuri, $apikey, $lat, $lon, $metric) {
		
		if ($metric=="1") 
			$metric="si";
		else 
			$metric="us";
		
		// check parms
		if ( trim($apikey) == "" or trim($lat) == "" or trim($lon)=="" ) return array();
		$url1 = $baseuri . $apikey . '/' . $lat . ',' . $lon . '/?exclude=minutely,hourly';
		$url1 .= '&units='.$metric.'&lang=en';
		error_log($url1);		
		// Open the file and decode it 
		$file1 = file_get_contents($url1, false);//, $context);	
		$data  = json_decode($file1, true);
		
		return $data;
	}
}


if (!function_exists('darksky_get_data')) {
	
	function darksky_get_data($weather_array, $wpf_vars) {
		$w = array();
		
		if ($wpf_vars['metric'] == "1") {
			$w['un_temp']  = 'C';
			$w['un_dist']  = 'km';
			$w['un_speed'] = 'm/s';
			$w['un_pres']  = 'mb';
			$w['un_prec']  = 'mm';
		} else {
			$w['un_temp']  = 'F';
			$w['un_dist']  = 'mi';
			$w['un_speed'] = 'mph';
			$w['un_pres']  = 'mb';
			$w['un_prec']  = 'in';
		}
		
		if ( !isset($weather_array['currently']) or !isset($weather_array['daily']) ) {
			$w['failure'] = "No DarkSky data available";
			return $w;
		}
		
		$w['lat'] = $weather_array['latitude'];
		$w['lon'] = $weather_array['longitude'];
		$w['time'] = $weather_array['currently']['time'];
		$w['timezone'] = $weather_array['timezone'];
		$mtz = new DateTimeZone($w['timezone']);
		
		//$st = new DateTime();
		//$st->setTimezone($mtz);
		//$st->setTimestamp($w['time']);
		//$w['time'] = $w['time'] + $st->getOffset();
		
		
		//error_log("c" ." " . $w['time']. " " .date_i18n("j. F Y G:i", $w['time']) );
		
		// current conditions
		$w['pressure']      = $weather_array['currently']['pressure'];
		$w['temperature']   = round( $weather_array['currently']['temperature'], 0);
		$w['realfeel']      = round($weather_array['currently']['apparentTemperature'], 0);
		$w['humidity']      = $weather_array['currently']['humidity'];
		$w['weathertext']   = $weather_array['currently']['summary'];
		$w['weathericon']   = $weather_array['currently']['icon'];
		$w['wgusts']        = $weather_array['currently']['windGust'];
		$w['windspeed']     = $weather_array['currently']['windSpeed'];
		$w['winddirection'] = $weather_array['currently']['windBearing'];
		$w['uvindex']       = $weather_array['currently']['uvIndex'];

		$w['precipProbability'] = round( $weather_array['currently']['precipProbability'] * 100, 0);
		$w['precipIntensity'] = round($weather_array['currently']['precipIntensity'], 1);
		if ($w['precipIntensity'] != 0)
			$w['precipType'] = $weather_array['currently']['precipType'];
		else
			$w['precipType'] = "";
		if ($w['precipType']  == "snow")
			$w['precipAccumulation'] = round($weather_array['currently']['precipAccumulation'], 1);
		else
			$w['precipAccumulation'] = "";
		
		// sunset sunrise
		$sr = new DateTime();
		$sr->setTimezone($mtz);
		$sr->setTimestamp($weather_array['daily']['data']['0']['sunriseTime']);
		$w['sunrise'] = $sr->format("H:i");
		
		$ss = new DateTime();
		$ss->setTimezone($mtz);
		$ss->setTimestamp($weather_array['daily']['data']['0']['sunsetTime']);
		$w['sunset'] = $ss->format("H:i");
		
		// forecast
		for($i=0;$i<=7;$i++) {
					
			$j = $i + 1;
			$odt = new DateTime();
			$odt->setTimezone($mtz);
						
			$w['fc_obsdate_'.$j]      = $weather_array['daily']['data'][$i]['time'] + $odt->getOffset();
			$w['fc_dt_short_'.$j]     = $weather_array['daily']['data'][$i]['summary'];
			$w['fc_dt_icon_'.$j]      = $weather_array['daily']['data'][$i]['icon'];
			$w['fc_dt_htemp_'.$j]     = round( $weather_array['daily']['data'][$i]['temperatureHigh'], 0);
			$w['fc_dt_ltemp_'.$j]     = round( $weather_array['daily']['data'][$i]['temperatureHigh'], 0);
			$w['fc_dt_windspeed_'.$j] = $weather_array['daily']['data'][$i]['windSpeed'];
			$w['fc_dt_winddir_'.$j]   = $weather_array['daily']['data'][$i]['windBearing'];
			$w['fc_dt_wgusts_'.$j]    = $weather_array['daily']['data'][$i]['windGust'];
			$w['fc_dt_maxuv_'.$j]     = $weather_array['daily']['data'][$i]['uvIndex'];
			$w['fc_nt_icon_'.$j]      = $weather_array['daily']['data'][$i]['icon'];
			$w['fc_nt_htemp_'.$j]     = round( $weather_array['daily']['data'][$i]['temperatureLow'], 0);
			$w['fc_nt_ltemp_'.$j]     = round( $weather_array['daily']['data'][$i]['temperatureLow'], 0);
			$w['fc_nt_windspeed_'.$j] = $weather_array['daily']['data'][$i]['windSpeed'];
			$w['fc_nt_winddir_'.$j]   = $weather_array['daily']['data'][$i]['windBearing'];
			$w['fc_nt_wgusts_'.$j]    = $weather_array['daily']['data'][$i]['windGust'];
			$w['fc_nt_maxuv_'.$j]     = $weather_array['daily']['data'][$i]['uvIndex'];
			
			$w['fc_dt_precipProbability'.$j] = round($weather_array['daily']['data'][$i]['precipProbability'] * 100,0);
			$w['fc_dt_precipIntensity'.$j] = round( $weather_array['daily']['data'][$i]['precipIntensity'], 1);
			if ( $w['fc_dt_precipIntensity'.$j] != 0)
				$w['fc_dt_precipType'.$j] = $weather_array['daily']['data'][$i]['precipType'];
			else
				$w['fc_dt_precipType'.$j] = "";
			if ($w['fc_dt_precipType'.$j]=="snow")
				$w['fc_dt_precipAccumulation'.$j] = round( $weather_array['daily']['data'][$i]['precipAccumulation'], 1);
			else
				$w['fc_dt_precipAccumulation'.$j] = "";
				
			//error_log($i ." " . $w['fc_obsdate_'.$j]. " " .date_i18n("j. F Y G:i", $w['fc_obsdate_'.$j] ));
		}
		
		// fill failure anyway
		$w['failure']=( isset($w['failure']) ? $w['failure'] : '');
		
		
		
		return $w;
	}
}

if (!function_exists('darksky_forecast_data')) {
	function darksky_forecast_data($wpfcid="A", $language_override=null) {
	
		$wpf_vars=get_wpf_opts($wpfcid);
		if (!empty($language_override)) {
			$wpf_vars['wpf_language']=$language_override;
		} 

		extract($wpf_vars);
		$w=maybe_unserialize(wpf_get_option("wp-forecast-cache".$wpfcid));

		// get translations
		if (function_exists('load_plugin_textdomain')) {
			add_filter("plugin_locale","wpf_lplug",10,2);
			load_plugin_textdomain("wp-forecast_".$wpf_language, false, dirname( plugin_basename( __FILE__ ) ) . "/lang/");
			remove_filter("plugin_locale","wpf_lplug",10,2);
		}
    
		// --------------------------------------------------------------
		// calc values for current conditions
		if ( isset($w['failure']) && $w['failure'] != '') return array('failure' => $w['failure']);

		$w['servicelink']= 'https://darksky.net/forecast/' . $w['lat'] . "," . $w['lon'] . '/si12/en';
		$w['copyright']='<a href="https://darksky.net/poweredby/">&copy; '.date("Y").' Powered by Dark Sky</a>';
	
		// next line is for compatibility
		$w['acculink']=$w['servicelink'];
		$w['location'] = $wpf_vars['locname'];
		$w['locname']= $w["location"];
		$w['shorttext'] = __($w['weathertext'], "wp-forecast_".$wpf_language);
    
    	$tz = new DateTimeZone($w['timezone']);
		$w['gmtdiff'] = $tz->getOffset( new DateTime() );
		
		//$lt = time() - date("Z"); // this is the GMT
		//$ct  = $lt + (3600 * ($w['gmtdiff'])); // local time
    
		$ct = current_time("U");
		$ct = $ct + $wpf_vars['timeoffset'] * 60; // add or subtract time offset
    		
		$w['blogdate']=date_i18n($fc_date_format, $ct);
		$w['blogtime']=date_i18n($fc_time_format, $ct);
    
		// get date/time from arksky
		$ct = $w['time'] + $w['gmtdiff'];
		$w['accudate']=date_i18n($fc_date_format, $ct);
		$w['accutime']=date_i18n($fc_time_format, $ct);
        
        $ico_arr = array(
			'clear-day' 			=> '01', 
			'clear-night' 			=> '33', 
			'rain' 					=> '12',
			'snow' 					=> '22', 
			'sleet' 				=> '29', 
			'wind' 					=> '32', 
			'fog' 					=> '11', 
			'cloudy' 				=> '06',
			'partly-cloudy-day' 	=> '04', 
			'partly-cloudy-night' 	=> '38',
			'hail'					=> '25', 
			'thunderstorm'			=> '15', 
			'tornado'				=> '32',
		); 
        $ico = "01";
        if (isset($ico_arr[$w["weathericon"]]))
			$ico = $ico_arr[$w["weathericon"]];
		$iconfile=find_icon($ico);
		$w['icon']="icons/".$iconfile;
		$w['iconcode']=$ico;

		$w['temperature'] = $w["temperature"]. "&deg;".$w['un_temp'];
		$w['realfeel'] = $w["realfeel"]."&deg;".$w['un_temp'];
		$w['humidity'] = round($w['humidity'] * 100, 0);

		// workaround different pressure values returned by accuweather
		$press = round($w["pressure"],0);
		if (strlen($press)==3 and substr($press,0,1)=="1")
			$press = $press * 10;
		$w['pressure'] = $press . " " . $w["un_pres"];
		$w['humidity']=round($w["humidity"],0);
		$w['windspeed']=windstr($metric,$w["windspeed"],$windunit);
		$w['winddir']=translate_winddir_degree($w["winddirection"],"wp-forecast_".$wpf_language);
		$w['winddir_orig']=str_replace('O','E',$w["winddir"]);
		$w['windgusts']=windstr($metric,$w["wgusts"],$windunit);
	
    
    
		// calc values for forecast
		for ($i = 1; $i < 8; $i++) {
			// daytime forecast
			$w['fc_obsdate_'.$i]= date_i18n($fc_date_format, $w['fc_obsdate_'.$i]);
			$ico = "01";
			if (isset($ico_arr[$w["fc_dt_icon_".$i]]))
				$ico = $ico_arr[$w["fc_dt_icon_".$i]];
			$iconfile=find_icon($ico);
			$w["fc_dt_icon_".$i]="icons/".$iconfile;
			$w["fc_dt_iconcode_".$i]=$ico;
			$w["fc_dt_desc_".$i]= __($ico,"wp-forecast_".$wpf_language);
			$w["fc_dt_htemp_".$i]= $w["fc_dt_htemp_".$i]."&deg;".$w['un_temp'];
			$wstr=windstr($metric,$w["fc_dt_windspeed_".$i],$windunit);
			$w["fc_dt_windspeed_".$i]= $wstr;
			$w["fc_dt_winddir_".$i]=translate_winddir_degree($w["fc_dt_winddir_".$i],"wp-forecast_".$wpf_language);
			$w["fc_dt_winddir_orig_".$i]=str_replace('O','E',$w["fc_dt_winddir_".$i]);
			$w["fc_dt_wgusts_".$i] = windstr($metric,$w["fc_dt_wgusts_".$i],$windunit);
			$w['fc_dt_maxuv_'.$i]=$w['fc_dt_maxuv_'.$i];
     
			// nighttime forecast
			$ico = "01";
			if (isset($ico_arr[$w["fc_nt_icon_".$i]]))
				$ico = $ico_arr[$w["fc_nt_icon_".$i]];
			$iconfile=find_icon($ico);
			$w["fc_nt_icon_".$i]="icons/".$iconfile;
			$w["fc_nt_iconcode_".$i]=$ico;
			$w["fc_nt_desc_".$i]= __($ico,"wp-forecast_".$wpf_language);
			$w["fc_nt_ltemp_".$i]= $w["fc_nt_ltemp_".$i]."&deg;".$w['un_temp'];
			$wstr=windstr($metric,$w["fc_nt_windspeed_".$i],$windunit);
			$w["fc_nt_windspeed_".$i]= $wstr;
			$w["fc_nt_winddir_".$i]=translate_winddir_degree($w["fc_nt_winddir_".$i],"wp-forecast_".$wpf_language);
			$w["fc_nt_winddir_orig_".$i]=str_replace('O','E',$w["fc_nt_winddir_".$i]);
			$w["fc_nt_wgusts_".$i] = windstr($metric,$w["fc_nt_wgusts_".$i],$windunit);
			$w['fc_nt_maxuv_'.$i]=$w['fc_nt_maxuv_'.$i];      
		}

		return $w;	
	}
}