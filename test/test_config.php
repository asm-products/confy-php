<?php

require './lib/Confy/Config.php';

describe('Testing match function', function ($ctx) {

  describe('with full url', function ($ctx) {
    before(function ($ctx) {
      $url = 'https://user:pass@api.confy.io/orgs/org/projects/project/envs/env/config';
      $ctx->result = Confy\Config::match($url);
    });

    it('should return url object', function ($ctx) {
      expect(gettype($ctx->result))->to->eql('array');
      expect($ctx->result['host'])->to->eql('https://api.confy.io');
    });

    it('should have auth info', function ($ctx) {
      expect($ctx->result['user'])->to->eql('user');
      expect($ctx->result['pass'])->to->eql('pass');
    });

    it('should have org info', function ($ctx) {
      expect($ctx->result['org'])->to->eql('org');
    });

    it('should have stage info', function ($ctx) {
      expect($ctx->result['project'])->to->eql('project');
      expect($ctx->result['env'])->to->eql('env');
    });

    it('should not have heroku', function ($ctx) {
      expect($ctx->result['heroku'])->to->eql(false);
    });

    it('should not have token', function ($ctx) {
      expect($ctx->result['token'])->to->be->empty();
    });

    it('should have correct path', function ($ctx) {
      expect($ctx->result['path'])->to->eql('/orgs/org/projects/project/envs/env/config');
    });
  });

  describe('with token url', function ($ctx) {
    before(function ($ctx) {
      $url = 'https://api.confy.io/orgs/org/config/abcdefabcdefabcdefabcdefabcdef1234567890';
      $ctx->result = Confy\Config::match($url);
    });

    it('should return url object', function ($ctx) {
      expect(gettype($ctx->result))->to->eql('array');
      expect($ctx->result['host'])->to->eql('https://api.confy.io');
    });

    it('should not have auth info', function ($ctx) {
      expect($ctx->result['user'])->to->be->empty();
      expect($ctx->result['pass'])->to->be->empty();
    });

    it('should have org info', function ($ctx) {
      expect($ctx->result['org'])->to->eql('org');
    });

    it('should not have stage info', function ($ctx) {
      expect($ctx->result['project'])->to->be->empty();
      expect($ctx->result['env'])->to->be->empty();
    });

    it('should not have heroku', function ($ctx) {
      expect($ctx->result['heroku'])->to->eql(false);
    });

    it('should have token', function ($ctx) {
      expect($ctx->result['token'])->to->eql('abcdefabcdefabcdefabcdefabcdef1234567890');
    });

    it('should have correct path', function ($ctx) {
      expect($ctx->result['path'])->to->eql('/orgs/org/config/abcdefabcdefabcdefabcdefabcdef1234567890');
    });
  });

  describe('with heroku url', function ($ctx) {
    before(function ($ctx) {
      $url = 'https://user:pass@api.confy.io/heroku/config';
      $ctx->result = Confy\Config::match($url);
    });

    it('should return url object', function ($ctx) {
      expect(gettype($ctx->result))->to->eql('array');
      expect($ctx->result['host'])->to->eql('https://api.confy.io');
    });

    it('should have auth info', function ($ctx) {
      expect($ctx->result['user'])->to->eql('user');
      expect($ctx->result['pass'])->to->eql('pass');
    });

    it('should not have org info', function ($ctx) {
      expect($ctx->result['org'])->to->be->empty();
    });

    it('should not have stage info', function ($ctx) {
      expect($ctx->result['project'])->to->be->empty();
      expect($ctx->result['env'])->to->be->empty();
    });

    it('should have heroku', function ($ctx) {
      expect($ctx->result['heroku'])->to->eql(true);
    });

    it('should not have token', function ($ctx) {
      expect($ctx->result['token'])->to->be->empty();
    });

    it('should have correct path', function ($ctx) {
      expect($ctx->result['path'])->to->eql('/heroku/config');
    });
  });

  describe('with non-string and non-object url', function ($ctx) {
    it('should raise error', function ($ctx) {
      expect(function () { Confy\Config::match(8); })->to->throw('\Exception', 'Invalid URL');
    });
  });

  describe('with bad url', function ($ctx) {
    it('should raise error', function ($ctx) {
      expect(function () { Confy\Config::match('http://api.confy.io/projects/config'); })->to->throw('\Exception', 'Invalid URL');
    });
  });

  describe('with empty object', function ($ctx) {
    it('should raise error', function ($ctx) {
      expect(function () { Confy\Config::match(array('user' => 'user', 'pass' => 'pass', 'heroku' => true)); })->to->throw('\Exception', 'Invalid URL');
    });
  });
});
