<?php

class HTMLPurifier_LengthTest extends HTMLPurifier_Harness
{

    function testConstruct() {
        $l = new HTMLPurifier_Length('23', 'in');
        $this->assertIdentical($l->getN(), '23');
        $this->assertIdentical($l->getUnit(), 'in');
    }

    function testMake() {
        $l = HTMLPurifier_Length::make('+23.4in');
        $this->assertIdentical($l->getN(), '+23.4');
        $this->assertIdentical($l->getUnit(), 'in');
    }

    function testToString() {
        $l = new HTMLPurifier_Length('23', 'in');
        $this->assertIdentical($l->toString(), '23in');
    }

    protected function assertValidate($string, $expect = true) {
        if ($expect === true) $expect = $string;
        $l = HTMLPurifier_Length::make($string);
        $result = $l->isValid();
        if ($result === false) $this->assertIdentical($expect, false);
        else $this->assertIdentical($l->toString(), $expect);
    }

    function testValidate() {
        $this->assertValidate('0');
        $this->assertValidate('+0', '0');
        $this->assertValidate('-0', '0');
        $this->assertValidate('0px');
        $this->assertValidate('4.5px');
        $this->assertValidate('-4.5px');
        $this->assertValidate('3ex');
        $this->assertValidate('3em');
        $this->assertValidate('3in');
        $this->assertValidate('3cm');
        $this->assertValidate('3mm');
        $this->assertValidate('3pt');
        $this->assertValidate('3pc');
        $this->assertValidate('3PX', '3px');
        $this->assertValidate('3', false);
        $this->assertValidate('3miles', false);
    }

    /**
     * @param $s1 First string to compare
     * @param $s2 Second string to compare
     * @param $expect 0 for $s1 == $s2, 1 for $s1 > $s2 and -1 for $s1 < $s2
     */
    protected function assertComparison($s1, $s2, $expect = 0) {
        $l1 = HTMLPurifier_Length::make($s1);
        $l2 = HTMLPurifier_Length::make($s2);
        $r1 = $l1->compareTo($l2);
        $r2 = $l2->compareTo($l1);
        $this->assertIdentical($r1 == 0 ? 0 : ($r1 > 0 ? 1 : -1), $expect);
        $this->assertIdentical($r2 == 0 ? 0 : ($r2 > 0 ? 1 : -1), - $expect);
    }

    function testCompareTo() {
        $this->assertComparison('12in', '12in');
        $this->assertComparison('12in', '12mm', 1);
        $this->assertComparison('1px', '1mm', -1);
        $this->assertComparison(str_repeat('2', 38) . 'in', '100px', 1);
    }

}

// vim: et sw=4 sts=4
