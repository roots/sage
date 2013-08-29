<?php

// since Mocks can't be called from within test files, we need to do
// a little jumping through hoops to generate them
function generate_mock_once($name) {
    $mock_name = $name . 'Mock';
    if (class_exists($mock_name, false)) return false;
    Mock::generate($name, $mock_name);
}

// vim: et sw=4 sts=4
