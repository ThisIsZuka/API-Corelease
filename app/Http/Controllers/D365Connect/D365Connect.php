<?php 

namespace App\Http\Controllers\D365Connect;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class D365Connect {
    CONST Grant_type = 'client_credentials';
    CONST Bearer = 'token';

    function __construct()
    {
        $this->token = "";
        $this->loginUri = env("D365_URL");
        $this->url = env("D365_RESOURCE");
        $this->resource = env("D365_RESOURCE");
        $this->app_key = env("D365_CLIENT_ID");
        $this->app_secret = env("D365_CLIENT_SECRET");
        $this->tenant = env("D365_TENANT");
        $this->endpoint = '';

        $this->options = [
            'multipart' => 
              [
                [
                    'name' => 'grant_type', 
                    'contents' => D365Connect::Grant_type,
                ],
                [
                    'name' => 'client_id',
                    'contents' => $this->app_key
                ],
                [
                    'name' => 'client_secret',
                    'contents' => $this->app_secret
                ],
                [
                    'name' => 'resource',
                    'contents' => $this->resource
                ]
              ]
            ];
    }

    public function setEndpoint($endpoint) {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function getEndpoint($endpointName) {
        return $this->resource . '/' . $this->endpoint[$endpointName];
    }

    public function set($type, $value) {
        try {
                switch ($type) {
                case 'loginuri':
                    $this->LoginUri = $value;
                    break;
                case 'url':
                    $this->url = $value;
                    break;
                case 'appkey': 
                    $this->app_key = $value;
                    break;
                case 'secret':
                    $this->app_secret = $value;
                    break;
                case 'source': 
                    $this->source = $value;
                    break;
                case 'tenantID':
                    $this->tenantID = $value;
                    break;
                case 'Token': 
                    $this->token = $value;
                    break;
            }
        return $this;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function setToken($token) {
        $this->set('Token', $token);
        return $this;
    }

    public function getToken() {
        return $this->token;
    }

    public function setLoginUrl($url) {
        $this->set('loginuri', $url);
        return $this;
    }

    public function setSource($source) {
        $this->set('source', $source);
        return $this;
    }

    public function setUrl($url) {
        $this->set('url', $url);
        return $this;
    }

    public function setAppKey($key) {
        $this->set('appkey', $key);
        return $this;
    }

    public function setSecret($secret) {
        $this->set('secret', $secret);
        return $this;
    }

    public function setTenantID($tenant) {
        $this->set('tenantID', $tenant);
        return $this;
    }

    private function testConnection() {
        try {
            return $this->connect()->connect_stauts;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function connect () {
        $client = new Client();

        $this->response = $client->post($this->loginUri . $this->tenant . '/' . $this->endpoint['Oauth2'], $this->options);
        // $this->connect_status = isset($this->conn->access_token) ? true:false;
        
        // if ($this->connect_status) {
        //     $this->token = $this->conn->access_token;
        // }

        return $client->post($this->loginUri . $this->tenant . '/' . $this->endpoint['Oauth2'], $this->options);
    }

    public function fire($url, $content) {
        
    }
}