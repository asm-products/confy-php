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
     * List all the projects of the given organization which can be accessed by the authenticated user.
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
     * Create a project if the authenticated user is the owner of the given organization. Only the __owners__ team will be able to see the project initially.
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
     * Get the given project in the given organization. Works only if the authenticated user has access to the project.
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
     * Update the given project. __Description__ is the only thing which can be updated. Authenticated user should be the owner of the organization.
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
     * Delete the given project. Authenticated user should be the owner of the organization.
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
