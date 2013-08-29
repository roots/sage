<?php

class HTMLPurifier_DefinitionCacheTest extends HTMLPurifier_Harness
{

    function test_isOld() {
        // using null subclass because parent is abstract
        $cache = new HTMLPurifier_DefinitionCache_Null('Test');

        generate_mock_once('HTMLPurifier_Config');
        $config = new HTMLPurifier_ConfigMock();
        $config->version = '1.0.0'; // hopefully no conflicts
        $config->setReturnValue('get', 10, array('Test.DefinitionRev'));
        $config->setReturnValue('getBatchSerial', 'hash', array('Test'));

        $this->assertIdentical($cache->isOld('1.0.0,hash,10', $config), false);
        $this->assertIdentical($cache->isOld('1.5.0,hash,1', $config), true);

        $this->assertIdentical($cache->isOld('0.9.0,hash,1', $config), true);
        $this->assertIdentical($cache->isOld('1.0.0,hash,1', $config), true);
        $this->assertIdentical($cache->isOld('1.0.0beta,hash,11', $config), true);

        $this->assertIdentical($cache->isOld('0.9.0,hash2,1', $config), true);
        $this->assertIdentical($cache->isOld('1.0.0,hash2,1', $config), false); // if hash is different, don't touch!
        $this->assertIdentical($cache->isOld('1.0.0beta,hash2,11', $config), true);
        $this->assertIdentical($cache->isOld('1.0.0-dev,hash2,11', $config), true);

    }

}

// vim: et sw=4 sts=4
