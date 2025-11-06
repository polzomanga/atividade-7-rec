<?php
require_once '../config/db.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    if (!$nome || !$email) {
        $erro = 'Nome e email são obrigatórios.';
    } else {
        $stmt = $conn->prepare('INSERT INTO leitores (nome, email, telefone) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $nome, $email, $telefone);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Erro ao cadastrar leitor. Email já existe?';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novo Leitor</title>
    <style>
        body { font-family: Arial; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 6px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Novo Leitor</h1>
    <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
    <form method="post">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Telefone:</label>
        <input type="text" name="telefone">
        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="index.php">Voltar</a></p>
</body>
</html>
