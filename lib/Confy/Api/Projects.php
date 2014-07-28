<?php

namespace Confy\Api;

use Confy\HttpClient\HttpClient;

/**
 * An organization can contain any number of projects.
 *
 * @param $org Name of the organization
 */
class Projects
{

    private $org;
    private $client;

    public function __construct($org, HttpClient $client)
    {
        $this->org = $org;
        $this->client = $client;
    }

    /**
     * List all the projects of the organization which can be seen by the authenticated user.
     *
     * '/orgs/:org/projects' GET
     */
    public function list(array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode($this->org).'/projects', $body, $options);

        return $response;
    }

    /**
     * Create a project for the given organization. Authenticated user should be the owner of the organization.
     *
     * '/orgs/:org/projects' POST
     *
     * @param $name Name of the project
     * @param $description Description of the project
     */
    public function create($name, $description, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['name'] = $name;
        $body['description'] = $description;

        $response = $this->client->post('/orgs/'.rawurlencode($this->org).'/projects', $body, $options);

        return $response;
    }

    /**
     * Get a project the user has access to.
     *
     * '/orgs/:org/projects/:project' GET
     *
     * @param $project Name of the project
     */
    public function retrieve($project, array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($project).'', $body, $options);

        return $response;
    }

    /**
     * Update a project. Authenticated user should be the owner of the organization.
     *
     * '/orgs/:org/projects/:project' PATCH
     *
     * @param $project Name of the project
     * @param $description Description of the project
     */
    public function update($project, $description, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['description'] = $description;

        $response = $this->client->patch('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($project).'', $body, $options);

        return $response;
    }

    /**
     * Delete the given project. Cannot delete the default project in the organization. Authenticated user should be the owner of the organization.
     *
     * '/orgs/:org/projects/:project' DELETE
     *
     * @param $project Name of the project
     */
    public function destroy($project, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());

        $response = $this->client->delete('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($project).'', $body, $options);

        return $response;
    }

}
