<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$erro = '';
$autores = $conn->query('SELECT id_autor, nome FROM autores ORDER BY nome');

// Busca livro
$stmt = $conn->prepare('SELECT * FROM livros WHERE id_livro = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$livro = $res->fetch_assoc();
if (!$livro) {
    echo 'Livro não encontrado.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $genero = trim($_POST['genero']);
    $ano_publicacao = (int)$_POST['ano_publicacao'];
    $id_autor = (int)$_POST['id_autor'];
    $ano_atual = date('Y');
    if (!$titulo || !$id_autor) {
        $erro = 'Título e autor são obrigatórios.';
    } elseif ($ano_publicacao <= 1500 || $ano_publicacao > $ano_atual) {
        $erro = 'Ano de publicação deve ser maior que 1500 e menor ou igual ao ano atual.';
    } else {
        $stmt = $conn->prepare('UPDATE livros SET titulo=?, genero=?, ano_publicacao=?, id_autor=? WHERE id_livro=?');
        $stmt->bind_param('ssiii', $titulo, $genero, $ano_publicacao, $id_autor, $id);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Erro ao atualizar livro.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Livro</title>
    <style>
        body { font-family: Arial; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 6px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Editar Livro</h1>
    <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
    <form method="post">
        <label>Título:</label>
        <input type="text" name="titulo" value="<?= htmlspecialchars($livro['titulo']) ?>" required>
        <label>Gênero:</label>
        <input type="text" name="genero" value="<?= htmlspecialchars($livro['genero']) ?>">
        <label>Ano de publicação:</label>
        <input type="number" name="ano_publicacao" min="1501" max="<?= date('Y') ?>" value="<?= $livro['ano_publicacao'] ?>" required>
        <label>Autor:</label>
        <select name="id_autor" required>
            <option value="">Selecione</option>
            <?php foreach ($autores as $a): ?>
                <option value="<?= $a['id_autor'] ?>" <?= $livro['id_autor'] == $a['id_autor'] ? 'selected' : '' ?>><?= htmlspecialchars($a['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Salvar</button>
    </form>
    <p><a href="index.php">Voltar</a></p>
</body>
</html>
