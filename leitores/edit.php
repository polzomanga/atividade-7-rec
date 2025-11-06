<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$erro = '';

$stmt = $conn->prepare('SELECT * FROM leitores WHERE id_leitor = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$leitor = $res->fetch_assoc();
if (!$leitor) {
    echo 'Leitor não encontrado.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    if (!$nome || !$email) {
        $erro = 'Nome e email são obrigatórios.';
    } else {
        $stmt = $conn->prepare('UPDATE leitores SET nome=?, email=?, telefone=? WHERE id_leitor=?');
        $stmt->bind_param('sssi', $nome, $email, $telefone, $id);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Erro ao atualizar leitor. Email já existe?';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Leitor</title>
    <style>
        body { font-family: Arial; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 6px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Editar Leitor</h1>
    <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
    <form method="post">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($leitor['nome']) ?>" required>
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($leitor['email']) ?>" required>
        <label>Telefone:</label>
        <input type="text" name="telefone" value="<?= htmlspecialchars($leitor['telefone']) ?>">
        <button type="submit">Salvar</button>
    </form>
    <p><a href="index.php">Voltar</a></p>
</body>
</html>
