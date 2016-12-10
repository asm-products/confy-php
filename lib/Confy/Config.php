<?php

namespace Confy;

use Confy\HttpClient\HttpClient;

class Config
{
    public static function match($url) {
        if (gettype($url) == 'string') {
            $nameRegex = '([a-z0-9][a-z0-9-]*[a-z0-9])';
            $tokenRegex = '([a-f0-9]{40})';
            $pathRegex = 'orgs\\/'.$nameRegex.'(\\/projects\\/'.$nameRegex.'\\/envs\\/'.$nameRegex.'\\/config|\\/config\\/'.$tokenRegex.')';
            $urlRegex = '/(https?:\\/\\/)((.*):(.*)@)?(.*)\\/('.$pathRegex.'|heroku\\/config)/i';

            preg_match($urlRegex, $url, $matches);

            if (count($matches) == 0) {
                throw new \Exception('Invalid URL');
            }

            $url = array(
                'host' => $matches[1].$matches[5],
                'user' => $matches[3], 'pass' => $matches[4],
                'org' => (count($matches) > 7 ? $matches[7] : ""),
                'project' => (count($matches) > 7 ? $matches[9] : ""),
                'env' => (count($matches) > 7 ? $matches[10] : ""),
                'token' => (count($matches) == 12 ? $matches[11] : ""),
                'heroku' => ($matches[6] == 'heroku/config')
            );
        }

        if (gettype($url) != 'array') {
            throw new \Exception('Invalid URL');
        }

        $exists = function ($value, $key) {
            return isset($value[$key]) && !empty($value[$key]);
        };

        if ($exists($url, 'host') && $exists($url, 'user') && $exists($url, 'pass') && $exists($url, 'heroku')) {
            $url['path'] = '/heroku/config';
        } else if ($exists($url, 'host') && $exists($url, 'token') && $exists($url, 'org')) {
            $url['path'] = '/orgs/'.$url['org'].'/config/'.$url['token'];
        } else if ($exists($url, 'host') && $exists($url, 'user') && $exists($url, 'pass') && $exists($url, 'org') && $exists($url, 'project') && $exists($url, 'env')) {
            $url['path'] = '/orgs/'.$url['org'].'/projects/'.$url['project'].'/envs/'.$url['env'].'/config';
        } else {
            throw new \Exception('Invalid URL');
        }

        return $url;
    }

    public static function load($url = array()) {
        $url = Config::match($url);

        $auth = array();

        if ($url['user'] && $url['pass']) {
            $auth['username'] = $url['user'];
            $auth['password'] = $url['pass'];
        }

        $httpClient = new HttpClient($auth, array('base' => $url['host']));

        $body = $httpClient->get($url['path'])->body;

        if (gettype($body) == 'array') {
            return $body;
        }

        $decryptPass = getenv('CONFY_DECRYPT_PASS');

        if (gettype($body) != 'string') {
            throw new \Exception('Invalid credential document');
        }

        if (empty($decryptPass)) {
            throw new \Exception('No decryption password found. Fill env var CONFY_DECRYPT_PASS', 1);
        }

        $iv = base64_decode(substr($body, 0, 24));
        $key = md5($decryptPass);

        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode(substr($body, 24)), MCRYPT_MODE_CBC, $iv);
        $decrypted = substr($decrypted, 0, -ord($decrypted[strlen($decrypted) - 1]));

        $body = json_decode($decrypted);

        if (JSON_ERROR_NONE != json_last_error()) {
            throw new \Exception('Decryption password is wrong');
        }

        return $body;
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
