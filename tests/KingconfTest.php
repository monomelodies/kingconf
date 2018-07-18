<?php

namespace Monomelodies\Kingconf\Tests;

use PHPUnit\Framework\TestCase;
use Monomelodies\Kingconf\Config;

class KingconfTest extends TestCase
{
    private function config($env)
    {
        return function () use ($env) {
            return $env;
        };
    }

    private function runtests($config)
    {
        $config = dirname(__FILE__)."/$config";
        $kingconf = new Config($config);
        $this->assertEquals('bar', $kingconf['foo']);
    }

    public function testJson()
    {
        $this->runtests('json.json');
    }

    public function testIni()
    {
        $this->runtests('ini.ini');
    }

    public function testPhp()
    {
        $this->runtests('php.php');
    }

    public function testYaml()
    {
        $this->runtests('yaml.yml');
    }

    public function testXml()
    {
        $this->runtests('xml.xml');
    }

    public function testAll()
    {
        $kingconf = new Config(
            dirname(__FILE__).'/overwrite.json',
            dirname(__FILE__).'/json.json',
            dirname(__FILE__).'/ini.ini',
            dirname(__FILE__).'/php.php',
            dirname(__FILE__).'/yaml.yml',
            dirname(__FILE__).'/xml.xml'
        );
        $this->assertEquals('baz', $kingconf['foo']);
    }
}

