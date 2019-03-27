<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use SSM\Includes\Walker;
use SSM\Includes\Helpers as SSMH;

class PageBuilder extends Controller
{

    protected $acf = true;

    public function builder() {
        return $this;
    }

    public static function getCustomID( $args )
    {

        $response = '';

        $inline_id = $args->option_html_id;
        $response .= ( $inline_id ) ? ' id="' . sanitize_html_class( strtolower( $inline_id ) ) . '"' : '';

        return $response;

    }

    public static function getCustomClasses( $context, $column_index, $args )
    {
        
        $response = '';
        
        $inline_classes = $args->option_html_classes;
        $odd = ( !empty( $column_index ) && $column_index % 2 == 0 ) ? 'even' : 'odd';
        $column_index++;
        
        switch ( $context ) {

            case 'template':
                $response .= ' class="content-block';
                break;
            
            case 'hero-unit':
                $response .= ' class="hero-unit';
                break;
            
            case 'module':
                $response .= ' class="component ' . $args->acf_fc_layout . ' stack-order-' . $column_index . ' stack-order-' . $odd;
                break;
                
        }
        
        switch ( $args->option_background ) {

            case 'Color':
                $response .= ' ' . sanitize_html_class( $args->option_background_color );
                break;
            
            case 'Image':
                $response .= ' bg-image bg-dark';
                break;
            
            case 'Video':
                $response .= ' bg-video';
                break;
        
        }

        if ( $context == 'hero-unit' && !is_null( $args->option_hero_unit_height ) ) {
            $response .= ( $args->option_hero_unit_height == 'full' ) ? ' full-height' : ' auto';
        }

        $response .= ( !empty( $inline_classes ) ) ? ' ' . $inline_classes : '';

        $response .= '"';
        
        return $response;
    
    }

    public static function getColumnsWidth( $column_index ) {
    
        global $post;
        return get_post_meta( $post->ID, 'custom_columns_width_' . $column_index, true);
    
    }

    public static function getMenuArgs( $context ) {

        $response = array();

        if ( $context == 'offCanvas') {
            
            $response = array( 
                'theme_location' => 'primary_navigation', 
                'container' => FALSE, 
                'items_wrap' => '<ul class="vertical menu accordion-menu" data-accordion-menu>%3$s</ul>', 
                'walker' => new Walker()
            );

        } elseif ( $context == 'primary_navigation' ) {

            $response = array( 
                'theme_location' => 'primary_navigation', 
                'container' => FALSE, 
                'items_wrap' => '<ul class="dropdown menu show-for-medium" data-dropdown-menu>%3$s</ul>', 
                'walker' => new Walker()
            );

        } elseif ( $context == 'footer_navigation' ) {

            $response = array(
                'theme_location' => 'footer_navigation', 
                'container' => FALSE, 
                'items_wrap' => '<ul class="menu vertical">%3$s</ul>', 
                'walker' => new Walker()
            );
            
        }

        return $response;
    
    }

    public static function getTheAddress( $address ) {

        $response = '';

        $street1 = $address->street1;
        $street2 = $address->street2;
        $city = $address->city;
        $state = $address->state;
        $zip = $address->zip;

        if ( $street1 || $street2 || $city || $state || $zip ) {
            $response .= ( $street1 ) ? $street1 : '';
            $response .= ( $street2 ) ? ", " . $street2 : '';
            $response .= ( $city ) ? "<br />" . $city : '';
            $response .= ( $state ) ? ", " . $state : '';
            $response .= ( $zip ) ? " " . $zip : '';
        }

        return $response;
    
    }

    public static function getPreparedAddress( $address ) {

        $prepared_url = $address->street1;
        $prepared_url .= ( $address->street2 ) ? $address->street2 : '';
        $prepared_url .= ' ' . $address->city;
        $prepared_url .= ' ' . $address->state;
        $prepared_url .= ' ' . $address->zip;
        $prepared_url = urlencode($prepared_url);

        return $prepared_url;
    
    }

    public static function getPreparedPhoneNumber( $number ) {

        $formatted = '';
        
        $pieces = explode(' ', $number );
 
        $formatted = '(' . $pieces[0] . ') ' . $pieces[1];
        $formatted .= ( isset( $pieces[2] ) ) ? $pieces[2] : '';
        $formatted .= ( isset( $pieces[3] ) ) ? $pieces[3] : '';

        return $formatted;

    }

}
