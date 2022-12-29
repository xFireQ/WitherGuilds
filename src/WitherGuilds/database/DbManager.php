<?php

namespace WitherGuilds\database;

use WitherGuilds\Main;

class DbManager {

    private static \SQLite3 $SQLite3;

    public static function init(): void {
        self::$SQLite3 = new \SQLite3(Main::getInstance()->getDataFolder()."DataBase.db");

        self::$SQLite3->exec("CREATE TABLE IF NOT EXISTS 'users' (name, xuid)");

        $sql1 = "CREATE TABLE IF NOT EXISTS 'gMembers' (member TEXT, guild TEXT, rank TEXT)";
        $sql2 = "CREATE TABLE IF NOT EXISTS 'guilds' (tag TEXT, name TEXT, leader TEXT, x1 INT, z1 INT, x2 INT, z2 INT, heartX INT, heartZ INT, baseX INT, baseY INT, baseZ INT, currentSize INT, PVP TEXT, hp INT, eDate TEXT, cDate TEXT)";

        self::$SQLite3->exec($sql1);
        self::$SQLite3->exec($sql2);
    }

    /**
     * @return \SQLite3
     */
    public static function getDb(): \SQLite3
    {
        return self::$SQLite3;
    }
}