<?php

namespace WitherGuilds\utils;

class ChatUtil {
    public static function fixColors(string $message): string {
        return str_replace("&", "§", $message);
    }

    public static function format(string $message) : string {
        return self::fixColors("&l&6WitherHC &r&8» &7".$message);
    }
}