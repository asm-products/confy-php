<?php

namespace Confy\Api;

use Confy\HttpClient\HttpClient;

/**
 * Every project has a default environment named Production. Each environment has one configuration document which can have many keys and values.
 *
 * @param $org Name of the organization
 * @param $project Name of the project
 */
class Envs
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
     * List all the environmens of the project which can be seen by the authenticated user.
     *
     * '/orgs/:org/projects/:project/envs' GET
     */
    public function list(array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/envs', $body, $options);

        return $response;
    }

    /**
     * Create an environment for the given project. Authenticated user should have access to the project.
     *
     * '/orgs/:org/projects/:project/envs' POST
     *
     * @param $name Name of the environment
     * @param $description Description of the environment
     */
    public function create($name, $description, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['name'] = $name;
        $body['description'] = $description;

        $response = $this->client->post('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/envs', $body, $options);

        return $response;
    }

    /**
     * Get an environment of the project the user has access to.
     *
     * '/orgs/:org/projects/:project/envs/:env' GET
     *
     * @param $env Name of the environment
     */
    public function retrieve($env, array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/envs/'.rawurlencode($env).'', $body, $options);

        return $response;
    }

    /**
     * Update an environment. Authenticated user should have access to the project.
     *
     * '/orgs/:org/projects/:project/envs/:env' PATCH
     *
     * @param $env Name of the environment
     * @param $description Description of the environment
     */
    public function update($env, $description, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['description'] = $description;

        $response = $this->client->patch('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/envs/'.rawurlencode($env).'', $body, $options);

        return $response;
    }

    /**
     * Delete the given environment of the project. Authenticated user should have access to the project. Cannot delete the default environment.
     *
     * '/orgs/:org/projects/:project/envs/:env' DELETE
     *
     * @param $env Name of the environment
     */
    public function destroy($env, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());

        $response = $this->client->delete('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/envs/'.rawurlencode($env).'', $body, $options);

        return $response;
    }

}
