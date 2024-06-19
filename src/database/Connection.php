<?php

namespace Websocket\App\database;

use PDO;

class Connection
{
    private const OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    private static ?PDO $pdo = null;
    private function __construct()
    {
    }

    public static function getInstance(): PDO
    {
        if(is_null(self::$pdo)) {
            try {
                self::$pdo = new \PDO(
                    "mysql:host=banco_de_dados_relacional;dbname=laravel",
                    "user",
                    "secret",
                    self::OPTIONS
                );
            } catch (\PDOException $exception) {
                die(var_dump($exception));
            }
        }
        return self::$pdo;
    }
}