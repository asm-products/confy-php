<?php

namespace Confy\Api;

use Confy\HttpClient\HttpClient;

/**
 * Organizations are owned by users and only (s)he can add/remove teams and projects for that organization. A default organization will be created for every user.
 */
class Orgs
{

    private $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * List all organizations the authenticated user is a member of.
     *
     * '/orgs' GET
     */
    public function list(array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs', $body, $options);

        return $response;
    }

    /**
     * Create an organization with a name and the email for billing.
     *
     * '/orgs' POST
     *
     * @param $name Name of the organization
     * @param $email Billing email of the organization
     */
    public function create($name, $email, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['name'] = $name;
        $body['email'] = $email;

        $response = $this->client->post('/orgs', $body, $options);

        return $response;
    }

    /**
     * Get an organization the user has access to.
     *
     * '/orgs/:org' GET
     *
     * @param $org Name of the organization
     */
    public function retrieve($org, array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode(org).'', $body, $options);

        return $response;
    }

    /**
     * Update an organization the user is owner of.
     *
     * '/orgs/:org' PATCH
     *
     * @param $org Name of the organization
     * @param $email Billing email of the organization
     */
    public function update($org, $email, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['email'] = $email;

        $response = $this->client->patch('/orgs/'.rawurlencode(org).'', $body, $options);

        return $response;
    }

}
