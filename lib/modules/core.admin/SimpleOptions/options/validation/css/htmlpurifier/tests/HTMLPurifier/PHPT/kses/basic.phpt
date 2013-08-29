--TEST--
HTMLPurifier.kses.php basic test
--FILE--
<?php
require '../library/HTMLPurifier.kses.php';
echo kses(
    '<a class="foo" style="color:#F00;" href="https://google.com">Foo<i>Bar</i>',
    array(
        'a' => array('class' => 1, 'href' => 1),
    ),
    array('http') // no https!
);

--EXPECT--
<a class="foo">FooBar</a>