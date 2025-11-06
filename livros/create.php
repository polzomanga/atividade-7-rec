<?php
require_once '../config/db.php';

$erro = '';
$autores = $conn->query('SELECT id_autor, nome FROM autores ORDER BY nome');

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
        $stmt = $conn->prepare('INSERT INTO livros (titulo, genero, ano_publicacao, id_autor) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssii', $titulo, $genero, $ano_publicacao, $id_autor);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Erro ao cadastrar livro.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novo Livro</title>
    <style>
        body { font-family: Arial; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 6px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Novo Livro</h1>
    <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
    <form method="post">
        <label>Título:</label>
        <input type="text" name="titulo" required>
        <label>Gênero:</label>
        <input type="text" name="genero">
        <label>Ano de publicação:</label>
        <input type="number" name="ano_publicacao" min="1501" max="<?= date('Y') ?>" required>
        <label>Autor:</label>
        <select name="id_autor" required>
            <option value="">Selecione</option>
            <?php foreach ($autores as $a): ?>
                <option value="<?= $a['id_autor'] ?>"><?= htmlspecialchars($a['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="index.php">Voltar</a></p>
</body>
</html>
