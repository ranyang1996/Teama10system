<?php

namespace ExtendBuilder;

add_filter( 'colibri_page_builder/assets/list', function ( $assets ) {
	$assets = array_merge( $assets, array(
		array(
		    'handle' => 'swiper',
			'src' => "swiper/js/swiper.js"
		),
		array(
            'handle' => 'swiper',
			'type' => 'css',
			'src'  => "swiper/css/swiper.css"
		)
	) );

	return $assets;
} );
