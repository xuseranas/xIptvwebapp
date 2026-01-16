<?php
// Simple SQLite connection helper

function getDB() {
    static $db = null;
    if ($db === null) {
        $path = __DIR__ . '/data/database.sqlite';
        $db = new SQLite3($path);
        $db->enableExceptions(true);
        $db->busyTimeout(5000);
        $db->exec('PRAGMA foreign_keys = ON;');
    }
    return $db;
}
