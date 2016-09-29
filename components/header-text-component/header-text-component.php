<?php

    extract(initComponent($args, ['titleText', 'subTitleText']));

?>

<div>
    <div class="header-text">
        <h1 class="title"><?= $titleText ?></h1>
        <h2 class="subTitle"><?= $subTitleText ?></h2>
    </div>
</div>
