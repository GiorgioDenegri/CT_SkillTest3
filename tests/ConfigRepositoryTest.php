<?php

use Coalition\ConfigRepository;

class ConfigRepositoryTest extends PHPUnit_Framework_TestCase
{
    public function test_has_returns_true_if_a_config_item_is_set()
    {
        $config = new ConfigRepository(['paths' => ['base' => 'path', 'app' => 'path']]);
        $this->assertTrue($config->has('paths'));
    }

    public function test_has_returns_false_if_a_config_item_is_not_set()
    {
        $config = new ConfigRepository(['paths' => ['base' => 'path', 'app' => 'path']]);

        $this->assertFalse($config->has('foo'));
    }

    public function test_setting_config_items()
    {
        $config = new ConfigRepository;

        $config->set('paths', ['base' => 'path', 'app' => 'path'])
               ->set('options', ['foo' => 'bar']);

        $this->assertTrue($config->has('paths'));
        $this->assertTrue($config->has('options'));
    }

    public function test_get_an_item_from_the_config_array()
    {
        $config = new ConfigRepository;

        $config->set('paths', ['base' => 'path', 'app' => 'path'])
               ->set('options', 'value');

        $paths   = $config->get('paths');

        $this->assertEquals('value', $config->get('options'));
        $this->assertEquals(['base' => 'path', 'app' => 'path'], $paths);
        $this->assertEquals('path', $paths['base']);
    }

    public function test_getting_a_non_existing_key_returns_the_default_value()
    {
        $config = new ConfigRepository;

        $this->assertEquals('foo', $config->get('key', 'foo'));
    }

    public function test_removing_an_item_from_the_config_array()
    {
        $config = new ConfigRepository(['foo' => 'bar', 'baz' => 'bam']);

        $config->remove('foo')->remove('bar');

        $this->assertFalse($config->has('foo'));
        $this->assertFalse($config->has('bar'));
    }

    public function test_loading_config_items_from_a_single_file()
    {
        $config = new ConfigRepository;
        $config->load(__DIR__.'/files/app.php');

        $this->assertTrue($config->has('app'));
        $this->assertEquals(
            ['base_path' => 'base/path', 'app_path' => 'app/path', 'public_path' => 'public/path'],
            $config->get('app')
        );
    }

    public function test_loading_an_array_of_config_files()
    {
        $files = [__DIR__.'/files/app.php', __DIR__.'/files/database.php'];
        $config = new ConfigRepository;
        $config->load($files);

        $this->assertTrue($config->has('app'));
        $this->assertTrue($config->has('database'));
    }

    public function test_array_access_set()
    {
        $config = new ConfigRepository;

        $config['foo'] = 'bar';
        $this->assertTrue(isset($config['foo']));
        $this->assertEquals('bar', $config['foo']);
    }

    public function test_array_access_unset()
    {
        $config = new ConfigRepository(['foo' => 'bar']);
        unset($config['foo']);

        $this->assertFalse($config->has('foo'));
    }

}