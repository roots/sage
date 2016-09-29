<?php

    extract(initComponent($args, ['title', ['subTitle', null], ['children', null]]));

    $var2 = '=D';

    $headerText = getComponent(
        'header-text-component',
        'Title text!',
        $var2
    );

    if($children !== null) {
        $children = '<div class="children">'.$children.'</div>';
    }

?>

<div>
    <div class="header-content">
        <?=
            $headerText
            $children
        ?>
    </div>
</div>
