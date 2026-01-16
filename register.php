<?php
require_once __DIR__ . '/functions.php';
$db = getDB();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = 'username and password required';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare('INSERT INTO users (username, password, created_at) VALUES (:u, :p, :c)');
        $stmt->bindValue(':u', $username, SQLITE3_TEXT);
        $stmt->bindValue(':p', $hash, SQLITE3_TEXT);
        $stmt->bindValue(':c', date('c'), SQLITE3_TEXT);
        try {
            $stmt->execute();
            header('Location: login.php');
            exit;
        } catch (Exception $e) {
            $error = 'username already taken';
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Register</title></head><body>
<h2>Register</h2>
<?php if ($error): ?><p style="color:red"><?= sanitize($error) ?></p><?php endif; ?>
<form method="post">
  <label>Username: <input name="username"></label><br>
  <label>Password: <input type="password" name="password"></label><br>
  <button type="submit">Register</button>
</form>
<p><a href="login.php">Login</a></p>
</body></html>
