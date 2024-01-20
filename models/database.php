<?php
class Database {
    public static $mysql;

    public static function mysql () {
        if (!isset(Database::$mysql)) {
            Database::$mysql = new mysqli("mysql.caesar.elte.hu", "zzz", "iyifbH3E5RecvT7z", "zzz");
        }
        return Database::$mysql;
    }
}