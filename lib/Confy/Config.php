<?php

namespace Confy;

use Confy\Client;

class Config
{
    public static function load($url = array())
    {
        if (gettype($url) == 'string') {
            preg_match('/(https?:\/\/)(.*):(.*)@(.*)\/orgs\/([a-z0-9]*)\/projects\/([a-z0-9]*)\/envs\/([a-z0-9]*)\/config/i', $url, $matches);

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
}
