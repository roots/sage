<?php

collect(glob(__DIR__ . '/includes/*.php'))->map(function ($file_path) {
    return require_once $file_path;
});
