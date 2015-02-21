<?php

namespace Confy\Api;

use Confy\HttpClient\HttpClient;

/**
 * Teams contain a list of users. The Authenticated user should be the owner of the organization.
 *
 * @param $org Name of the organization
 * @param $team Name of the team
 */
class Members
{

    private $org;
    private $team;
    private $client;

    public function __construct($org, $team, HttpClient $client)
    {
        $this->org = $org;
        $this->team = $team;
        $this->client = $client;
    }

    /**
     * List all the members in the given team. Authenticated user should be a member of the team or the owner of the org.
     *
     * '/orgs/:org/teams/:team/member' GET
     */
    public function list(array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode($this->org).'/teams/'.rawurlencode($this->team).'/member', $body, $options);

        return $response;
    }

    /**
     * Add the user to the given team. The __user__ in the request needs to be a string and be the username of a valid user.  The Authenticated user should be the owner of the organization.
     *
     * '/orgs/:org/teams/:team/member' POST
     *
     * @param $user Username of the user
     */
    public function add($user, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['user'] = $user;

        $response = $this->client->post('/orgs/'.rawurlencode($this->org).'/teams/'.rawurlencode($this->team).'/member', $body, $options);

        return $response;
    }

    /**
     * Remove users from the given team. The __user__ in the request needs to be a string and be the username of a valid user. Cannot delete the default member in a team.  The Authenticated user should be the owner of the organization.
     *
     * '/orgs/:org/teams/:team/member' DELETE
     *
     * @param $user Username of the user
     */
    public function remove($user, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['user'] = $user;

        $response = $this->client->delete('/orgs/'.rawurlencode($this->org).'/teams/'.rawurlencode($this->team).'/member', $body, $options);

        return $response;
    }

}
