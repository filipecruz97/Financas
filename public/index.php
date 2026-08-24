<?php 
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "../config/database.php";
require "../src/funcoes.php";
require "../src/transacoes.php";

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtUsuarios = $pdo->query("SELECT * FROM users ORDER BY name");
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);
?>

<p>Olá, <?= htmlspecialchars($_SESSION["user_name"]) ?>! <a href="logout.php">Sair</a></p>

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
    <label>Categoria:</label>
    <select name="categoria_id">
        <?php foreach ($categorias as $c): ?>
            <option value="<?= $c["id"] ?>"><?= htmlspecialchars($c["name"]) ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <button type="submit"> Salvar </button>
</form>

<?php 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descricao = trim($_POST["descricao"]);
    $valor = $_POST["valor"];
    $tipo = $_POST["tipo"];
    $categoria_id = $_POST["categoria_id"];

    if ($descricao === "" || $valor === "" || !is_numeric($valor)) {
        echo "<p style='color:red'>Preencha todos os campos corretamente.</p>";
    } else {

        $novoId = criarTransacao($pdo, $descricao, $valor, $tipo, $categoria_id, $_SESSION["user_id"]);

        echo "<hr>";
        echo "Transação salva com sucesso no banco! ID: " . $novoId;
    }
}
?>

<h3>Filtrar transações</h3>
<form method="GET" action="">
    <label>De:</label>
    <input type="date" name="data_inicio" value="<?= $_GET['data_inicio'] ?? '' ?>">

    <label>Até:</label>
    <input type="date" name="data_fim" value="<?= $_GET['data_fim'] ?? '' ?>">

    <label>Categoria:</label>
    <select name="categoria_id">
        <option value="">Todas</option>
        <?php foreach ($categorias as $c): ?>
            <option value="<?= $c["id"] ?>" <?= (isset($_GET['categoria_id']) && $_GET['categoria_id'] == $c["id"]) ? "selected" : "" ?>>
                <?= htmlspecialchars($c["name"]) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Quem lançou:</label>
    <select name="user_id">
        <option value="">Todos</option>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= $u["id"] ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $u["id"]) ? "selected" : "" ?>>
                <?= htmlspecialchars($u["name"]) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Filtrar</button>
    <a href="index.php">Limpar filtro</a>
</form>

<?php
$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';
$categoriaFiltro = $_GET['categoria_id'] ?? '';
$userFiltro = $_GET['user_id'] ?? '';

$transactions = listarTransacoesFiltradas($pdo, $dataInicio, $dataFim, $categoriaFiltro, $userFiltro);

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
?>

<h3>Resumo</h3>
<p>🟢 Total receitas: <?= formatarMoeda($totalReceitas) ?></p>
<p>🔴 Total despesas: <?= formatarMoeda($totalDespesas) ?></p>
<p><strong>Saldo: <?= formatarMoeda($saldo) ?></strong></p>

<h2>Transações cadastradas</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Tipo</th>
        <th>Categoria</th>
        <th>Lançado por</th>
        <th>Data</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t["description"]) ?></td>
            <td><?= formatarMoeda($t["amount"]) ?></td>
            <td><?= $t["type"] === "expense" ? "🔴 Despesa" : "🟢 Receita" ?></td>
            <td><?= htmlspecialchars($t["category_name"] ?? "-") ?></td>
            <td><?= htmlspecialchars($t["user_name"] ?? "-") ?></td>
            <td><?= date("d/m/Y", strtotime($t["date"])) ?></td>
            <td>
                <a href="editar.php?id=<?= $t["id"] ?>">✏️ Editar</a>
                |
                <a href="excluir.php?id=<?= $t["id"] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>