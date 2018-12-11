<?php

/**
 * DEMO FILE
 */

namespace App\AjaxHandler;

use Triggerfish\REST_Ajax\AbstractAjaxHandler;

class DemoAction extends AbstractAjaxHandler
{
    public function __template() : string
    {
        return 'partials/list/non-existing-demo-file';
    }

    public function __getData()
    {
        return ['class' => $this->params];
    }
}
