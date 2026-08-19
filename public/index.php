<?php 

require "../config/database.php";
require "../src/funcoes.php";
?>

<form method="POST" action="">
    <label> Descrição: </label>
    <input type="text" name="descricao">
    <br>
    <label> Valor: </label>
    <input type="number" step="0.01" name="valor">
    <br>
    <label> Tipo: </label>
    <select name="tipo">
        <option value="expense"> Despesa </option>
        <option value="income"> Receita </option>
    </select>
    <br>
    <button type="submit"> Salvar </button>
</form>

<?php 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descricao = trim($_POST["descricao"]);
    $valor = $_POST["valor"];
    $tipo = $_POST["tipo"];

    if ($descricao === "" || $valor === "" || !is_numeric($valor)) {
        echo "<p style='color:red'>Preencha todos os campos corretamente.</p>";
    } else {

        $stmt = $pdo->prepare("INSERT INTO transactions (description, amount, type, date, category_id, user_id) VALUES (:description, :amount, :type, :date, NULL, NULL)");
        $stmt->execute([
            "description" => $descricao,
            "amount" => $valor,
            "type" => $tipo,
            "date" => date("Y-m-d")
        ]);

        echo "<hr>";
        echo "Transação salva com sucesso no banco! ID: " . $pdo->lastInsertId(); 
    }
}
?>

<h2>Transações cadastradas</h2>

<?php
$stmt = $pdo->query("SELECT * FROM transactions ORDER BY date DESC");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table border="1" cellpadding="8">
    <tr>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Tipo</th>
        <th>Data</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t["description"]) ?></td>
            <td><?= formatarMoeda($t["amount"]) ?></td>
            <td><?= $t["type"] === "expense" ? "🔴 Despesa" : "🟢 Receita" ?></td>
            <td><?= date("d/m/Y", strtotime($t["date"])) ?></td>
            <td>
                <a href="editar.php?id=<?= $t["id"] ?>">✏️ Editar</a>
                |
                <a href="excluir.php?id=<?= $t["id"] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>