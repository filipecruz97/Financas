<?php

function listarTransacoes($pdo) {
    $stmt = $pdo->query("
        SELECT transactions.*, categories.name AS category_name
        FROM transactions
        LEFT JOIN categories ON transactions.category_id = categories.id
        ORDER BY transactions.date DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}

function criarTransacao($pdo, $descricao, $valor, $tipo, $categoria_id, $usuario_id) {
    $stmt = $pdo->prepare("INSERT INTO transactions (description, amount, type, date, category_id, user_id) VALUES (:description, :amount, :type, :date, :category_id, :user_id)");
    $stmt->execute([
        "description" => $descricao,
        "amount" => $valor,
        "type" => $tipo,
        "date" => date("Y-m-d"),
        "category_id" => $categoria_id,
        "user_id" => $usuario_id
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

function listarTransacoesFiltradas($pdo, $dataInicio, $dataFim, $categoriaId, $userId) {
    $sql = "
        SELECT transactions.*, categories.name AS category_name, users.name AS user_name
        FROM transactions
        LEFT JOIN categories ON transactions.category_id = categories.id
        LEFT JOIN users ON transactions.user_id = users.id
        WHERE 1=1
    ";
    $params = [];

    if (!empty($dataInicio)) {
        $sql .= " AND transactions.date >= :data_inicio";
        $params["data_inicio"] = $dataInicio;
    }

    if (!empty($dataFim)) {
        $sql .= " AND transactions.date <= :data_fim";
        $params["data_fim"] = $dataFim;
    }

    if (!empty($categoriaId)) {
        $sql .= " AND transactions.category_id = :categoria_id";
        $params["categoria_id"] = $categoriaId;
    }

    if (!empty($userId)) {
        $sql .= " AND transactions.user_id = :user_id";
        $params["user_id"] = $userId;
    }

    $sql .= " ORDER BY transactions.date DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function gastosPorCategoria($pdo, $userId = null) {
    $sql = "
        SELECT categories.name AS category_name, SUM(transactions.amount) AS total
        FROM transactions
        LEFT JOIN categories ON transactions.category_id = categories.id
        WHERE transactions.type = 'expense'
        AND transactions.category_id IS NOT NULL
    ";
    $params = [];

    if (!empty($userId)) {
        $sql .= " AND transactions.user_id = :user_id";
        $params["user_id"] = $userId;
    }

    $sql .= " GROUP BY categories.name";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}