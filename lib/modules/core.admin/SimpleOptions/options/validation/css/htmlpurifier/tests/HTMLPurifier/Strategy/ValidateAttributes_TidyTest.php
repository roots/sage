<?php

class HTMLPurifier_Strategy_ValidateAttributes_TidyTest extends HTMLPurifier_StrategyHarness
{

    function setUp() {
        parent::setUp();
        $this->obj = new HTMLPurifier_Strategy_ValidateAttributes();
        $this->config->set('HTML.TidyLevel', 'heavy');
    }

    function testConvertCenterAlign() {
        $this->assertResult(
            '<h1 align="center">Centered Headline</h1>',
            '<h1 style="text-align:center;">Centered Headline</h1>'
        );
    }

    function testConvertRightAlign() {
        $this->assertResult(
            '<h1 align="right">Right-aligned Headline</h1>',
            '<h1 style="text-align:right;">Right-aligned Headline</h1>'
        );
    }

    function testConvertLeftAlign() {
        $this->assertResult(
            '<h1 align="left">Left-aligned Headline</h1>',
            '<h1 style="text-align:left;">Left-aligned Headline</h1>'
        );
    }

    function testConvertJustifyAlign() {
        $this->assertResult(
            '<p align="justify">Justified Paragraph</p>',
            '<p style="text-align:justify;">Justified Paragraph</p>'
        );
    }

    function testRemoveInvalidAlign() {
        $this->assertResult(
            '<h1 align="invalid">Invalid Headline</h1>',
            '<h1>Invalid Headline</h1>'
        );
    }

    function testConvertTableLengths() {
        $this->assertResult(
            '<td width="5%" height="10" /><th width="10" height="5%" /><hr width="10" height="10" />',
            '<td style="width:5%;height:10px;" /><th style="width:10px;height:5%;" /><hr style="width:10px;" />'
        );
    }

    function testTdConvertNowrap() {
        $this->assertResult(
            '<td nowrap />',
            '<td style="white-space:nowrap;" />'
        );
    }

    function testCaptionConvertAlignLeft() {
        $this->assertResult(
            '<caption align="left" />',
            '<caption style="text-align:left;" />'
        );
    }

    function testCaptionConvertAlignRight() {
        $this->assertResult(
            '<caption align="right" />',
            '<caption style="text-align:right;" />'
        );
    }

    function testCaptionConvertAlignTop() {
        $this->assertResult(
            '<caption align="top" />',
            '<caption style="caption-side:top;" />'
        );
    }

    function testCaptionConvertAlignBottom() {
        $this->assertResult(
            '<caption align="bottom" />',
            '<caption style="caption-side:bottom;" />'
        );
    }

    function testCaptionRemoveInvalidAlign() {
        $this->assertResult(
            '<caption align="nonsense" />',
            '<caption />'
        );
    }

    function testTableConvertAlignLeft() {
        $this->assertResult(
            '<table align="left" />',
            '<table style="float:left;" />'
        );
    }

    function testTableConvertAlignCenter() {
        $this->assertResult(
            '<table align="center" />',
            '<table style="margin-left:auto;margin-right:auto;" />'
        );
    }

    function testTableConvertAlignRight() {
        $this->assertResult(
            '<table align="right" />',
            '<table style="float:right;" />'
        );
    }

    function testTableRemoveInvalidAlign() {
        $this->assertResult(
            '<table align="top" />',
            '<table />'
        );
    }

    function testImgConvertAlignLeft() {
        $this->assertResult(
            '<img src="foobar.jpg" alt="foobar" align="left" />',
            '<img src="foobar.jpg" alt="foobar" style="float:left;" />'
        );
    }

    function testImgConvertAlignRight() {
        $this->assertResult(
            '<img src="foobar.jpg" alt="foobar" align="right" />',
            '<img src="foobar.jpg" alt="foobar" style="float:right;" />'
        );
    }

    function testImgConvertAlignBottom() {
        $this->assertResult(
            '<img src="foobar.jpg" alt="foobar" align="bottom" />',
            '<img src="foobar.jpg" alt="foobar" style="vertical-align:baseline;" />'
        );
    }

    function testImgConvertAlignMiddle() {
        $this->assertResult(
            '<img src="foobar.jpg" alt="foobar" align="middle" />',
            '<img src="foobar.jpg" alt="foobar" style="vertical-align:middle;" />'
        );
    }

    function testImgConvertAlignTop() {
        $this->assertResult(
            '<img src="foobar.jpg" alt="foobar" align="top" />',
            '<img src="foobar.jpg" alt="foobar" style="vertical-align:top;" />'
        );
    }

    function testImgRemoveInvalidAlign() {
        $this->assertResult(
            '<img src="foobar.jpg" alt="foobar" align="outerspace" />',
            '<img src="foobar.jpg" alt="foobar" />'
        );
    }

    function testBorderConvertHVSpace() {
        $this->assertResult(
            '<img src="foo" alt="foo" hspace="1" vspace="3" />',
            '<img src="foo" alt="foo" style="margin-top:3px;margin-bottom:3px;margin-left:1px;margin-right:1px;" />'
        );
    }

    function testHrConvertSize() {
        $this->assertResult(
            '<hr size="3" />',
            '<hr style="height:3px;" />'
        );
    }

    function testHrConvertNoshade() {
        $this->assertResult(
            '<hr noshade />',
            '<hr style="color:#808080;background-color:#808080;border:0;" />'
        );
    }

    function testHrConvertAlignLeft() {
        $this->assertResult(
            '<hr align="left" />',
            '<hr style="margin-left:0;margin-right:auto;text-align:left;" />'
        );
    }

    function testHrConvertAlignCenter() {
        $this->assertResult(
            '<hr align="center" />',
            '<hr style="margin-left:auto;margin-right:auto;text-align:center;" />'
        );
    }

    function testHrConvertAlignRight() {
        $this->assertResult(
            '<hr align="right" />',
            '<hr style="margin-left:auto;margin-right:0;text-align:right;" />'
        );
    }

    function testHrRemoveInvalidAlign() {
        $this->assertResult(
            '<hr align="bottom" />',
            '<hr />'
        );
    }

    function testBrConvertClearLeft() {
        $this->assertResult(
            '<br clear="left" />',
            '<br style="clear:left;" />'
        );
    }

    function testBrConvertClearRight() {
        $this->assertResult(
            '<br clear="right" />',
            '<br style="clear:right;" />'
        );
    }

    function testBrConvertClearAll() {
        $this->assertResult(
            '<br clear="all" />',
            '<br style="clear:both;" />'
        );
    }

    function testBrConvertClearNone() {
        $this->assertResult(
            '<br clear="none" />',
            '<br style="clear:none;" />'
        );
    }

    function testBrRemoveInvalidClear() {
        $this->assertResult(
            '<br clear="foo" />',
            '<br />'
        );
    }

    function testUlConvertTypeDisc() {
        $this->assertResult(
            '<ul type="disc" />',
            '<ul style="list-style-type:disc;" />'
        );
    }

    function testUlConvertTypeSquare() {
        $this->assertResult(
            '<ul type="square" />',
            '<ul style="list-style-type:square;" />'
        );
    }

    function testUlConvertTypeCircle() {
        $this->assertResult(
            '<ul type="circle" />',
            '<ul style="list-style-type:circle;" />'
        );
    }

    function testUlConvertTypeCaseInsensitive() {
        $this->assertResult(
            '<ul type="CIRCLE" />',
            '<ul style="list-style-type:circle;" />'
        );
    }

    function testUlRemoveInvalidType() {
        $this->assertResult(
            '<ul type="a" />',
            '<ul />'
        );
    }

    function testOlConvertType1() {
        $this->assertResult(
            '<ol type="1" />',
            '<ol style="list-style-type:decimal;" />'
        );
    }

    function testOlConvertTypeLowerI() {
        $this->assertResult(
            '<ol type="i" />',
            '<ol style="list-style-type:lower-roman;" />'
        );
    }

    function testOlConvertTypeUpperI() {
        $this->assertResult(
            '<ol type="I" />',
            '<ol style="list-style-type:upper-roman;" />'
        );
    }

    function testOlConvertTypeLowerA() {
        $this->assertResult(
            '<ol type="a" />',
            '<ol style="list-style-type:lower-alpha;" />'
        );
    }

    function testOlConvertTypeUpperA() {
        $this->assertResult(
            '<ol type="A" />',
            '<ol style="list-style-type:upper-alpha;" />'
        );
    }

    function testOlRemoveInvalidType() {
        $this->assertResult(
            '<ol type="disc" />',
            '<ol />'
        );
    }

    function testLiConvertTypeCircle() {
        $this->assertResult(
            '<li type="circle" />',
            '<li style="list-style-type:circle;" />'
        );
    }

    function testLiConvertTypeA() {
        $this->assertResult(
            '<li type="A" />',
            '<li style="list-style-type:upper-alpha;" />'
        );
    }

    function testLiConvertTypeCaseSensitive() {
        $this->assertResult(
            '<li type="CIRCLE" />',
            '<li />'
        );
    }


}

// vim: et sw=4 sts=4
