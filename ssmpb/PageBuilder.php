<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use SSM\Includes\Helpers as SSMH;

class PageBuilder extends Controller
{

    protected $acf = true;

    public function builder() {
        return $this;
    }

    public static function getCustomClasses( $custom_classes, $context, $column_index, $args ) {

        $response = '';

        $inline_id = $args->option_html_id;
        $inline_classes = $args->option_html_classes;
        $odd = ( !empty( $column_index ) && $column_index % 2 == 0 ) ? 'even' : 'odd';

        $response .= ( $inline_id ) ? ' id="' . sanitize_html_class( strtolower( $inline_id ) ) . '"' : '';
        
        switch ( $context ) {

            case 'template':
                $response .= ' class="content-block ';
                break;
            case 'hero-unit':
                $response .= ' class="hero-unit ';
                break;
            case 'module':
                $response .= ' class="module stack-order-' . $column_index . ' stack-order-' . $odd;
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

        $response .=  ( !is_null( $custom_classes ) ) ? ' ' . SSMH::sanitizeHtmlClasses( $custom_classes ) : '';

        $response .= ( !is_null( $inline_classes ) ) ? ' ' . $inline_classes : '';

        $response .= '"';

        return $response;

    }

}
