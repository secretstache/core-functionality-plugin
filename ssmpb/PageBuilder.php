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

    //get_inline_styles()
    public static function getInlineStyles ( $option_background, $image ) {

        if ( $option_background == 'Image' && !is_null( $image ) ) {

            $style = ' style="background-image: url(' . $image->url . ')"';
        
        }

        return $style;

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

    // the_template_header()
    public static function getTemplateHeader( $include, $headline, $subheadline ) {

        $response = '';

        if ( $include ) {

            $response = '<div class="grid-container">';
            $response .= '<div class="grid-x grid-x-margin align-center">';
                $response .= '<div class="cell small-12 medium-10">';
                    $response .= '<header class="component template-header align-center">';

                        $response .= ( $headline ) ? '<h2 class="headline">' . $headline . '</h2>' : '';

                        $response .= ( $subheadline ) ? '<h3 class="subheadline">' . $subheadline .'</h3>' : '';

                    $response .= '</header>';
                $response .= '</div>';
                $response .= '</div>';
            $response .= '</div>';

        }

        return $response;

    }

    // the_video_background()
    public static function getVideoBackground( $option_background, $video ) {

        $response = '';

        if ( $option_background == 'Video' && !is_null( $video ) ) {
                
            $response = '<div class="template-video">';
            $response .= '<video autoplay loop>';
            $response .= '<source src="' . $video->url . '" type="video/mp4">';
            $response .= '</video>';
            $response .= '</div>';
            $response .= '<div class="overlay"></div>';
        }

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
