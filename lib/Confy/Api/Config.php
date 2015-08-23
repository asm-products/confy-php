<?php

namespace Confy\Api;

use Confy\HttpClient\HttpClient;

/**
 * Any member of the team which has access to the project can retrieve any of it's environment's configuration document or edit it.
 *
 * @param $org Name of the organization
 * @param $project Name of the project
 * @param $env Name of the environment
 */
class Config
{

    private $org;
    private $project;
    private $env;
    private $client;

    public function __construct($org, $project, $env, HttpClient $client)
    {
        $this->org = $org;
        $this->project = $project;
        $this->env = $env;
        $this->client = $client;
    }

    /**
     * Get an environment configuration
     *
     * '/orgs/:org/projects/:project/envs/:env/config' GET
     */
    public function retrieve(array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/envs/'.rawurlencode($this->env).'/config', $body, $options);

        return $response;
    }

    /**
     * Update the configuration document for the given environment of the project. We will patch the document recursively.
     *
     * '/orgs/:org/projects/:project/envs/:env/config' PATCH
     *
     * @param $config Configuration to update
     */
    public function update($config, array $options = array())
    {
        $body = (isset($options['body']) ? $options['body'] : array());
        $body['config'] = $config;

        $response = $this->client->patch('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/envs/'.rawurlencode($this->env).'/config', $body, $options);

        return $response;
    }

    /**
     * List the last 10 versions of the environment configuration
     *
     * '/orgs/:org/projects/:project/envs/:env/versions' GET
     */
    public function versions(array $options = array())
    {
        $body = (isset($options['query']) ? $options['query'] : array());

        $response = $this->client->get('/orgs/'.rawurlencode($this->org).'/projects/'.rawurlencode($this->project).'/envs/'.rawurlencode($this->env).'/versions', $body, $options);

        return $response;
    }

}
