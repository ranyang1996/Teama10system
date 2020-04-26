<?php if( ! defined( 'ABSPATH' ) ) exit; ?>
<!DOCTYPE html>
<html>
<head>
<title>Insert shortcode</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="<?php echo SC_ADMIN_URL; ?>css/style-insert.css<?php echo '?ver=' . SC_VERSION; ?>" media="all" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="<?php echo SC_ADMIN_URL; ?>js/script-insert.js<?php echo '?ver=' . SC_VERSION; ?>"></script>
</head>
<body>

<div class="sc_menu">
    <input type="search" class="sc_search" placeholder="Search ..." />
    <a href="https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/" target="_blank" class="ufw"><i>Check out</i> : Ultimate floating widgets<span>A WordPress plugin to create floating widgets.</span></a>
</div>

<div class="sc_list">
<?php

$shortcodes = Shortcoder::get_shortcodes();

if( empty( $shortcodes ) ){
    echo '<p class="sc_note">No shortcodes are created, go ahead create one in <a href="' . admin_url( 'post-new.php?post_type=' . SC_POST_TYPE ) . '" target="_blank">shortcoder admin page</a>.</p>';
}else{

    foreach( $shortcodes as $name => $options ){
        $id = $options[ 'id' ];
        $content = $options[ 'content' ];
        $settings = $options[ 'settings' ];
        $params = array();

        preg_match_all( '/%%(.*?)%%/', $content, $matches );

        $cp_data = $matches[1];

        if( !empty( $cp_data ) ){

            $cp_data = array_map( 'strtolower', $cp_data );

            foreach( $cp_data as $data ){
                $colon_pos = strpos( $data, ':' );
                if( $colon_pos === false ){
                    array_push( $params, trim( $data ) );
                }else{
                    $cp_name = substr( $data, 0, $colon_pos );
                    array_push( $params, trim( $cp_name ) );
                }
            }
        }

        $enclosed_sc = strpos( $content, '$$enclosed_content$$' ) !== false ? 'true' : 'false';

        echo '<div class="sc_wrap" data-name="' . esc_attr( $name ) . '" data-id="' . esc_attr( $id ) . '" data-enclosed="' . $enclosed_sc . '">';
            echo '<div class="sc_head">';
                echo '<img src="' . SC_ADMIN_URL . '/images/arrow.svg" width="16" />';
                echo '<h3>' . $settings[ '_sc_title' ] . '</h3>';
                echo '<div class="sc_tools">';
                    if( current_user_can( 'edit_post', $id ) ){
                        echo '<a href="' . admin_url( 'post.php?action=edit&post=' . $id ) . '" class="button" target="_blank">' . __( 'Edit', 'shortcoder' ) . '</a>';
                    }
                    echo '<button class="button sc_copy">' . __( 'Copy', 'shortcoder' ) . '</button>';
                    echo '<button class="button sc_insert">' . __( 'Insert', 'shortcoder' ) . '</button>';
                echo '</div>';
            echo '</div>';

            echo '<div class="sc_options">';

            if( !empty( $params ) ){
                echo '<h4>' . __( 'Available parameters', 'shortcoder' ) . ': </h4>';
                echo '<div class="sc_params_wrap">';
                $temp = array();

                foreach( $params as $k => $v ){
                    $cleaned = str_replace( '%', '', $v );
                    if( !in_array( $cleaned, $temp ) ){
                        array_push( $temp, $cleaned );
                        echo '<label>' . $cleaned . ': <input type="text" class="sc_param" data-param="' . $cleaned . '"/></label> ';
                    }
                }

                echo '</div>';

            }else{
                echo '<p>' . __( 'No parameters present in this shortcode', 'shortcoder' ) . '</p>';
            }

            echo '<div class="sc_foot">';
                echo '<button class="sc_insert button button-primary">' . __( 'Insert shortcode', 'shortcoder' ) . '</button>';
                if( $enclosed_sc == 'true' ){
                    echo '<span>' . __( 'Has enclosed content parameter', 'shortcoder' ) . '</span>';
                }
            echo '</div>';

            echo '</div>';
        echo '</div>';
    }

    echo '<p class="sc_note sc_search_none">' . __( 'No shortcodes match search term !', 'shortcoder' ) . '</p>';

}

?>
</div>

<div class="cfe_box">
<?php
echo '<div class="cfe_text">';
    echo '<img src="' . SC_ADMIN_URL . '/images/coffee.svg" />';
    echo '<div><h2>Buy me a Coffee !</h2><p>If you like this plugin, buy me a coffee !</p></div>';
echo '</div>';
echo '<div class="cfe_form">';
    echo '<select class="cfe_amt">';
    for($i = 5; $i <= 15; $i++){
        echo '<option value="' . $i . '" ' . ($i == 6 ? 'selected="selected"' : '') . '>$' . $i . '</option>';
    }
    echo '<option value="">Custom</option>';
    echo '</select>';
    echo '<a class="button button-primary cfe_btn" href="https://www.paypal.me/vaakash/6" data-link="https://www.paypal.me/vaakash/" target="_blank">Buy me coffee !</a>';
echo '</div>';
?>
</div>

<div class="footer_thanks">Thanks for using <a href="https://www.aakashweb.com/wordpress-plugins/shortcoder/" target="_blank">Shortcoder</a> &bull; Please <a href="https://wordpress.org/support/plugin/shortcoder/reviews/?rate=5#new-post" target="_blank">rate 5 stars</a> and spread the word.</div>

</body>
</html>