# confy-php

Official Confy API library client for PHP

__This library is generated by [alpaca](https://github.com/pksunkara/alpaca)__

## Installation

Make sure you have [composer](https://getcomposer.org) installed.

Add the following to your composer.json

```js
{
    "require": {
        "confyio/confyio": "*"
    }
}
```

Update your dependencies

```bash
$ php composer.phar update
```

> This package follows the `PSR-0` convention names for its classes, which means you can easily integrate these classes loading in your own autoloader.

#### Versions

Works with [ 5.4 / 5.5 / 5.6 ]

## Usage

### Before Starting

There are two ways of pointing to the credential document. You need to choose one of them.

#### User URL

Using user's authentication information

```js
'https://user:pass@api.confy.io/orgs/orgname/projects/projectname/envs/envname/config'
```

#### Access Token URL

Using unique access token

```js
'https://api.confy.io/orgs/orgname/config/abcdefabcdefabcdef1234567890abcdefabcdef'
```

### Initiate API Client

```php
<?php

// This file is generated by Composer
require_once 'vendor/autoload.php';

// When the config is
// => array('port' => 6000, 'db' => array('pass' => 'sun'))
```

### Define Endpoint

You need to provide either an URL or an options objects to point the API client to the correct credential document.

```php
// User URL
$endpoint = array(
  'user' => 'user', // Username of the user trying to access the document
  'pass' => 'pass', // Password of the user trying to access the document
  'org' => 'company', // Name of the organization
  'project' => 'app', // Name of the project
  'env' => 'production', // Name of the stage
);

$endpoint = 'https://user:pass@api.confy.io/orgs/orgname/projects/projectname/envs/envname/config';

// Access Token URl
$endpoint = array(
  'org' => 'company', // Name of the organization
  'token' => 'abcdefabcdefabcdef1234567890abcdefabcdef' // Access token of the document
);

$endpoint = 'https://api.confy.io/orgs/orgname/config/abcdefabcdefabcdef1234567890abcdefabcdef';
```

### Call the Server

There are two ways of loading the credentials.

#### Data Object

You can load it as a hash object with the same structure into a variable.

```php
$config = Confy\Config::load($endpoint);

$config['port'] // => 6000
$config['db']['pass'] // => 'sun'
```

#### Environment Variables

You can load it directly into `$_ENV` with the key formed by concatenizing the path keys with underscores.

```php
Confy\Config::env($endpoint);

// ['port']
$_ENV['PORT'] // => 6000

// ['db']['pass']
$_ENV['DB_PASS'] // => 'sun'
```

## API Reference

### Build a client

##### Basic authentication

```php
$auth = array('username' => 'pksunkara', 'password' => 'password');

$client = new Confy\Client($auth, $clientOptions);
```

### Client Options

The following options are available while instantiating a client:

 * __base__: Base url for the api
 * __api_version__: Default version of the api (to be used in url)
 * __user_agent__: Default user-agent for all requests
 * __headers__: Default headers for all requests
 * __request_type__: Default format of the request body

### Response information

__All the callbacks provided to an api call will receive the response as shown below__

```php
$response = $client->klass('args')->method('args', $methodOptions);

$response->code;
// >>> 200

$response->headers;
// >>> array('x-server' => 'apache')
```

##### JSON response

When the response sent by server is __json__, it is decoded into an array

```php
$response->body;
// >>> array('user' => 'pksunkara')
```

### Method Options

The following options are available while calling a method of an api:

 * __api_version__: Version of the api (to be used in url)
 * __headers__: Headers for the request
 * __query__: Query parameters for the url
 * __body__: Body of the request
 * __request_type__: Format of the request body

### Request body information

Set __request_type__ in options to modify the body accordingly

##### RAW request

When the value is set to __raw__, don't modify the body at all.

```php
$body = 'username=pksunkara';
// >>> 'username=pksunkara'
```

##### JSON request

When the value is set to __json__, JSON encode the body.

```php
$body = array('user' => 'pksunkara');
// >>> '{"user": "pksunkara"}'
```

### Authenticated User api

User who is authenticated currently.

```php
$user = $client->user();
```

##### Retrieve authenticated user (GET /user)

Get the authenticated user's profile.

```php
$response = $user->retrieve($options);
```

##### Update authenticated user (PATCH /user)

Update the authenticated user's profile. Should use basic authentication.

The following arguments are required:


```php
$response = $user->update($options);
```

### Organizations api

Organizations are owned by users and only (s)he can add/remove teams and projects for that organization. A default organization will be created for every user.

```php
$orgs = $client->orgs();
```

##### List Organizations (GET /orgs)

List all organizations the authenticated user is a member of.

```php
$response = $orgs->list($options);
```

##### Retrieve an organization (GET /orgs/:org)

Get the given organization if the authenticated user is a member.

The following arguments are required:

 * __org__: Name of the organization

```php
$response = $orgs->retrieve("big-company", $options);
```

##### Update an organization (PATCH /orgs/:org)

Update the given organization if the authenticated user is the owner. __Email__ is the only thing which can be updated.

The following arguments are required:

 * __org__: Name of the organization
 * __email__: Billing email of the organization

```php
$response = $orgs->update("big-company", "admin@bigcompany.com", $options);
```

### Teams api

Every organization will have a default team named __Owners__. Owner of the organization will be a default member for every team.

The following arguments are required:

 * __org__: Name of the organization

```php
$teams = $client->teams("big-company");
```

##### List Teams (GET /orgs/:org/teams)

List teams of the given organization authenticated user is a member of.

```php
$response = $teams->list($options);
```

##### Create a team (POST /orgs/:org/teams)

Create a team for the given organization. Authenticated user should be the owner of the organization.

The following arguments are required:

 * __name__: Name of the team
 * __description__: Description of the team

```php
$response = $teams->create("Consultants", "Guys who are contractors", $options);
```

##### Retrieve a team (GET /orgs/:org/teams/:team)

Get the given team in the given organization. Access only if the authenticated user is a member of the team.

The following arguments are required:

 * __team__: Name of the team

```php
$response = $teams->retrieve("consultants", $options);
```

##### Update a team (PATCH /orgs/:org/teams/:team)

Update the given team. __Description__ is the only thing which can be updated. Authenticated user should be the owner of the organization.

The following arguments are required:

 * __team__: Name of the team
 * __description__: Description of the team

```php
$response = $teams->update("consultants", "Guys who are contractors", $options);
```

##### Delete a team (DELETE /orgs/:org/teams/:team)

Delete the given team. Cannot delete the default team in the organization. Authenticated user should be the owner of the organization.

The following arguments are required:

 * __team__: Name of the team

```php
$response = $teams->destroy("consultants", $options);
```

##### List projects a team has access to (GET /orgs/:org/teams/:team/projects)

Retrieve the list of projects the given team has access to. Authenticated user should be a member of the team.

The following arguments are required:

 * __team__: Name of the team

```php
$response = $teams->projects("consultants", $options);
```

### Members api

Teams contain a list of users. The Authenticated user should be the owner of the organization.

The following arguments are required:

 * __org__: Name of the organization
 * __team__: Name of the team

```php
$members = $client->members("big-company", "consultants");
```

##### List members (GET /orgs/:org/teams/:team/member)

List all the members in the given team. Authenticated user should be a member of the team or the owner of the org.

```php
$response = $members->list($options);
```

##### Add a member (POST /orgs/:org/teams/:team/member)

Add the user to the given team. The __user__ in the request needs to be a string and be the username of a valid user.  The Authenticated user should be the owner of the organization.

The following arguments are required:

 * __user__: Username of the user

```php
$response = $members->add("johnsmith", $options);
```

##### Remove a member (DELETE /orgs/:org/teams/:team/member)

Remove users from the given team. The __user__ in the request needs to be a string and be the username of a valid user. Cannot delete the default member in a team.  The Authenticated user should be the owner of the organization.

The following arguments are required:

 * __user__: Username of the user

```php
$response = $members->remove("johnsmith", $options);
```

### Projects api

An organization can contain any number of projects.

The following arguments are required:

 * __org__: Name of the organization

```php
$projects = $client->projects("big-company");
```

##### List projects (GET /orgs/:org/projects)

List all the projects of the given organization which can be accessed by the authenticated user.

```php
$response = $projects->list($options);
```

##### Create a project (POST /orgs/:org/projects)

Create a project if the authenticated user is the owner of the given organization. Only the __owners__ team will be able to see the project initially.

The following arguments are required:

 * __name__: Name of the project
 * __description__: Description of the project

```php
$response = $projects->create("Knowledge Base", "Support FAQ & Wiki", $options);
```

##### Retrieve a project (GET /orgs/:org/projects/:project)

Get the given project in the given organization. Works only if the authenticated user has access to the project.

The following arguments are required:

 * __project__: Name of the project

```php
$response = $projects->retrieve("knowledge-base", $options);
```

##### Update a project (PATCH /orgs/:org/projects/:project)

Update the given project. __Description__ is the only thing which can be updated. Authenticated user should be the owner of the organization.

The following arguments are required:

 * __project__: Name of the project
 * __description__: Description of the project

```php
$response = $projects->update("knowledge-base", "Support FAQ and Wiki", $options);
```

##### Delete a project (DELETE /orgs/:org/projects/:project)

Delete the given project. Authenticated user should be the owner of the organization.

The following arguments are required:

 * __project__: Name of the project

```php
$response = $projects->destroy("knowledge-base", $options);
```

### Access api

List of teams whic have access to the project. Default team __Owners__ will have access to every project. Authenticated user should be the owner of the organization for the below endpoints.

The following arguments are required:

 * __org__: Name of the organization
 * __project__: Name of the project

```php
$access = $client->access("big-company", "knowledge-base");
```

##### List teams (GET /orgs/:org/projects/:project/access)

Retrieve a list of teams which have access to the given project. Authenticated user should be a member of the team.

```php
$response = $access->list($options);
```

##### Add a team (POST /orgs/:org/projects/:project/access)

Give the team access to the given project. The __team__ in the request needs to be a string and should be the name of a valid team. Authenticated user should be the owner of the organization.

The following arguments are required:

 * __team__: Name of the team

```php
$response = $access->add("consultants", $options);
```

##### Remove a team (DELETE /orgs/:org/projects/:project/access)

Remove project access for the given team. The __team__ in the request needs to be a string and should be the name of a valid team. Can't delete default team's access. Authenticated user should be the owner of the organization.

The following arguments are required:

 * __team__: Name of the team

```php
$response = $access->remove("consultants", $options);
```

### Environments api

Every project has a default environment named Production. Each environment has __one__ configuration document which can have many keys and values.

The following arguments are required:

 * __org__: Name of the organization
 * __project__: Name of the project

```php
$envs = $client->envs("big-company", "knowledge-base");
```

##### List all environments (GET /orgs/:org/projects/:project/envs)

List all the environmens of the project. The authenticated user should have access to the project.

```php
$response = $envs->list($options);
```

##### Create an environment (POST /orgs/:org/projects/:project/envs)

Create an environment. The authenticated user should have access to the project.

The following arguments are required:

 * __name__: Name of the environment
 * __description__: Description of the environment

```php
$response = $envs->create("QA", "Quality assurance guys server", $options);
```

##### Retrieve an environment (GET /orgs/:org/projects/:project/envs/:env)

Get the given environment in the given project. The authenticated user should have access to the project.

The following arguments are required:

 * __env__: Name of the environment

```php
$response = $envs->retrieve("qa", $options);
```

##### Update an environment (PATCH /orgs/:org/projects/:project/envs/:env)

Update the given environment. __Description__ is the only thing which can be updated. Authenticated user should have access to the project.

The following arguments are required:

 * __env__: Name of the environment
 * __description__: Description of the environment

```php
$response = $envs->update("qa", "Testing server for QA guys", $options);
```

##### Delete an environment (DELETE /orgs/:org/projects/:project/envs/:env)

Delete the given environment. Authenticated user should have access to the project. Cannot delete the default environment.

The following arguments are required:

 * __env__: Name of the environment

```php
$response = $envs->destroy("knowledge-base", $options);
```

### Configuration api

Any member of the team which has access to the project can retrieve any of it's environment's configuration document or edit it.

The following arguments are required:

 * __org__: Name of the organization
 * __project__: Name of the project
 * __env__: Name of the environment

```php
$config = $client->config("big-company", "knowledge-base", "production");
```

##### Retrieve a config (GET /orgs/:org/projects/:project/envs/:env/config)

Get an environment configuration

```php
$response = $config->retrieve($options);
```

##### Update the configuration (PATCH /orgs/:org/projects/:project/envs/:env/config)

Update the configuration document for the given environment of the project. We will patch the document recursively.

The following arguments are required:

 * __config__: Configuration to update

```php
$response = $config->update(array(
    'database' => array(
        'port' => 6984
    ),
    'random' => "wow"
), $options);
```

##### Retrieve config versions (GET /orgs/:org/projects/:project/envs/:env/versions)

List the last 10 versions of the environment configuration

```php
$response = $config->versions($options);
```

## Contributors
Here is a list of [Contributors](https://github.com/confyio/confy-php/contributors)

### TODO

## License
BSD

## Bug Reports
Report [here](https://github.com/confyio/confy-php/issues).

## Contact
Pavan Kumar Sunkara (pavan.sss1991@gmail.com)
