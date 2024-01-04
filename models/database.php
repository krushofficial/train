<?php
class Database {
    public static $mysql;

    public static function mysql () {
        if (!isset(Database::$mysql)) {
            Database::$mysql = new mysqli("host", "username", "password", "db");
        }
        return Database::$mysql;
    }
}