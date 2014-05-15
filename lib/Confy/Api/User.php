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
     * Update the authenticated user's profile
     *
     * '/user' PATCH
     *
     * @param $email Profile email of the user
     */
    public function update($email, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['email'] = $email;

        $response = $this->client->patch('/user', $body, $options);

        return $response;
    }

}
