<?php

// Component files need to live inside 'components/cmp-name/cmp-name'
// And they need to look like <?php return function($arg1, $arg2=null, $arg3=null) {print($arg1)} ?\> (sry the escape needed to be there)
function getComponent() {

    $args = func_get_args();
    $componentName = $args[0];

    $args = array_slice($args, 1);

    return '<div class="'.$componentName.'">'.call_user_func_array(include(locate_template('components/'.$componentName.'/'.$componentName.'.php')), $args).'</div';
}

// This lets you pass a template name and save it to a variable, need to have 'templates/tmpl-name' like normal
function load_template_part($tmplName) {

    return file_get_contents( get_stylesheet_directory() . '/' . $tmplName . '.php');;
}
