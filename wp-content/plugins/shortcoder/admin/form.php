<?php

if( ! defined( 'ABSPATH' ) ) exit;

class SC_Admin_Form{

    public static function table( $rows = array(), $print = false, $class = '' ){
        
        $html = '<table class="form-table ' . $class . '">';
        
        foreach( $rows as $row ){
            $html .= '<tr ' . ( isset( $row[2] ) ? $row[2]  : '' ) . '>';
                $html .= '<th>' . ( isset( $row[0] ) ? $row[0] : '' ) . '</th>';
                $html .= '<td>' . ( isset( $row[1] ) ? $row[1] : '' ) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        if( $print ){
            echo $html;
        }else{
            return $html;
        }
        
    }

    public static function field( $field_type, $params = array() ){
        
        $defaults = array(

            'text' => array(
                'type' => 'text',
                'value' => '',
                'id' => '',
                'class' => 'regular-text',
                'name' => '',
                'placeholder' => '',
                'required' => '',
                'helper' => '',
                'tooltip' => '',
                'custom' => ''
            ),

            'select' => array(
                'id' => '',
                'class' => '',
                'name' => '',
                'list' => array(),
                'value' => '',
                'helper' => '',
                'tooltip' => '',
                'custom' => ''
            ),

            'textarea' => array(
                'type' => 'text',
                'value' => '',
                'name' => '',
                'id' => '',
                'class' => '',
                'placeholder' => '',
                'rows' => '',
                'cols' => '',
                'helper' => '',
                'tooltip' => '',
                'custom' => ''
            )

        );
        
        $params = wp_parse_args( $params, $defaults[ $field_type ] );
        $field_html = '';
        
        extract( $params, EXTR_SKIP );
        
        switch( $field_type ){
            case 'text':
                $field_html = "<input type='$type' class='$class' id='$id' name='$name' value='$value' placeholder='$placeholder' " . ( $required ? "required='$required'" : "" ) . "  $custom />";
            break;
            
            case 'select':
                $field_html .= "<select name='$name' class='$class' id='$id' $custom>";
                foreach( $list as $k => $v ){
                    $field_html .= "<option value='$k' " . selected( $value, $k, false ) . ">$v</option>";
                }
                $field_html .= "</select>";
            break;

            case 'textarea':
                $field_html .= "<textarea id='$id' name='$name' class='$class' placeholder='$placeholder' rows='$rows' cols='$cols' $custom>$value</textarea>";
            break;

        }

        if( !empty( $tooltip ) ){
            $field_html .= "<div class='sc-tt'><span class='dashicons dashicons-editor-help'></span><span class='sc-tt-text'>$tooltip</span></div>";
        }
        
        if( !empty( $helper ) ){
            $field_html .= "<p class='description'>$helper</p>";
        }
        
        return $field_html;
        
    }

}

?>