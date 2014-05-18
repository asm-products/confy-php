<?php

namespace Confy;

use Confy\Client;

class Config
{
    public static function load($url = array()) {
        if (gettype($url) == 'string') {
            preg_match('/(https?:\/\/)(.*):(.*)@(.*)\/orgs\/([a-z0-9]*)\/projects\/([a-z0-9]*)\/envs\/([a-z0-9]*)\/config/i', $url, $matches);

            if (count($matches) == 0) {
                throw new Exception('Invalid url');
            }

            $url = array(
                'host' => $matches[1].$matches[4], 'user' => $matches[2], 'pass' => $matches[3],
                'org' => $matches[5], 'project' => $matches[6], 'env' => $matches[7]
            );
        }

        $client = new Client(array(
            'username' => $url['user'], 'password' => $url['pass']
        ), array('base' => $url['host']));

        return $client->config($url['org'], $url['project'], $url['env'])->retrieve()->body;
    }

    public static function env($url = array()) {
        Config::path(Config::load($url));
    }

    public static function path($config, $str="") {
        foreach ($config as $key => $value) {
            $key = $str."_".strtoupper($key);

            switch (gettype($value)) {
                case 'array':
                    Config::path($value, $key);
                    break;

                case 'integer': case 'string': case 'double': case 'boolean':
                    $_ENV[substr($key, 1)] = $value;
                    break;

                default:
            }
        }
    }
}
