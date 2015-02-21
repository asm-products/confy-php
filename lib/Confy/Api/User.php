<?php

namespace Confy\Api;

use Confy\HttpClient\HttpClient;

/**
 * User who is authenticated currently.
 */
class User
{

    private $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get the authenticated user's profile.
     *
     * '/user' GET
     */
    public function retrieve(array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/user', $body, $options);

        return $response;
    }

    /**
     * Update the authenticated user's profile. Should use basic authentication.
     *
     * '/user' PATCH
     *
     */
    public function update(array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());

        $response = $this->client->patch('/user', $body, $options);

        return $response;
    }

}
