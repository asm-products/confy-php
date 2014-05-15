<?php

namespace Confy\Api;

use Confy\HttpClient\HttpClient;

/**
 * List of teams who has access to the project. Default team __Owners__ will have access to every project. Authenticated user should be the owner of the organization for the below endpoints.
 *
 * @param $org Name of the organization
 * @param $project Name of the project
 */
class Access
{

    private $org;
    private $project;
    private $client;

    public function __construct($org, $project, HttpClient $client)
    {
        $this->org = $org;
        $this->project = $project;
        $this->client = $client;
    }

    /**
     * Give the team access to the given project. The __team__ in the request needs to be a string.
     *
     * '/orgs/:org/projects/:project/access' POST
     *
     * @param $team Name of the team
     */
    public function add($team, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['team'] = $team;

        $response = $this->client->post('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/access', $body, $options);

        return $response;
    }

    /**
     * Remove project access for the given team. The __team__ in the request needs to be a string. Can't delete default team's access.
     *
     * '/orgs/:org/projects/:project/access' DELETE
     *
     * @param $team Name of the team
     */
    public function remove($team, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['team'] = $team;

        $response = $this->client->delete('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/access', $body, $options);

        return $response;
    }

}
