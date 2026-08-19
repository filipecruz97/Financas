<?php
require "../config/database.php";
require "../src/funcoes.php";

$id = $_GET["id"] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

// Se o formulário foi enviado, atualiza o registro
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descricao = trim($_POST["descricao"]);
    $valor = $_POST["valor"];
    $tipo = $_POST["tipo"];

    if ($descricao === "" || $valor === "" || !is_numeric($valor)) {
        $erro = "Preencha todos os campos corretamente.";
    } else {
        $stmt = $pdo->prepare("UPDATE transactions SET description = :description, amount = :amount, type = :type WHERE id = :id");
        $stmt->execute([
            "description" => $descricao,
            "amount" => $valor,
            "type" => $tipo,
            "id" => $id
        ]);

        header("Location: index.php");
        exit;
    }
}

// Busca os dados atuais da transação, pra preencher o formulário
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = :id");
$stmt->execute(["id" => $id]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    header("Location: index.php");
    exit;
}
?>

<h2>Editar transação</h2>

<?php if (isset($erro)): ?>
    <p style="color:red"><?= $erro ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Descrição:</label>
    <input type="text" name="descricao" value="<?= htmlspecialchars($transaction["description"]) ?>">
    <br>
    <label>Valor:</label>
    <input type="number" step="0.01" name="valor" value="<?= $transaction["amount"] ?>">
    <br>
    <label>Tipo:</label>
    <select name="tipo">
        <option value="expense" <?= $transaction["type"] === "expense" ? "selected" : "" ?>>Despesa</option>
        <option value="income" <?= $transaction["type"] === "income" ? "selected" : "" ?>>Receita</option>
    </select>
    <br>
    <button type="submit">Atualizar</button>
</form>

<a href="index.php">← Voltar</a>