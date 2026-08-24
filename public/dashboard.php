<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "../config/database.php";
require "../src/funcoes.php";
require "../src/transacoes.php";

$transactions = listarTransacoes($pdo);

$totalReceitas = 0;
$totalDespesas = 0;
foreach ($transactions as $t) {
    if ($t["type"] === "income") {
        $totalReceitas += $t["amount"];
    } else {
        $totalDespesas += $t["amount"];
    }
}
$saldo = $totalReceitas - $totalDespesas;

$gastos = gastosPorCategoria($pdo);

// Preparar os dados para o gráfico em formato JSON
$labels = array_column($gastos, "category_name");
$valores = array_column($gastos, "total");
?>

<h1>Dashboard</h1>
<p>Olá, <?= htmlspecialchars($_SESSION["user_name"]) ?>! <a href="logout.php">Sair</a></p>
<p><a href="index.php">Ver todas as transações</a></p>

<h3>Resumo geral</h3>
<p>🟢 Total receitas: <?= formatarMoeda($totalReceitas) ?></p>
<p>🔴 Total despesas: <?= formatarMoeda($totalDespesas) ?></p>
<p><strong>Saldo: <?= formatarMoeda($saldo) ?></strong></p>

<h3>Gastos por categoria</h3>
<div style="max-width: 400px;">
    <canvas id="graficoCategorias"></canvas>
</div>

<h3>Últimas transações</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Tipo</th>
        <th>Data</th>
    </tr>
    <?php foreach (array_slice($transactions, 0, 5) as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t["description"]) ?></td>
            <td><?= formatarMoeda($t["amount"]) ?></td>
            <td><?= $t["type"] === "expense" ? "🔴 Despesa" : "🟢 Receita" ?></td>
            <td><?= date("d/m/Y", strtotime($t["date"])) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('graficoCategorias');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Gastos por categoria',
            data: <?= json_encode($valores) ?>,
        }]
    }
});
</script>