<?php 

require "../src/funcoes.php";
?>

<form method= "POST" action= "">
    <label> Descrição: </label>
    <input type= "text" name= "descricao">
    <br>
    <label> Valor: </label>
    <input type= "number" step= "0.01" name= "valor">
    <br>
    <label> Tipo: </label>
    <select name= "tipo">
        <option value= "Despesa"> Despesa </option>
        <option value= "Receita"> Receita </option>
    </select>
    <br>
    <button type= "submit"> Salvar </button>
</form>


<?php 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descricao = trim($_POST["descricao"]);
    $valor = $_POST["valor"];
    $tipo = $_POST["tipo"];

    if ($descricao === "" || $valor === "" || !is_numeric($valor)) {
        echo "<p style='color:red'>Preencha todos os campos corretamente.</p>";
    } else {
        echo "<hr>";
        echo "Você cadastrou: " . $descricao . " - " . formatarMoeda($valor) . " (" . $tipo . ")";
    }
}