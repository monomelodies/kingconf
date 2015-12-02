<?php

namespace Kingconf;

use ArrayObject;
use Symfony\Component\Yaml\Yaml;

class Config extends ArrayObject
{
    /**
     * Constructor. Pass one or more paths to config files in supported formats
     * (ini, php, json, xml, yaml).
     */
    public function __construct($config)
    {
        $configs = func_get_args();
        foreach ($configs as $config) {
            $this->loadConfig($config);
        }
    }

    private function loadConfig($config)
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

