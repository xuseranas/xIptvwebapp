<?php
// Run this once to create the sqlite DB and seed an admin user.
// Usage: visit this file in your browser once.

require_once __DIR__ . '/db.php';

$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$dbFile = $dataDir . '/database.sqlite';
if (file_exists($dbFile)) {
    echo "Database already exists at data/database.sqlite. If you want to recreate, delete it first.\n";
    exit;
}

$db = getDB();

try {
    $db->exec('PRAGMA foreign_keys = ON;');

    $db->exec('CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        is_admin INTEGER DEFAULT 0,
        created_at TEXT NOT NULL
    );');

    $db->exec('CREATE TABLE uploads (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        filename TEXT,
        title TEXT,
        content TEXT NOT NULL,
        created_at TEXT NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    );');

    $db->exec('CREATE TABLE ads (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT,
        content TEXT,
        image_url TEXT,
        target_url TEXT,
        active INTEGER DEFAULT 1,
        created_at TEXT NOT NULL
    );');

    // seed admin
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (username, password, is_admin, created_at) VALUES (:u, :p, 1, :c)');
    $stmt->bindValue(':u', $username, SQLITE3_TEXT);
    $stmt->bindValue(':p', $password, SQLITE3_TEXT);
    $stmt->bindValue(':c', date('c'), SQLITE3_TEXT);
    $stmt->execute();

    // sample ad
    $stmt = $db->prepare('INSERT INTO ads (title, content, image_url, target_url, active, created_at) VALUES (:t, :c, :i, :u, 1, :d)');
    $stmt->bindValue(':t', 'Welcome Ad', SQLITE3_TEXT);
    $stmt->bindValue(':c', 'Welcome to the IPTV demo site', SQLITE3_TEXT);
    $stmt->bindValue(':i', '', SQLITE3_TEXT);
    $stmt->bindValue(':u', '#', SQLITE3_TEXT);
    $stmt->bindValue(':d', date('c'), SQLITE3_TEXT);
    $stmt->execute();

    echo "Database created at data/database.sqlite and seeded.\n";
    echo "Admin login: admin / admin123 (change password after first login).\n";
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
