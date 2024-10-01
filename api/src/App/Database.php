<?php

namespace App;

use PDO;

class Database
{
    public function __construct(private string $host,
                                private string $dbname,
                                private string $dbuser,
                                private string $dbpass,
                                ){}
                                
    public function getConnection(): PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=utf8";
        $pdo = new PDO($dsn, $this->dbuser, $this->dbpass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $pdo;
    }
}
