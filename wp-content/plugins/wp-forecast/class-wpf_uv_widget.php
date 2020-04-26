<?php
/**
 * class for UV widget displaying the data from OpenUV.com
 *
 * @package default
 */

if (!class_exists('wpf_uv_widget')) {
	
	class wpf_uv_widget extends WP_Widget {
		/**
		 *
		 */
		
		function __construct() {
			$widget_ops = array(
				'classname' => 'wp_forecast_uv_widget',
				'description' => 'WP Forecast UV-Widget'
			);
			$control_ops = array(
				'width' => 300,
				'height' => 150
			);
			parent::__construct('wp-forecast-uv', 'WP Forecast UV', $widget_ops, $control_ops);
		}
		/**
		 *
		 * @param unknown $args
		 * @param unknown $instance
		 */
		
		function widget($args, $instance) {
			// get widget params from instance
			$title = $instance['title'];
			$wpfcid = $instance['wpfcid'];
			$safeexposure = (isset($instance['safeexposure']) ? esc_attr($instance['safeexposure']) : "");
			$showgraph = (isset($instance['showgraph']) ? esc_attr($instance['showgraph']) : "");
			
			if (trim($wpfcid) == "") $wpfcid = "A";
			// pass title to show function
			$args['title'] = $title;
			
			if ($wpfcid == "?") $wpf_vars = get_wpf_opts("A");
			else $wpf_vars = get_wpf_opts($wpfcid);
			
			if (!empty($language_override)) {
				$wpf_vars['wpf_language'] = $language_override;
			}
			// call display method
			$this->show($wpfcid, $args, $wpf_vars, $showgraph, $safeexposure);
		}
		/**
		 *
		 * @param unknown $new_instance
		 * @param unknown $old_instance
		 * @return unknown
		 */
		
		function update($new_instance, $old_instance) {
			// update semaphor counter for loading wpf ajax script
			
			if ($old_instance['wpfcid'] != $new_instance['wpfcid']) {
				$semnow = get_option('wpf_sem_ajaxload');
				
				if ($new_instance['wpfcid'] == '?') update_option('wpf_sem_ajaxload', $semnow + 1);
				else update_option('wpf_sem_ajaxload', ($semnow - 1 < 0 ? 0 : $semnow - 1));
			}
			return $new_instance;
		}
		/**
		 *
		 * @param unknown $instance
		 */
		
		function form($instance) {
			$count = wpf_get_option('wp-forecast-count');
			// get translation
			$locale = get_locale();
			
			if (empty($locale)) $locale = 'en_US';
			
			if (function_exists('load_plugin_textdomain')) {
				add_filter("plugin_locale", "wpf_lplug", 10, 2);
				load_plugin_textdomain("wp-forecast_" . $locale, false, dirname(plugin_basename(__FILE__)) . "/lang/");
				remove_filter("plugin_locale", "wpf_lplug", 10, 2);
			}
			$title = (isset($instance['title']) ? esc_attr($instance['title']) : "");
			$wpfcid = (isset($instance['wpfcid']) ? esc_attr($instance['wpfcid']) : "");
			$safeexposure = (isset($instance['safeexposure']) ? esc_attr($instance['safeexposure']) : "");
			$showgraph = (isset($instance['showgraph']) ? esc_attr($instance['showgraph']) : "");
			// code for widget title form
			$out = "";
			$out.= '<p><label for="' . $this->get_field_id('title') . '" >';
			$out.= __("Title:", "wp-forecast_" . $locale);
			$out.= '<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
			// print out widget selector
			$out.= '<p><label for ="' . $this->get_field_id('wpfcid') . '" >';
			$out.= __('Available widgets', "wp-forecast_" . $locale);
			$out.= "<select name='" . $this->get_field_name("wpfcid") . "' id='" . $this->get_field_id('wpfcid') . "' size='1' >";
			// option for choose dialog
			$out.= "<option value='?' ";
			
			if ($wpfcid == "?") $out.= " selected='selected' ";
			$out.= ">?</option>";
			
			for ($i = 0;$i < $count;$i++) {
				$id = get_widget_id($i);
				$out.= "<option value='" . $id . "' ";
				
				if ($wpfcid == $id or ($wpfcid == "" and $id == "A")) $out.= " selected='selected' ";
				$out.= ">" . $id . "</option>";
			}
			$out.= "</select></label></p>";
			$out.= '<p><label for ="' . $this->get_field_id('showgraph') . '" >';
			$out.= __("Show UV graph:", "wp-forecast_" . $locale);
			$checked = ($showgraph == 1 ? "checked='checked'" : "");
			$out.= '<input type="checkbox" name="' . $this->get_field_name("showgraph") . '" id="' . $this->get_field_id('showgraph') . '" value="1" ' . $checked . '>';
			$out.= "</label></p>";
			$out.= '<p><label for ="' . $this->get_field_id('safeexposure') . '" >';
			$out.= __("Show safe exposure time:", "wp-forecast_" . $locale);
			$checked = ($safeexposure == 1 ? "checked='checked'" : "");
			$out.= '<input type="checkbox" name="' . $this->get_field_name("safeexposure") . '" id="' . $this->get_field_id('sageexposure') . '" value="1" ' . $checked . '>';
			$out.= "</label></p>";
			echo $out;
		}
		/**
		 *
		 * @param unknown $wpfcid
		 * @param unknown $args
		 * @param unknown $wpfvars
		 * @return unknown
		 */
		
		function show($wpfcid, $args, $wpfvars, $showgraph, $safeexposure) {
			// check how we are called as a widget or from sidebar
			
			if (sizeof($args) == 0) $show_from_widget = 0;
			else $show_from_widget = 1;
			// order is important to override old title in wpfvars with new in args
			extract($wpfvars);
			extract($args);
			// get translations
			
			if (function_exists('load_plugin_textdomain')) {
				add_filter("plugin_locale", "wpf_lplug", 10, 2);
				load_plugin_textdomain("wp-forecast_" . $wpf_language, false, dirname(plugin_basename(__FILE__)) . "/lang/");
				remove_filter("plugin_locale", "wpf_lplug", 10, 2);
			}
			$plugin_path = plugins_url("", __FILE__);
			$w = wp_forecast_data($wpfcid, $wpf_language);
			// output current conditions
			$out = "";
			$out.= "\n<div class=\"wp-forecast-curr\">\n";
			// if the provider sends us a failure notice print it and return
			
			if ($w['failure'] != "") {
				$out.= __("Failure notice from provider", "wp-forecast_" . $wpf_language) . ":<br />";
				$out.= $w['failure'] . "</div>";
				// print it
				
				if ($show_from_widget == 1) echo $before_widget . $before_title . $title . $after_title . $out . $after_widget;
				else echo $out;
				return false;
			}
			// if error print an error message and return
			
			if (count($w) <= 0) {
				$out.= __("Sorry, no valid weather data available.", "wp-forecast_" . $wpf_language) . "<br />";
				$out.= __("Please try again later.", "wp-forecast_" . $wpf_language) . "</div>";
				// print it
				
				if ($show_from_widget == 1) echo $before_widget . $before_title . $title . $after_title . $out . $after_widget;
				else echo $out;
				return false;
			}
			// ortsnamen ausgeben parameter fuer open in new window berücksichtigen
			$servicelink = "";
			$servicelink_end = "";
			
			if (substr($dispconfig, 25, 1) == "1") {
				$servicelink = '<a href="' . $w['servicelink'] . '"';
				$servicelink_end = "</a>";
				
				if (substr($dispconfig, 26, 1) == "1") $servicelink = $servicelink . ' target="_blank" >';
				else $servicelink = $servicelink . ' >';
			}
			$out.= '<div class="wp-forecast-curr-head">';
			
			if ($w['location'] == "" or $wpfvars['visitorlocation'] == 1) $out.= "<div>" . $servicelink . $w['locname'] . $servicelink_end . "</div>\n";
			else 
			if (trim($w['location']) != "" and $w['location'] != "&nbsp;") $out.= "<div>" . $servicelink . $w['location'] . $servicelink_end . "</div>\n";
			// show date / time
			
			// if current time should be used
			
			if ($currtime == "1") {
				$cd = $w['blogdate'];
				$ct = $w['blogtime'];
			}
			else 
			if ($service == "accu") {
				// else take given accuweather time
				$cd = $w['accudate'];
				$ct = $w['accutime'];
			}
			else 
			if ($service == "bug") {
				// else take given weatherbug time
				$cd = $w['bugdate'];
				$ct = $w['bugtime'];
			}
			
			if (substr($dispconfig, 18, 1) == "1" or substr($dispconfig, 1, 1) == "1") {
				$out.= "<div>";
				
				if (substr($dispconfig, 18, 1) == "1") $out.= $cd;
				else $out.= __('time', "wp-forecast_" . $wpf_language) . ": ";
				
				if (substr($dispconfig, 18, 1) == "1" and substr($dispconfig, 1, 1) == "1") $out.= ", ";
				
				if (substr($dispconfig, 1, 1) == "1") $out.= $ct;
				$out.= "</div>\n";
			}
			$out.= "</div>\n";
			$out.= '<div class="wp-forecast-curr-block">';
			// show icon
			$out.= "<div class='wp-forecast-curr-left'>";
			
			if ($service == "accu") {
				$uviconno = round($w['openuv']['uv']);
				$uvicon = "UVIndex" . $uviconno . ".jpg";
				$breite = 0;
				$hoehe = 0;
				
				if (file_exists(plugin_dir_path(__FILE__) . "/icons/" . $uvicon)) {
					$isize = getimagesize(plugin_dir_path(__FILE__) . "/icons/" . $uvicon);
					
					if ($isize != false) {
						$breite = $isize[0];
						$hoehe = $isize[1];
					}
				}
				$out.= "<img class='awp-forecast-curr-left' src='" . $plugin_path . "/icons/" . $uvicon . "' alt='" . $w['shorttext'] . "' width='" . $breite . "' height='" . $hoehe . "' />\n";
			}
			$out.= "<br />";
			$out.= "</div>";
			$out.= "</div>\n"; // end of block
			$out.= "<div class=\"wp-forecast-curr-details\">";
			// show data from OpenUV.io if applicable
			
			if (trim($ouv_apikey) != "") {
				
				if ($ouv_uv) $out.= "<div>" . __('Current UV-Index', "wp-forecast_" . $wpf_language) . ": " . $w['openuv']['uv'] . "</div>\n";
				
				if ($ouv_uvmax) $out.= "<div>" . __('Max. UV-Index', "wp-forecast_" . $wpf_language) . ": " . $w['openuv']['uv_max'] . "</div>\n";
				
				if ($ouv_ozone) $out.= "<div>" . __('Ozone', "wp-forecast_" . $wpf_language) . ": " . $w['openuv']['ozone'] . " DU</div>\n";
				$out.= "<br/>";
				
				if ($ouv_safetime || $safeexposure) {
					$j = 1;
					
					foreach ($w['openuv']['safe_exposure_time'] as $set) {
						
						if (trim($set) != "") $out.= "<div>" . __('Safe Exposure Time for Skin Type', "wp-forecast_" . $wpf_language) . " $j: " . $set . " Min.</div>\n";
						else $out.= "<div>" . __('Safe Exposure Time for Skin Type', "wp-forecast_" . $wpf_language) . " $j: &infin; Min.</div>\n";
						$j++;
					}
					$out.= "<br/>";
				}
				
				if ($showgraph) {
					$countValues = count($w['openuv']['forecast']);
					// determine min and max values
					$uvmin = 99;
					$uvmax = 0;
					$data = Array();
					
					foreach ($w['openuv']['forecast'] as $uvfc) {
						
						if ($uvfc['uv'] > $uvmax) $uvmax = $uvfc['uv'];
						
						if ($uvfc['uv'] < $uvmin) $uvmin = $uvfc['uv'];
						$d = new DateTime($uvfc['uv_time']);
						$data[(int)$d->format("H") ] = round($uvfc['uv'], 1) * 10;
					}
					$scaleYmax = ((int)$uvmax + 1) * 10;
					// print out diagram
					$out.= <<<EOT
					<style>
					div.wpf-uv-bar-on    {float: left; width: 15px; height: 5px; background-color: #666666; margin: 1px;}
					div.wpf-uv-bar-off   {float: left; width: 15px; height: 5px; background-color: rgb(255,255,255,0); margin: 1px;}
					div.wpf-uv-row-clear {clear: both;}
					div.wpf-uv-bar-left  {float:left; width: 20px; height: 5px; border-style: none none none none; border-color: #335588; border-width: 3px;}
					div.wpf-uv-bar-right {float:left; width: 20px; height: 5px; border-style: none none none solid; border-color: #335588; border-width: 3px;}
					div.wpf-uv-row-left  {float:left; width: 20px; height: 5px; border-style: solid none none none; border-color: #335588; border-width: 3px;margin-top:7px;}
					</style>
EOT;
					
					for ($i = $scaleYmax;$i >= 0;$i--) {
						
						if ($i % 10 == 0) $out.= "<div class='wpf-uv-bar-left'>" . (int)($i / 10) . "</div>";
						else $out.= "<div class='wpf-uv-bar-left'>&nbsp;</div>";
						
						foreach ($data as $dk => $dv) {
							
							if ($dv >= $i) $out.= "<div class='wpf-uv-bar-on'>&nbsp;</div>";
							else $out.= "<div class='wpf-uv-bar-off'>&nbsp;</div>";
						}
						$out.= "<div class='wpf-uv-row-clear'></div>";
					}
					// print x -axis
					$out.= "<div class='wpf-uv-row-left'>&nbsp;</div>";
					
					foreach ($data as $dk => $dv) {
						$out.= "<div class='wpf-uv-row-left'>$dk</div>";
					}
					$out.= "<div class='wpf-uv-row-clear'></div><br/>";
					$d = new DateTime($w['openuv']['forecast'][0]['uv_time']);
					$d1 = new DateTime($w['openuv']['forecast'][count($w['openuv']['forecast']) - 1]['uv_time']);
					$out.= "<div>Werte vom " . $d->format("d.m.y") . " von " . $d->format("H:i") . " bis " . $d1->format("H:i") . " Uhr.</div>";
				}
				// print copyright notice
				$out.= "<div class='wpf-uv-row-clear'></div><br/>";
				$out.= "<div class=\"wp-forecast-copyright\">" . __($w['openuv']['copyright'], "wp-forecast_" . $wpf_language) . "</div>";
			}
			// show copyright
			
			if (substr($dispconfig, 21, 1) == "1") $out.= "<div class=\"wp-forecast-copyright\">" . $w['copyright'] . "</div>";
			$out.= "</div>\n"; // end of details
			$out.= "</div>\n"; // end of curr
			
			// print it
			
			if ($show_from_widget == 1) echo $before_widget . $before_title . $title . $after_title;
			echo '<div id="wp-forecast' . $wpfcid . '" class="wp-forecast">' . $out . '</div>' . "\n";
			// to come back to theme floating status
			echo '<div style="clear:inherit;">&nbsp;</div>';
			
			if ($show_from_widget == 1) echo $after_widget;
		}
	}
}