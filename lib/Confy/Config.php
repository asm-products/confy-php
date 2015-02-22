<?php

namespace Confy;

use Confy\HttpClient\HttpClient;

class Config
{
    public static function load($url = array()) {
        if (gettype($url) == 'string') {
            $nameRegex = '([a-z0-9][a-z0-9-]*[a-z0-9])';
            $pathRegex = 'orgs\\/'.$nameRegex.'\\/projects\\/'.$nameRegex.'\\/envs\\/'.$nameRegex;
            $urlRegex = '/(https?:\\/\\/)(.*):(.*)@(.*)\\/('.$pathRegex.'|heroku)\\/config/i';

            preg_match($urlRegex, $url, $matches);

            if (count($matches) == 0) {
                throw new \Exception('Invalid url');
            }

            $url = array(
                'host' => $matches[1].$matches[4], 'path' => '/'.$matches[5].'/config',
                'user' => $matches[2], 'pass' => $matches[3]
            );
        }

        if (gettype($url) != 'array') {
            throw new \Exception('Invalid url');
        }

        $httpClient = new HttpClient(array(
            'username' => $url['user'], 'password' => $url['pass']
        ), array('base' => $url['host']));

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
