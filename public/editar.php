<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?erro=sessao");
    exit;
}


require "../config/database.php";
require "../src/funcoes.php";
require "../src/transacoes.php";

$id = $_GET["id"] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: login.php?erro=sessao");
    exit;
}

// Se o formulário foi enviado, atualiza o registro
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descricao = trim($_POST["descricao"]);
    $valor = $_POST["valor"];
    $tipo = $_POST["tipo"];

   if ($descricao === "" || $valor === "" || !is_numeric($valor) || $valor <= 0) {
        $erro = "Preencha todos os campos corretamente.";
    } else {
      atualizarTransacao($pdo, $id, $descricao, $valor, $tipo);
        header("Location: login.php?erro=sessao");
        exit;
    }
}

// Busca os dados atuais da transação, pra preencher o formulário
$transaction = buscarTransacaoPorId($pdo, $id);

if (!$transaction) {
  header("Location: login.php?erro=sessao");
    exit;
}
?>

<h2>Editar transação</h2>

<?php 

        if (isset($erro)): ?>
    <p style="color:red"><?= $erro ?></p>
<?php endif; ?>


<link rel="stylesheet" href="style.css">
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