<?php
require "../config/database.php";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = $_GET["id"];

    $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = :id");
    $stmt->execute(["id" => $id]);
}

header("Location: index.php");
exit;