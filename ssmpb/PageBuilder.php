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
    
    //section_id_classes()
    public static function getTemplateClasses( $custom_classes, $inline_classes, $inline_id, $option_background, $background_color ) {

        $response = '';

        $response .= ( $inline_id ) ? ' id="' . sanitize_html_class( strtolower( $inline_id ) ) . '" class="content-block' : ' class="content-block';

        switch ( $option_background ) {

            case 'Color':
                $response .= ' ' . sanitize_html_class( $background_color );
                break;
            case 'Image':
                $response .= ' bg-image bg-dark';
                break;
            case 'Video':
                $response .= ' bg-video';
                break;
        
        }

        $response .=  ( !is_null( $custom_classes ) ) ? ' ' . SSMH::sanitizeHtmlClasses( $custom_classes ) : '';

        $response .= ( !is_null( $inline_classes ) ) ? ' ' . $inline_classes : '';

        $response .= '"';

        return $response;

    }

    //component_id_classes()
    public static function getModuleClasses( $custom_classes, $inline_classes, $inline_id, $column_index ) {

        $response = '';

        $odd = ( $column_index % 2 == 0 ) ? 'even' : 'odd';

        $response .= ( $inline_id ) ? ' id="' . sanitize_html_class( strtolower( $inline_id ) ) . '" class="module stack-order-' . $column_index . ' stack-order-' . $odd : ' class="module stack-order-' . $column_index . ' stack-order-' . $odd ;
                    
        $response .=  ( !is_null( $custom_classes ) ) ? ' ' . SSMH::sanitizeHtmlClasses( $custom_classes ) : '';
        
        $response .=  ( !is_null( $inline_classes ) ) ? ' ' . $inline_classes : '';

        $response .= '"';
        
        return $response;

    }

    // hero_unit_id_classes()
    public static function getHeroUnitClasses( $custom_classes, $inline_classes, $inline_id, $option_background, $background_color, $height ) {
        
        $response = '';

        $response .= ( $inline_id ) ? ' id="' . sanitize_html_class( strtolower( $inline_id ) ). '" class="hero-unit' : '';

        switch ( $option_background ) {

            case 'Color':
                $response .= ' ' . sanitize_html_class( $background_color );
                break;
            case 'Image':
                $response .= ' bg-image bg-dark';
                break;
            case 'Video':
                $response .= ' bg-video';
                break;
        
        }

        $response .= ( $height == 'full' ) ? ' full-height' : ' auto';

        $response .=  ( !is_null( $custom_classes ) ) ? ' ' . SSMH::sanitizeHtmlClasses( $custom_classes ) : '';

        $response .= ( !is_null( $inline_classes ) ) ? ' ' . $inline_classes : '';

        $response .= '"';

        return $response;

    }


}
