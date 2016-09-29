<?php

// Component files need to live inside 'components/cmp-name/cmp-name'

// Checkout the 2 examples in the components folder.

/* How to get a component

    <div>

        echo getComponent(
            'page-header',
            $title,
            $subTitle,
            $arg3
        );


        // You can save components into variables!!
        $pageHeader = getComponent(
            'page-header',
            $title,
            $subTitle,
            $arg3
        );

        echo $pageHeader;

    </div>
*/


// Gets called by components to declare local vars and set default values
function initComponent($args, $vars) {

    $localVarDefs = [];

    $varsLen = count($vars);
    // Passed in from calling function
    $argsLen = count($args);

    for ($i = 0; $i < $varsLen; $i++) {

        // Not enough args passed in, so make everything else default
        if($i >= $argsLen) {

            $localVarDefs[$vars[$i][0]] = $vars[$i][1];
            continue;
        }

        $default = null;
        $varName = null;

        if(is_array($vars[$i])) {
            $varName = $vars[$i][0];
            $default = $vars[$i][1];
        } else {
            $varName = $vars[$i];
        }

        $localVarDefs[$varName] = $args[$i];
    }

    return $localVarDefs;
}


function getComponent() {

    $args = func_get_args();
    $componentName = $args[0];

    $args = array_slice($args, 1);

    ob_start();
    include(locate_template('components/'.$componentName.'/'.$componentName.'.php'));
    $component = ob_get_clean();

    return '<div class="'.$componentName.'">'.$component.'</div>';
}


// This lets you pass a template name and save it to a variable, need to have 'templates/tmpl-name' like normal
function getTemplate($tmplName) {

    return file_get_contents( get_stylesheet_directory() . '/' . $tmplName . '.php');;
}


