<?php

namespace Monomelodies\Kingconf;

use ArrayObject;
use Symfony\Component\Yaml\Yaml;

/**
 * The Config class. Construct with one or more config file paths, then access
 * as an array (e.g. `$config['foo']`).
 */
class Config extends ArrayObject
{
    /**
     * Constructor. Pass one or more paths to config files in supported formats
     * (ini, php, json, xml, yaml).
     *
     * @see Kingconf\Config::loadConfig
     */
    public function __construct(string ...$configs)
    {
        foreach ($configs as $config) {
            $this->loadConfig($config);
        }
    }

    /**
     * Private helper method to load a single config file.
     *
     * @param string $config Path to the config file to load.
     * @return void
     * @throws Kingconf\NotfoundException if the file doesn't exist.
     * @throws Kingconf\InvalidException if the formatting is invalid (e.g.
     *  invalid JSON).
     * @throws Kingconf\UnknownFormatException if the file format is not
     *  supported. Note that this is a simple extension check.
     */
    private function loadConfig(string $config) : void
    {
        if (!file_exists($config)) {
            throw new NotfoundException;
        }
        $ext = substr($config, strrpos($config, '.') + 1);
        switch ($ext) {
            case 'json':
                $settings = json_decode(file_get_contents($config), true);
                if (is_null($settings)) {
                    throw new InvalidException;
                }
                break;
            case 'yml':
            case 'yaml':
                try {
                    $settings = Yaml::parse(file_get_contents($config));
                } catch (\Exception $e) {
                    throw new InvalidException;
                }
                break;
            case 'ini':
                $settings = parse_ini_file($config, true);
                if ($settings === false) {
                    throw new InvalidException;
                }
                break;
            case 'xml':
                $xml = simplexml_load_file($config);
                if ($xml === false) {
                    throw new InvalidException;
                }
                $settings = json_decode(json_encode($xml), true);
                break;
            case 'php':
                $settings = include $config;
                if (!is_array($settings)) {
                    throw new InvalidException;
                }
                break;
            default:
                throw new UnknownFormatException;
        }
        $new = (array)$this + $settings;
        foreach ($new as $key => $value) {
            $this[$key] = $value;
        }            
    }
}

