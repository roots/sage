<?php

/**
 * Loads the template hierarchy view file.
 */

use function Roots\app;
use function Roots\view;

echo view(app('sage.view'), app('sage.data'))->render();
