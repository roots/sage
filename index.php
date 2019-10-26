<?php

use function Roots\app;
use function Roots\view;

/** Loads the template hierarchy view file */
echo view(app('sage.view'), app('sage.data'))->render();
