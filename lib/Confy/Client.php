<?php

namespace Confy;

use Confy\HttpClient\HttpClient;

class Client
{
    private $httpClient;

    public function __construct($auth = array(), array $options = array())
    {
        $this->httpClient = new HttpClient($auth, $options);
    }

    /**
     * User who is authenticated currently.
     */
    public function user()
    {
        return new Api\User($this->httpClient);
    }

    /**
     * Organizations are owned by users and only (s)he can add/remove teams and projects for that organization. A default organization will be created for every user.
     */
    public function orgs()
    {
        return new Api\Orgs($this->httpClient);
    }

    /**
     * Every organization will have a default team named Owners. Owner of the organization will be a default member for every team.
     *
     * @param $org Name of the organization
     */
    public function teams($org)
    {
        return new Api\Teams($org, $this->httpClient);
    }

    /**
     * Teams contain a list of users. The Authenticated user should be the owner of the organization.
     *
     * @param $org Name of the organization
     * @param $team Name of the team
     */
    public function members($org, $team)
    {
        return new Api\Members($org, $team, $this->httpClient);
    }

    /**
     * An organization can contain any number of projects.
     *
     * @param $org Name of the organization
     */
    public function projects($org)
    {
        return new Api\Projects($org, $this->httpClient);
    }

    /**
     * List of teams who has access to the project. Default team __Owners__ will have access to every project. Authenticated user should be the owner of the organization for the below endpoints.
     *
     * @param $org Name of the organization
     * @param $project Name of the project
     */
    public function access($org, $project)
    {
        return new Api\Access($org, $project, $this->httpClient);
    }

    /**
     * Every project has a default environment named Production. Each environment has one configuration document which can have many keys and values.
     *
     * @param $org Name of the organization
     * @param $project Name of the project
     */
    public function envs($org, $project)
    {
        return new Api\Envs($org, $project, $this->httpClient);
    }

    /**
     * Any member of the team which has access to the project can retrieve any of it's environment's configuration document or edit it.
     *
     * @param $org Name of the organization
     * @param $project Name of the project
     * @param $env Name of the environment
     */
    public function config($org, $project, $env)
    {
        return new Api\Config($org, $project, $env, $this->httpClient);
    }

}
