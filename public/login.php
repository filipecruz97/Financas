<?php
require "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user["password"])) {
        echo "Login válido! Bem-vindo, " . htmlspecialchars($user["name"]);
    } else {
        $erro = "Email ou senha incorretos.";
    }
}
?>

<h2>Login</h2>

<?php if (isset($erro)): ?>
    <p style="color:red"><?= $erro ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Email:</label>
    <input type="email" name="email">
    <br>
    <label>Senha:</label>
    <input type="password" name="senha">
    <br>
    <button type="submit">Entrar</button>
</form>