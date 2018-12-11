<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class FourZeroFour extends Controller
{
    protected $template = '404';

    public function content()
    {
        return get_field('settings_404_content', 'option') ?? '';
    }
}
