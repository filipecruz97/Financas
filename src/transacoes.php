<?php

function listarTransacoes($pdo) {
    $stmt = $pdo->query("SELECT * FROM transactions ORDER BY date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function criarTransacao($pdo, $descricao, $valor, $tipo) {
    $stmt = $pdo->prepare("INSERT INTO transactions (description, amount, type, date, category_id, user_id) VALUES (:description, :amount, :type, :date, NULL, NULL)");
    $stmt->execute([
        "description" => $descricao,
        "amount" => $valor,
        "type" => $tipo,
        "date" => date("Y-m-d")
    ]);
    return $pdo->lastInsertId();
}

function buscarTransacaoPorId($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = :id");
    $stmt->execute(["id" => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizarTransacao($pdo, $id, $descricao, $valor, $tipo) {
    $stmt = $pdo->prepare("UPDATE transactions SET description = :description, amount = :amount, type = :type WHERE id = :id");
    $stmt->execute([
        "description" => $descricao,
        "amount" => $valor,
        "type" => $tipo,
        "id" => $id
    ]);
}

function excluirTransacao($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = :id");
    $stmt->execute(["id" => $id]);
}