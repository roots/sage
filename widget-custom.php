<?php

$context['widget'] = new \TimberExtended\Widget($acfw);
$context['widget']->init(get_defined_vars());

Timber::render(['widgets/widget--' . $context['widget']->widget_id, 'widgets/widget.twig'], $context);
