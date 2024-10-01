<?php
use App\Database;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

return [
    Database::class => function(){
        return new Database(host: $_ENV['DEV_HOST'] ?? '', 
                            dbname:$_ENV['MYSQL_DATABASE'] ?? '', 
                            dbuser:$_ENV['MYSQL_USER'] ?? '',
                            dbpass:$_ENV['MYSQL_PASSWORD'] ?? '');
                        
    }
];
?>