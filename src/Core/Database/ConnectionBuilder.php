<?php
namespace Paw\Core\Database;

use PDO;
use PDOException;
use Paw\Core\Config;
use Paw\Core\Traits\Loggable;

class ConnectionBuilder
{
	use Loggable;
	
	public function make(Config $config): PDO
	{
		try{
            $adapter = $config -> get('DB_ADAPTER');
            $hostname = $config -> get('DB_HOSTNAME');
            $dbname = $config -> get('DB_DBNAME');
            $port = $config -> get('DB_PORT');
            $charset = $config -> get('DB_CHARSET');

            if (strpos($hostname, '/') === 0) {
                // Cloud Run (Unix Socket) connection
                $dsn = "{$adapter}:unix_socket={$hostname};dbname={$dbname};charset={$charset}";
            } else {
                // Standard TCP connection
                $dsn = "{$adapter}:host={$hostname};dbname={$dbname};port={$port};charset={$charset}";
            }

            return new PDO(
                $dsn,
                $config -> get('DB_USERNAME'),
                $config -> get('DB_PASSWORD'),
                [
                    'options' => [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]
                ]
            );
		}catch(PDOException $e){
			$this->logger->error('Internal Server Error' , ["Error" => $e]);
            die("Error Interno - Consulte al administrador");
        }
	}
}
