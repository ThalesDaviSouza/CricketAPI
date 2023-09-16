<?php

use Doctrine\DBAL\DriverManager as DriverManager;

class DB {
    private $queryBuilder;
    private $connection;
    private $connectionParameters;

    public function __construct(Config $config){
        $this->connectionParameters = $config->getDbConfig();

        $this->connection = DriverManager::getConnection($this->connectionParameters);

        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    public function getQueryBuilder(){
        return $this->queryBuilder;
    }

}


