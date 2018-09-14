<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class FrontPage extends Controller
{
    const TEST_VAR = "test_var";
    public static $test_var = 'test_var2';

    public function test_image()
    {
        return 'test_image';
    }

    public function link_target() {
        return get_field('link_target'); 
    }

    public function html_classes() {
        return get_field('html_attributes');
    }

    public function button() {
        return get_field('button');
    }

}
