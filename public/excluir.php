<?php
require "../config/database.php";
require "../src/transacoes.php";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = $_GET["id"];
    excluirTransacao($pdo, $id);
}

header("Location: index.php");
exit;