<?php

class HTMLPurifier_UnitConverterTest extends HTMLPurifier_Harness
{

    protected function assertConversion($input, $expect, $unit = null, $test_negative = true) {
        $length = HTMLPurifier_Length::make($input);
        if ($expect !== false) $expectl = HTMLPurifier_Length::make($expect);
        else $expectl = false;
        $to_unit = $unit !== null ? $unit : $expectl->getUnit();

        $converter = new HTMLPurifier_UnitConverter(4, 10);
        $result = $converter->convert($length, $to_unit);
        if (!$result || !$expectl) $this->assertIdentical($result, $expectl);
        else $this->assertIdentical($result->toString(), $expectl->toString());

        $converter = new HTMLPurifier_UnitConverter(4, 10, true);
        $result = $converter->convert($length, $to_unit);
        if (!$result || !$expectl) $this->assertIdentical($result, $expectl);
        else $this->assertIdentical($result->toString(), $expectl->toString(), 'BCMath substitute: %s');

        if ($test_negative) {
            $this->assertConversion(
                "-$input",
                $expect === false ? false : "-$expect",
                $unit,
                false
            );
        }
    }

    function testFail() {
        $this->assertConversion('1in', false, 'foo');
        $this->assertConversion('1foo', false, 'in');
    }

    function testZero() {
        $this->assertConversion('0', '0', 'in', false);
        $this->assertConversion('-0', '0', 'in', false);
        $this->assertConversion('0in', '0', 'in', false);
        $this->assertConversion('-0in', '0', 'in', false);
        $this->assertConversion('0in', '0', 'pt', false);
        $this->assertConversion('-0in', '0', 'pt', false);
    }

    function testEnglish() {
        $this->assertConversion('1in', '6pc');
        $this->assertConversion('6pc', '1in');

        $this->assertConversion('1in', '72pt');
        $this->assertConversion('72pt', '1in');

        $this->assertConversion('1pc', '12pt');
        $this->assertConversion('12pt', '1pc');

        $this->assertConversion('1pt', '0.01389in');
        $this->assertConversion('1.000pt', '0.01389in');
        $this->assertConversion('100000pt', '1389in');

        $this->assertConversion('1in', '96px');
        $this->assertConversion('96px', '1in');
    }

    function testMetric() {
        $this->assertConversion('1cm', '10mm');
        $this->assertConversion('10mm', '1cm');
        $this->assertConversion('1mm', '0.1cm');
        $this->assertConversion('100mm', '10cm');
    }

    function testEnglishMetric() {
        $this->assertConversion('2.835pt', '1mm');
        $this->assertConversion('1mm', '2.835pt');
        $this->assertConversion('0.3937in', '1cm');
    }

    function testRoundingMinPrecision() {
        // One sig-fig, modified to be four, conversion rounds up
        $this->assertConversion('100pt', '1.389in');
        $this->assertConversion('1000pt', '13.89in');
        $this->assertConversion('10000pt', '138.9in');
        $this->assertConversion('100000pt', '1389in');
        $this->assertConversion('1000000pt', '13890in');
    }

    function testRoundingUserPrecision() {
        // Five sig-figs, conversion rounds down
        $this->assertConversion('11112000pt', '154330in');
        $this->assertConversion('1111200pt', '15433in');
        $this->assertConversion('111120pt', '1543.3in');
        $this->assertConversion('11112pt', '154.33in');
        $this->assertConversion('1111.2pt', '15.433in');
        $this->assertConversion('111.12pt', '1.5433in');
        $this->assertConversion('11.112pt', '0.15433in');
    }

    function testRoundingBigNumber() {
        $this->assertConversion('444400000000000000000000in', '42660000000000000000000000px');
    }

    protected function assertSigFig($n, $sigfigs) {
        $converter = new HTMLPurifier_UnitConverter();
        $result = $converter->getSigFigs($n);
        $this->assertIdentical($result, $sigfigs);
    }

    function test_getSigFigs() {
        $this->assertSigFig('0', 0);
        $this->assertSigFig('1', 1);
        $this->assertSigFig('-1', 1);
        $this->assertSigFig('+1', 1);
        $this->assertSigFig('01', 1);
        $this->assertSigFig('001', 1);
        $this->assertSigFig('12', 2);
        $this->assertSigFig('012', 2);
        $this->assertSigFig('10', 1);
        $this->assertSigFig('10.', 2);
        $this->assertSigFig('100.', 3);
        $this->assertSigFig('103', 3);
        $this->assertSigFig('130', 2);
        $this->assertSigFig('.1', 1);
        $this->assertSigFig('0.1', 1);
        $this->assertSigFig('00.1', 1);
        $this->assertSigFig('0.01', 1);
        $this->assertSigFig('0.010', 2);
        $this->assertSigFig('0.012', 2);
    }

}

// vim: et sw=4 sts=4
