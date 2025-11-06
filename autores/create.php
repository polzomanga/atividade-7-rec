<?php
require_once '../config/db.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $nacionalidade = trim($_POST['nacionalidade']);
    $ano_nascimento = (int)$_POST['ano_nascimento'];
    if (!$nome) {
        $erro = 'Nome é obrigatório.';
    } else {
        $stmt = $conn->prepare('INSERT INTO autores (nome, nacionalidade, ano_nascimento) VALUES (?, ?, ?)');
        $stmt->bind_param('ssi', $nome, $nacionalidade, $ano_nascimento);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Erro ao cadastrar autor.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novo Autor</title>
    <style>
        body { font-family: Arial; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 6px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Novo Autor</h1>
    <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
    <form method="post">
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>Nacionalidade:</label>
        <input type="text" name="nacionalidade">
        <label>Ano de nascimento:</label>
        <input type="number" name="ano_nascimento" min="1000" max="<?= date('Y') ?>">
        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="index.php">Voltar</a></p>
</body>
</html>
