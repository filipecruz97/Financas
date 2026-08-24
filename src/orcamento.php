<?php

function criarOuAtualizarMeta($pdo, $categoriaId, $valor, $mes, $ano) {
    // Verifica se já existe meta pra essa categoria/mês/ano
    $stmt = $pdo->prepare("SELECT id FROM budgets WHERE category_id = :category_id AND month = :month AND year = :year");
    $stmt->execute([
        "category_id" => $categoriaId,
        "month" => $mes,
        "year" => $ano
    ]);
    $existente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existente) {
        // Já existe: atualiza o valor
        $stmt = $pdo->prepare("UPDATE budgets SET amount = :amount WHERE id = :id");
        $stmt->execute([
            "amount" => $valor,
            "id" => $existente["id"]
        ]);
    } else {
        // Não existe: cria nova meta
        $stmt = $pdo->prepare("INSERT INTO budgets (category_id, amount, month, year) VALUES (:category_id, :amount, :month, :year)");
        $stmt->execute([
            "category_id" => $categoriaId,
            "amount" => $valor,
            "month" => $mes,
            "year" => $ano
        ]);
    }
}

function listarMetasComGasto($pdo, $mes, $ano) {
    $sql = "
        SELECT 
            categories.id AS category_id,
            categories.name AS category_name,
            budgets.amount AS meta,
            COALESCE(SUM(transactions.amount), 0) AS gasto
        FROM categories
        LEFT JOIN budgets ON budgets.category_id = categories.id AND budgets.month = :month AND budgets.year = :year
        LEFT JOIN transactions ON transactions.category_id = categories.id 
            AND transactions.type = 'expense'
            AND MONTH(transactions.date) = :month
            AND YEAR(transactions.date) = :year
        WHERE budgets.id IS NOT NULL
        GROUP BY categories.id, categories.name, budgets.amount
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(["month" => $mes, "year" => $ano]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}