<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?erro=sessao");
    exit;
}

require "../config/database.php";
require "../src/funcoes.php";
require "../src/orcamento.php";

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mesAtual = date("n");
$anoAtual = date("Y");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $categoriaId = $_POST["categoria_id"];
    $valor = $_POST["valor"];

    if (!empty($categoriaId) && is_numeric($valor)) {
        criarOuAtualizarMeta($pdo, $categoriaId, $valor, $mesAtual, $anoAtual);
    }

   header("Location: login.php?erro=sessao");
    exit;
}

$metas = listarMetasComGasto($pdo, $mesAtual, $anoAtual);
?>
<link rel="stylesheet" href="style.css">
<h1>Metas do mês</h1>
<p><a href="dashboard.php">← Voltar ao Dashboard</a></p>

<h3>Definir/atualizar meta</h3>
<form method="POST" action="">
    <label>Categoria:</label>
    <select name="categoria_id">
        <?php foreach ($categorias as $c): ?>
            <option value="<?= $c["id"] ?>"><?= htmlspecialchars($c["name"]) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Valor da meta (mensal):</label>
    <input type="number" step="0.01" name="valor">

    <button type="submit">Salvar meta</button>
</form>

<h3>Comparativo: gasto vs meta (<?= date("m/Y") ?>)</h3>

<table border="1" cellpadding="8">
    <tr>
        <th>Categoria</th>
        <th>Meta</th>
        <th>Gasto atual</th>
        <th>Situação</th>
    </tr>
    <?php foreach ($metas as $m): ?>
        <?php
        $percentual = $m["meta"] > 0 ? ($m["gasto"] / $m["meta"]) * 100 : 0;
        $estourou = $m["gasto"] > $m["meta"];
        ?>
        <tr>
            <td><?= htmlspecialchars($m["category_name"]) ?></td>
            <td><?= formatarMoeda($m["meta"]) ?></td>
            <td><?= formatarMoeda($m["gasto"]) ?></td>
            <td>
                <?php if ($estourou): ?>
                    🔴 Estourou (<?= round($percentual) ?>%)
                <?php else: ?>
                    🟢 Dentro da meta (<?= round($percentual) ?>%)
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>