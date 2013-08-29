<?php

// needs to be seperated into files
class HTMLPurifier_TagTransformTest extends HTMLPurifier_Harness
{

    /**
     * Asserts that a transformation happens
     *
     * This assertion performs several tests on the transform:
     *
     * -# Transforms a start tag with only $name and no attributes
     * -# Transforms a start tag with $name and $attributes
     * -# Transform an end tag
     * -# Transform an empty tag with only $name and no attributes
     * -# Transform an empty tag with $name and $attributes
     *
     * In its current form, it assumes that start and empty tags would be
     * treated the same, and is really ensuring that the tag transform doesn't
     * do anything wonky to the tag type.
     *
     * @param $transformer      HTMLPurifier_TagTransform class to test
     * @param $name             Name of the original tag
     * @param $attributes       Attributes of the original tag
     * @param $expect_name      Name of output tag
     * @param $expect_attributes Attributes of output tag when $attributes
     *                          is included.
     * @param $expect_added_attributes Attributes of output tag when $attributes
     *                          are omitted.
     * @param $config_array     Configuration array for HTMLPurifier_Config
     * @param $context_array    Context array for HTMLPurifier_Context
     */
    protected function assertTransformation($transformer,
                                         $name,        $attributes,
                                  $expect_name, $expect_attributes,
                                  $expect_added_attributes = array(),
                                  $config_array = array(), $context_array = array()) {

        $config = HTMLPurifier_Config::createDefault();
        $config->loadArray($config_array);

        $context = new HTMLPurifier_Context();
        $context->loadArray($context_array);

        // start tag transform
        $this->assertIdentical(
                new HTMLPurifier_Token_Start($expect_name, $expect_added_attributes),
                $transformer->transform(
                    new HTMLPurifier_Token_Start($name), $config, $context)
            );

        // start tag transform with attributes
        $this->assertIdentical(
                new HTMLPurifier_Token_Start($expect_name, $expect_attributes),
                $transformer->transform(
                    new HTMLPurifier_Token_Start($name, $attributes),
                    $config, $context
                )
            );

        // end tag transform
        $this->assertIdentical(
                new HTMLPurifier_Token_End($expect_name),
                $transformer->transform(
                    new HTMLPurifier_Token_End($name), $config, $context
                )
            );

        // empty tag transform
        $this->assertIdentical(
                new HTMLPurifier_Token_Empty($expect_name, $expect_added_attributes),
                $transformer->transform(
                    new HTMLPurifier_Token_Empty($name), $config, $context
                )
            );

        // empty tag transform with attributes
        $this->assertIdentical(
                new HTMLPurifier_Token_Empty($expect_name, $expect_attributes),
                $transformer->transform(
                    new HTMLPurifier_Token_Empty($name, $attributes),
                    $config, $context
                )
            );


    }

    function testSimple() {

        $transformer = new HTMLPurifier_TagTransform_Simple('ul');

        $this->assertTransformation(
            $transformer,
            'menu', array('class' => 'boom'),
            'ul', array('class' => 'boom')
        );

    }

    function testSimpleWithCSS() {

        $transformer = new HTMLPurifier_TagTransform_Simple('div', 'text-align:center;');

        $this->assertTransformation(
            $transformer,
            'center', array('class' => 'boom', 'style'=>'font-weight:bold;'),
            'div', array('class' => 'boom', 'style'=>'text-align:center;font-weight:bold;'),
            array('style'=>'text-align:center;')
        );

        // test special case, uppercase attribute key
        $this->assertTransformation(
            $transformer,
            'center', array('STYLE'=>'font-weight:bold;'),
            'div', array('style'=>'text-align:center;font-weight:bold;'),
            array('style'=>'text-align:center;')
        );

    }

    protected function assertSizeToStyle($transformer, $size, $style) {
        $this->assertTransformation(
            $transformer,
            'font', array('size' => $size),
            'span', array('style' => 'font-size:' . $style . ';')
        );
    }

    function testFont() {

        $transformer = new HTMLPurifier_TagTransform_Font();

        // test a font-face transformation
        $this->assertTransformation(
            $transformer,
            'font', array('face' => 'Arial'),
            'span', array('style' => 'font-family:Arial;')
        );

        // test a color transformation
        $this->assertTransformation(
            $transformer,
            'font', array('color' => 'red'),
            'span', array('style' => 'color:red;')
        );

        // test the size transforms
        $this->assertSizeToStyle($transformer, '0', 'xx-small');
        $this->assertSizeToStyle($transformer, '1', 'xx-small');
        $this->assertSizeToStyle($transformer, '2', 'small');
        $this->assertSizeToStyle($transformer, '3', 'medium');
        $this->assertSizeToStyle($transformer, '4', 'large');
        $this->assertSizeToStyle($transformer, '5', 'x-large');
        $this->assertSizeToStyle($transformer, '6', 'xx-large');
        $this->assertSizeToStyle($transformer, '7', '300%');
        $this->assertSizeToStyle($transformer, '-1', 'smaller');
        $this->assertSizeToStyle($transformer, '-2', '60%');
        $this->assertSizeToStyle($transformer, '-3', '60%');
        $this->assertSizeToStyle($transformer, '+1', 'larger');
        $this->assertSizeToStyle($transformer, '+2', '150%');
        $this->assertSizeToStyle($transformer, '+3', '200%');
        $this->assertSizeToStyle($transformer, '+4', '300%');
        $this->assertSizeToStyle($transformer, '+5', '300%');
        $this->assertTransformation(
            $transformer, 'font', array('size' => ''),
            'span', array()
        );

        // test multiple transforms, the alphabetical ordering is important
        $this->assertTransformation(
            $transformer,
            'font', array('color' => 'red', 'face' => 'Arial', 'size' => '6'),
            'span', array('style' => 'color:red;font-family:Arial;font-size:xx-large;')
        );
    }
}

// vim: et sw=4 sts=4
