<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$erro = '';

// Busca autor
$stmt = $conn->prepare('SELECT * FROM autores WHERE id_autor = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$autor = $res->fetch_assoc();
if (!$autor) {
    echo 'Autor não encontrado.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $nacionalidade = trim($_POST['nacionalidade']);
    $ano_nascimento = (int)$_POST['ano_nascimento'];
    if (!$nome) {
        $erro = 'Nome é obrigatório.';
    } else {
        $stmt = $conn->prepare('UPDATE autores SET nome=?, nacionalidade=?, ano_nascimento=? WHERE id_autor=?');
        $stmt->bind_param('ssii', $nome, $nacionalidade, $ano_nascimento, $id);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Erro ao atualizar autor.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Autor</title>
    <style>
        body { font-family: Arial; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 6px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Editar Autor</h1>
    <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
    <form method="post">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($autor['nome']) ?>" required>
        <label>Nacionalidade:</label>
        <input type="text" name="nacionalidade" value="<?= htmlspecialchars($autor['nacionalidade']) ?>">
        <label>Ano de nascimento:</label>
        <input type="number" name="ano_nascimento" min="1000" max="<?= date('Y') ?>" value="<?= $autor['ano_nascimento'] ?>">
        <button type="submit">Salvar</button>
    </form>
    <p><a href="index.php">Voltar</a></p>
</body>
</html>
