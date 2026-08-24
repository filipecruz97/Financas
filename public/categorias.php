<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);

    if ($nome !== "") {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute(["name" => $nome]);
    }

    header("Location: login.php?erro=sessao");
    exit;
}

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" href="style.css">
<h2>Categorias</h2>

<form method="POST" action="">
    <input type="text" name="nome" placeholder="Nova categoria">
    <button type="submit">Adicionar</button>
</form>

<ul>
    <?php foreach ($categorias as $c): ?>
        <li><?= htmlspecialchars($c["name"]) ?></li>
    <?php endforeach; ?>
</ul>

<a href="index.php">← Voltar</a>