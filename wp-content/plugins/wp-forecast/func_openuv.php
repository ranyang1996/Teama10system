<?php
/* This file is part of the wp-forecast plugin for wordpress */

/*  Copyright 2018  Hans Matzen  (email : webmaster at tuxlog dot de)

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
if (!function_exists('openuv_get_data')) {
	
	function openuv_get_data($apikey, $lat, $lon) {
		// check parms
		if ( trim($apikey) == "" or trim($lat) == "" or trim($lon)=="" ) return array();
		
		$url1 = 'https://api.openuv.io/api/v1/uv?lat=' . $lat . '&lng=' . $lon; // '&alt=' + alt + '&ozone=' + ozone + 
		$url2 = 'https://api.openuv.io/api/v1/forecast?lat=' . $lat . '&lng=' . $lon;
		
		// Create a stream
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"x-access-token: $apikey\r\n" 
			)
		);

		$context = stream_context_create($opts);

		// Open the file using the HTTP headers set above
		$file1 = file_get_contents($url1, false, $context);	
		$data  = json_decode($file1, true);
		
		$file2 = file_get_contents($url2, false, $context);	
		$data2 = json_decode($file2, true);
		
		// add forecast data to array
		$data['result']['forecast'] = $data2['result'];
		
		// add copyright notice
		$data['result']['copyright'] = "UV Data is delivered by openuv.io";
		
		return $data['result'];
	}
}