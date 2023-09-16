<?php

class Config {
    private $dbSettings;
    private $errorSettings;

    public function __construct(){
        # TODO: Change database info por env variables
        # Actually it is only a study about slim
        # But in a near future I'll make some changes like this

        $this->dbSettings = [
            'dbname' => 'slimphp',
            'user' => 'thale',
            'password' => 'password',
            'host' => 'mysql',
            'driver' => 'pdo_mysql'
        ];
    }

    public function getDbConfig(){
        return $this->dbSettings;
    }

}

