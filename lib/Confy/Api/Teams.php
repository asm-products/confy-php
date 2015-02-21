<?php

namespace Confy\Api;

use Confy\HttpClient\HttpClient;

/**
 * Every organization will have a default team named __Owners__. Owner of the organization will be a default member for every team.
 *
 * @param $org Name of the organization
 */
class Teams
{

    private $org;
    private $client;

    public function __construct($org, HttpClient $client)
    {
        $this->org = $org;
        $this->client = $client;
    }

    /**
     * List teams of the given organization authenticated user is a member of.
     *
     * '/orgs/:org/teams' GET
     */
    public function list(array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode($this->org).'/teams', $body, $options);

        return $response;
    }

    /**
     * Create a team for the given organization. Authenticated user should be the owner of the organization.
     *
     * '/orgs/:org/teams' POST
     *
     * @param $name Name of the team
     * @param $description Description of the team
     */
    public function create($name, $description, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['name'] = $name;
        $body['description'] = $description;

        $response = $this->client->post('/orgs/'.rawurlencode($this->org).'/teams', $body, $options);

        return $response;
    }

    /**
     * Get the given team in the given organization. Access only if the authenticated user is a member of the team.
     *
     * '/orgs/:org/teams/:team' GET
     *
     * @param $team Name of the team
     */
    public function retrieve($team, array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode($this->org).'/teams/'.rawurlencode($team).'', $body, $options);

        return $response;
    }

    /**
     * Update the given team. __Description__ is the only thing which can be updated. Authenticated user should be the owner of the organization.
     *
     * '/orgs/:org/teams/:team' PATCH
     *
     * @param $team Name of the team
     * @param $description Description of the team
     */
    public function update($team, $description, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['description'] = $description;

        $response = $this->client->patch('/orgs/'.rawurlencode($this->org).'/teams/'.rawurlencode($team).'', $body, $options);

        return $response;
    }

    /**
     * Delete the given team. Cannot delete the default team in the organization. Authenticated user should be the owner of the organization.
     *
     * '/orgs/:org/teams/:team' DELETE
     *
     * @param $team Name of the team
     */
    public function destroy($team, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());

        $response = $this->client->delete('/orgs/'.rawurlencode($this->org).'/teams/'.rawurlencode($team).'', $body, $options);

        return $response;
    }

}
