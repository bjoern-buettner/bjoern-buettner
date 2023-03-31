<?php

namespace Me\BjoernBuettner;

use PDO;

class Database
{
    private static ?PDO $database = null;

    public static function get(): PDO
    {
        if (self::$database === null) {
            self::$database = new PDO(
                'mysql:host=' . $_ENV['DATABASE_HOST'] . ';dbname=' . $_ENV['DATABASE_DATABASE'],
                $_ENV['DATABASE_USER'],
                $_ENV['DATABASE_PASSWORD'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]
            );
        }
        return self::$database;
    }
}
