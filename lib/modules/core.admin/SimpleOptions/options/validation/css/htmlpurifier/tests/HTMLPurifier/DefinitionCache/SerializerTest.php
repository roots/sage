<?php

class HTMLPurifier_DefinitionCache_SerializerTest extends HTMLPurifier_DefinitionCacheHarness
{

    function test() {
        // XXX SimpleTest does some really crazy stuff in the background
        // to do equality checks. Unfortunately, this makes some
        // versions of PHP segfault. So we need to define a better,
        // homebrew notion of equality and use that instead.  For now,
        // the identical asserts are commented out.

        $cache = new HTMLPurifier_DefinitionCache_Serializer('Test');

        $config = $this->generateConfigMock('serial');
        $config->setReturnValue('get', 2, array('Test.DefinitionRev'));
        $config->version = '1.0.0';

        $config_md5   = '1.0.0,serial,2';

        $file = realpath(
            $rel_file = HTMLPURIFIER_PREFIX . '/HTMLPurifier/DefinitionCache/Serializer/Test/' .
            $config_md5 . '.ser'
        );
        if($file && file_exists($file)) unlink($file); // prevent previous failures from causing problems

        $this->assertIdentical($config_md5, $cache->generateKey($config));

        $def_original = $this->generateDefinition();

        $cache->add($def_original, $config);
        $this->assertFileExist($rel_file);

        $file_generated = $cache->generateFilePath($config);
        $this->assertIdentical(realpath($rel_file), realpath($file_generated));

        $def_1 = $cache->get($config);
        // $this->assertIdentical($def_original, $def_1);

        $def_original->info_random = 'changed';

        $cache->set($def_original, $config);
        $def_2 = $cache->get($config);

        // $this->assertIdentical($def_original, $def_2);
        // $this->assertNotEqual ($def_original, $def_1);

        $def_original->info_random = 'did it change?';

        $this->assertFalse($cache->add($def_original, $config));
        $def_3 = $cache->get($config);

        // $this->assertNotEqual ($def_original, $def_3); // did not change!
        // $this->assertIdentical($def_3, $def_2);

        $cache->replace($def_original, $config);
        $def_4 = $cache->get($config);
        // $this->assertIdentical($def_original, $def_4);

        $cache->remove($config);
        $this->assertFileNotExist($file);

        $this->assertFalse($cache->replace($def_original, $config));
        $def_5 = $cache->get($config);
        $this->assertFalse($def_5);

    }

    function test_errors() {
        $cache = new HTMLPurifier_DefinitionCache_Serializer('Test');
        $def = $this->generateDefinition();
        $def->setup = true;
        $def->type = 'NotTest';
        $config = $this->generateConfigMock('testfoo');

        $this->expectError('Cannot use definition of type NotTest in cache for Test');
        $cache->add($def, $config);

        $this->expectError('Cannot use definition of type NotTest in cache for Test');
        $cache->set($def, $config);

        $this->expectError('Cannot use definition of type NotTest in cache for Test');
        $cache->replace($def, $config);
    }

    function test_flush() {

        $cache = new HTMLPurifier_DefinitionCache_Serializer('Test');

        $config1 = $this->generateConfigMock('test1');
        $config2 = $this->generateConfigMock('test2');
        $config3 = $this->generateConfigMock('test3');

        $def1 = $this->generateDefinition(array('info_candles' => 1));
        $def2 = $this->generateDefinition(array('info_candles' => 2));
        $def3 = $this->generateDefinition(array('info_candles' => 3));

        $cache->add($def1, $config1);
        $cache->add($def2, $config2);
        $cache->add($def3, $config3);

        $this->assertEqual($def1, $cache->get($config1));
        $this->assertEqual($def2, $cache->get($config2));
        $this->assertEqual($def3, $cache->get($config3));

        $cache->flush($config1); // only essential directive is %Cache.SerializerPath

        $this->assertFalse($cache->get($config1));
        $this->assertFalse($cache->get($config2));
        $this->assertFalse($cache->get($config3));

    }

    function testCleanup() {

        $cache = new HTMLPurifier_DefinitionCache_Serializer('Test');

        // in order of age, oldest first
        // note that configurations are all identical, but version/revision
        // are different

        $config1 = $this->generateConfigMock();
        $config1->version = '0.9.0';
        $config1->setReturnValue('get', 574, array('Test.DefinitionRev'));
        $def1 = $this->generateDefinition(array('info' => 1));

        $config2 = $this->generateConfigMock();
        $config2->version = '1.0.0beta';
        $config2->setReturnValue('get', 1, array('Test.DefinitionRev'));
        $def2 = $this->generateDefinition(array('info' => 3));

        $cache->set($def1, $config1);
        $cache->cleanup($config1);
        $this->assertEqual($def1, $cache->get($config1)); // no change

        $cache->cleanup($config2);
        $this->assertFalse($cache->get($config1));
        $this->assertFalse($cache->get($config2));

    }

    function testCleanupOnlySameID() {

        $cache = new HTMLPurifier_DefinitionCache_Serializer('Test');

        $config1 = $this->generateConfigMock('serial1');
        $config1->version = '1.0.0';
        $config1->setReturnValue('get', 1, array('Test.DefinitionRev'));
        $def1 = $this->generateDefinition(array('info' => 1));

        $config2 = $this->generateConfigMock('serial2');
        $config2->version = '1.0.0';
        $config2->setReturnValue('get', 34, array('Test.DefinitionRev'));
        $def2 = $this->generateDefinition(array('info' => 3));

        $cache->set($def1, $config1);
        $cache->cleanup($config1);
        $this->assertEqual($def1, $cache->get($config1)); // no change

        $cache->set($def2, $config2);
        $cache->cleanup($config2);
        $this->assertEqual($def1, $cache->get($config1));
        $this->assertEqual($def2, $cache->get($config2));

        $cache->flush($config1);
    }

    /**
     * Asserts that a file exists, ignoring the stat cache
     */
    function assertFileExist($file) {
        clearstatcache();
        $this->assertTrue(file_exists($file), 'Expected ' . $file . ' exists');
    }

    /**
     * Asserts that a file does not exist, ignoring the stat cache
     */
    function assertFileNotExist($file) {
        clearstatcache();
        $this->assertFalse(file_exists($file), 'Expected ' . $file . ' does not exist');
    }

    function testAlternatePath() {

        $cache = new HTMLPurifier_DefinitionCache_Serializer('Test');
        $config = $this->generateConfigMock('serial');
        $config->version = '1.0.0';
        $config->setReturnValue('get', 1, array('Test.DefinitionRev'));
        $dir = dirname(__FILE__) . '/SerializerTest';
        $config->setReturnValue('get', $dir, array('Cache.SerializerPath'));

        $def_original = $this->generateDefinition();
        $cache->add($def_original, $config);
        $this->assertFileExist($dir . '/Test/1.0.0,serial,1.ser');

        unlink($dir . '/Test/1.0.0,serial,1.ser');
        rmdir( $dir . '/Test');

    }

    function testAlternatePermissions() {

        $cache = new HTMLPurifier_DefinitionCache_Serializer('Test');
        $config = $this->generateConfigMock('serial');
        $config->version = '1.0.0';
        $config->setReturnValue('get', 1, array('Test.DefinitionRev'));
        $dir = dirname(__FILE__) . '/SerializerTest';
        $config->setReturnValue('get', $dir, array('Cache.SerializerPath'));
        $config->setReturnValue('get', 0777, array('Cache.SerializerPermissions'));

        $def_original = $this->generateDefinition();
        $cache->add($def_original, $config);
        $this->assertFileExist($dir . '/Test/1.0.0,serial,1.ser');

        $this->assertEqual(0666, 0777 & fileperms($dir . '/Test/1.0.0,serial,1.ser'));
        $this->assertEqual(0777, 0777 & fileperms($dir . '/Test'));

        unlink($dir . '/Test/1.0.0,serial,1.ser');
        rmdir( $dir . '/Test');

    }
}

// vim: et sw=4 sts=4
