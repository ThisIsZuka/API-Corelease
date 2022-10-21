<?php 

class D365Connect {
    CONST Grant_type = 'client_credentials';
    CONST Bearer = 'token';

    function __construct()
    {
        $this->token = "";
        if ($this->testConnection()) {

        }
    }

    private function testConnection() {
        try {
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function connect () {

    }

    public function fire() {

    }
}