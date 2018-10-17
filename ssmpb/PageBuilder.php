<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use SSM\Includes\Helpers as SSMH;

class PageBuilder extends Controller
{

    protected $modules_dir = SSMC_DIR . 'ssmpb/modules';
    protected $templates_dir = SSMC_DIR . 'ssmpb/templates';
    protected $acf = true;

    public function builder() {
        return $this;
    } 

    //sections.php
    public function init()
    {
        if (!post_password_required()) {

            if ( have_rows( 'templates' ) ) {

                global $tpl_args;
                $cols_cb_i = 0;

                while ( have_rows( 'templates' ) ) {

                    the_row();

                    $cb_i = get_row_index();
                    $tpl_args['cb_i'] = $cb_i;
                    $tpl_args['cols_cb_i'] = $cols_cb_i;

                    $layout = get_row_layout();

                    switch ( $layout ) {

                        case 'columns':
                            $this->getTemplatePart( $this->templates_dir . '/columns.php', $tpl_args);
                            $cols_cb_i++;
                            break;

                        case 'related_content':
                            $this->getTemplatePart( $this->templates_dir . '/related-content.php', $tpl_args);
                            break;

                        case 'block_grid':
                            $this->getTemplatePart( $this->templates_dir . '/block-grid.php', $tpl_args);
                            break;

                        case 'image_gallery':
                            $this->getTemplatePart( $this->templates_dir . '/image-gallery.php', $tpl_args);
                            break;
                    }
                }
            }
        }
    }

    //the_columns()
    protected function buildColumns( $context = 'template', $tpl_args )
    {
        
		global $post;
        global $tpl_args;
        
		$cols_cb_i = $tpl_args['cols_cb_i'];
        $page_id = $post->ID;
        $cols = '';
        $alignment = '';

        if ($context == 'hero_unit') {
   
            $cols = get_field($context . '_columns');
            $alignment_array = get_field('hero_unit_column_alignment');
				
            if ($alignment_array['y_alignment'] != 'top') {
                $y_alignment = ' align-' . $alignment_array['y_alignment'];
            }

            $x_alignment = ' align-center';
        
        } elseif ($context == 'template') {

            $cols = get_sub_field($context . '_columns');

            if (get_sub_field('option_y_alignment') != 'top') {
                $y_alignment = ' align-' . get_sub_field('option_y_alignment');
            }

            $x_alignment = ' align-' . get_sub_field('option_x_alignment');
        }

        $count = count($cols);
        $columns_width = get_post_meta($page_id, 'option_columns_width_' . $cols_cb_i, true);
        $width_array = explode('_', $columns_width);
        $pluck = 0;
        $tpl_args['column_count'] = $count;
        $tpl_args['context'] = $context;

        if (have_rows($context . '_columns')) {

            echo '<div class="grid-container">';
            echo '<div class="main grid-x grid-margin-x' . $x_alignment . $y_alignment . ' has-' . $count . '-cols">';
            
            while (have_rows($context . '_columns')) {

                the_row();
                
                if ($context == 'hero_unit') {
                    
                    $width = 12 / $count;

                } elseif ($context == 'template') {
                    
                    if ($columns_width != null) {
                        $width = $width_array[$pluck];
                    } else {
                        $width = 12 / $count;
                    }
                
                }

                $tpl_args['column_width'] = $width;
                
                echo '<div class="cell small-11 medium-' . $width . ' i-' . get_row_index() . '">';
                echo '<div class="inner">';

                $this->getModules( $tpl_args );
                
                echo '</div>';
                echo '</div>';
                $pluck++;
            }
            
            echo '</div>';
            echo '</div>';
        }
    }

    //components.php
    protected function getModules( $tpl_args )    
    {

        global $c_i;
        global $tpl_args;

        $c_i = 1;

        while ( have_rows('modules') ) {

            the_row();

            $tpl_args['module_position'] = $c_i;
            $layout = get_row_layout();
            
            switch ( $layout ) {
                    
                case 'header' : 
                    $this->getTemplatePart( $this->modules_dir . '/related-content.php', $tpl_args);
                    break;
                case 'hero_header' :
                    $this->getTemplatePart( $this->modules_dir . '/hero-header.php', $tpl_args);
                    break;
                case 'html_editor' :
                    $this->getTemplatePart( $this->modules_dir . '/html-editor.php', $tpl_args);
                    break;
                case 'image' :
                    $this->getTemplatePart( $this->modules_dir . '/image.php', $tpl_args);
                    break;
                case 'video' :
                    $this->getTemplatePart( $this->modules_dir . '/video.php', $tpl_args);
                    break;
                case 'form' :
                    $this->getTemplatePart( $this->modules_dir . '/form.php', $tpl_args);
                    break;
                case 'button' :
                    $this->getTemplatePart( $this->modules_dir . '/button.php', $tpl_args);
                    break;
                case 'business_information' :
                    $this->getTemplatePart( $this->modules_dir . '/business-information.php', $tpl_args);
                    break;
            }

            $c_i++;
        }

    }

    // hm_get_template_part()
    protected function getTemplatePart( $file, $template_args = array(), $cache_args = array() )
    {

        $template_args = wp_parse_args($template_args);
        $cache_args = wp_parse_args($cache_args);

        if ( $cache_args ) {
            
            foreach ( $template_args as $key => $value ) {

                if ( is_scalar($value) || is_array($value) ) {
                    $cache_args[$key] = $value;
                } elseif ( is_object($value) && method_exists($value, 'get_id' ) ) {
                    $cache_args[$key] = call_user_func( 'get_id', $value );
                }

            }

            if ( ( $cache = wp_cache_get( $file, serialize( $cache_args ) ) ) !== false ) {

                if ( !empty( $template_args['return'] ) ) {
                    return $cache;
                }

                echo $cache;
                return;
            }

        }

        $file_handle = $file;

        if (file_exists($this->templates_dir . '/' . $file . '.php')) {
            $file = $this->templates_dir . '/' . $file . '.php';
        }

        $return = require $file;

        if ($cache_args) {
            wp_cache_set($file, $data, serialize($cache_args), 3600);
        }

        if (!empty($template_args['return'])) {

            if ($return === false) {
                return false;
            } else {
                return $data;
            }
        }

        return $data;
    
    }

    //get_inline_styles()
    protected function getInlineStyles( $context = 'template' )
    {
        $image = '';

        if ( $context == 'hero_unit' ) {
            
            if (get_field('background_options') == 'Image' && get_field('background_image') != null) {
                $image = get_field('background_image');
                $style = ' style="background-image: url(' . $image['url'] . ')"';
            }

        } else {

            if (get_sub_field('option_background_options') == 'Image' && get_sub_field('option_background_image') != null) {
                $image = get_sub_field('option_background_image');
                $style = ' style="background-image: url(' . $image['url'] . ')"';
            }

        }

        return $style;

    }
    
    //section_id_classes()
    protected function getTemplateClasses( $classes = '' )
    {

        $inline_classes = get_sub_field('option_html_classes');
        $template_id_classes = '';

        if ( $html_id = get_sub_field('option_html_id') ) {
          $html_id = sanitize_html_class(strtolower($html_id));
          $template_id_classes .= ' id="' . $html_id . '" class="content-block';
        } else {
          $template_id_classes .= ' class="content-block';
        }

        if ( get_sub_field('option_background_options') == 'Color' ) {
          $template_id_classes .= ' ' . sanitize_html_class( get_sub_field('option_background_color') );
        }

        if ( get_sub_field('option_background_options') == 'Image' ) {
          $template_id_classes .= ' bg-image bg-dark';
        }

        if ( get_sub_field('option_background_options') == 'Video' ) {
          $template_id_classes .= ' bg-video bg-dark';
        }

        if ( $classes != NULL ) {
          $classes = SSMH::sanitizeHtmlClasses($classes);
          $template_id_classes .= ' ' . $classes;
        }

        if ( $inline_classes != NULL ) {
          $template_id_classes .= ' ' . $inline_classes;
        }

        $template_id_classes .= '"';

        return $template_id_classes;

    }

    //the_section_header()
    protected function getTemplateHeader()
    {

        global $tpl_args;
        
        $include_header = get_sub_field('option_include_template_header');
        $headline = get_sub_field('option_template_headline');
        $subheadline = get_sub_field('option_template_subheadline');
        $headline_tag_open = '<h2 class="headline">';
        $headline_tag_close = '</h2>';
        $subheadline_tag_open = '<h3 class="subheadline">';
        $subheadline_tag_close = '</h3>';

        if ( $include_header == TRUE ) {

          $html = '<div class="grid-container">';
          $html .= '<div class="grid-x grid-x-margin align-center">';
              $html .= '<div class="cell small-12 medium-10">';
                $html .= '<header class="component template-header align-center">';

                  if ( $headline ) {
                    $html .= $headline_tag_open . $headline . $headline_tag_close;
                  }

                  if ( $subheadline ) {
                    $html .= $subheadline_tag_open . $subheadline . $subheadline_tag_close;
                  }

                $html .= '</header>';
              $html .= '</div>';
            $html .= '</div>';
          $html .= '</div>';
          
          return $html;
        
        }
      
    }

    // the_video_background()
    protected function getVideoBackground( $context = 'template' )
    {
	
        $html = '';

        if ( $context == 'hero_unit' ) {

            if ( get_field('background_options') == 'Video' && get_field('background_video') != null ) {
                $video = get_field('background_video');
                $html = '<div class="hero-video">';
                $html .= '<video autoplay loop>';
                $html .= '<source src="' . $video['url'] . '" type="video/mp4">';
                $html .= '</video>';
                $html .= '</div>';
                $html .= '<div class="overlay"></div>';
            }

        } else {

            if ( get_sub_field('option_background_options') == 'Video' && get_sub_field('option_background_video') != null ) {
                $video = get_sub_field('option_background_video');
                $html = '<div class="hero-video">';
                $html .= '<video autoplay loop>';
                $html .= '<source src="' . $video['url'] . '" type="video/mp4">';
                $html .= '</video>';
                $html .= '</div>';
                $html .= '<div class="overlay"></div>';
            }
        
        }

        if ( get_field('background_options') == 'Video' && get_field('background_video') != null || get_sub_field('option_background_options') == 'Video' && get_sub_field('option_background_video') != null ) {
            
        }

        return $html;
    
    }

    //component_id_classes()
    protected function getModuleClasses( $c_classes = '' )
    {

        global $c_i;

        $even_odd = 0 == $c_i % 2 ? 'even' : 'odd';
        $inline_classes = get_sub_field('option_html_classes');
        $module_id_classes = '';

        if ( $html_id = get_sub_field('option_html_id') ) {
            $html_id = sanitize_html_class(strtolower($html_id));
            $module_id_classes .= ' id="' . $html_id . '" class="module stack-order-' . $c_i . ' stack-order-' . $even_odd;
        } else {
            $module_id_classes .= ' class="module stack-order-' . $c_i . ' stack-order-' . $even_odd;
        }

        if ( $alignment = get_sub_field('option_alignment') ) {
            $module_id_classes .= ' ' . sanitize_html_class( $alignment );
        }
        if ( $c_classes != NULL ) {
            $c_classes = SSMH::sanitizeHtmlClasses($c_classes);
            $module_id_classes .= ' ' . $c_classes;
        }
        if ( $inline_classes != NULL ) {
            $module_id_classes .= ' ' . $inline_classes;
        }

        $module_id_classes .= '"';

        return $module_id_classes;

    }

    // hero_unit_id_classes()
    protected function getHeroUnitClasses( $h_classes = '' )
    {

        $inline_classes = get_field('html_classes');
        $hero_unit_id_classes = '';

        if ( $html_id = get_field('html_id') ) {
            $html_id = sanitize_html_class(strtolower($html_id));
            $hero_unit_id_classes .= ' id="' . $html_id . '" class="hero-unit';
        } else {
            $hero_unit_id_classes .= ' class="hero-unit';
        }

        if ( get_field('background_options') == 'Color' ) {
            $hero_unit_id_classes .= ' ' . sanitize_html_class( get_field('background_color') );
        }

        if ( get_field('background_options') == 'Image' ) {
            $hero_unit_id_classes .= ' bg-image bg-dark';
        }

        if ( get_field('background_options') == 'Video' ) {
            $hero_unit_id_classes .= ' bg-video';
        }

        if ( get_field('hero_unit_height') == 'full' ) {
            $hero_unit_id_classes .= ' full-height';
        } elseif ( get_field('hero_unit_height') == 'auto' ) {
            $hero_unit_id_classes .= ' auto';
        }
        
        if ( $h_classes != NULL ) {
            $h_classes = SSMH::sanitize_html_classes($s_classes);
            $hero_unit_id_classes .= ' ' . $h_classes;
        }

        if ( $inline_classes != NULL ) {
            $hero_unit_id_classes .= ' ' . $inline_classes;
        }

        $hero_unit_id_classes .= '"';

        return $hero_unit_id_classes;

    }

}
