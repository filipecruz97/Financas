<?php
require "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    if ($nome === "" || $email === "" || $senha === "") {
        $erro = "Preencha todos os campos.";
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([
            "name" => $nome,
            "email" => $email,
            "password" => $hash
        ]);

        echo "Usuário cadastrado com sucesso! <a href='login.php'>Fazer login</a>";
        exit;
    }
}
?>

<h2>Cadastro</h2>

<?php if (isset($erro)): ?>
    <p style="color:red"><?= $erro ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Nome:</label>
    <input type="text" name="nome">
    <br>
    <label>Email:</label>
    <input type="email" name="email">
    <br>
    <label>Senha:</label>
    <input type="password" name="senha">
    <br>
    <button type="submit">Cadastrar</button>
</form>