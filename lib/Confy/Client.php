<?php

namespace Confy;

use Confy\HttpClient\HttpClient;

class Client
{
    private $httpClient;

    public function __construct($auth = array(), array $options = array())
    {
        $this->httpClient = new HttpClient($auth, $options);
    }

    /**
     * User who is authenticated currently.
     */
    public function user()
    {
        return new Api\User($this->httpClient);
    }

    /**
     * Organizations are owned by users and only (s)he can add/remove teams and projects for that organization. A default organization will be created for every user.
     */
    public function orgs()
    {
        return new Api\Orgs($this->httpClient);
    }

}
