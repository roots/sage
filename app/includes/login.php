<?php

namespace App;

add_action('login_head', function () {
    wp_enqueue_style('sage/login.css', asset_path('styles/login.css'));
});

add_filter('login_headerurl', function () {
    return home_url('/');
});

// add_filter('the_password_form', function ($form = '') {
//     $label = 'pwbox-' . get_the_ID() ?: mt_rand();

//     return template('partials/password-form', ['label' => $label]);
// });
