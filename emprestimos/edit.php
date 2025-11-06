<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$erro = '';

$stmt = $conn->prepare('SELECT * FROM emprestimos WHERE id_emprestimo = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$emp = $res->fetch_assoc();
if (!$emp) {
    echo 'Empréstimo não encontrado.';
    exit;
}

$livros = $conn->query('SELECT id_livro, titulo FROM livros');
$leitores = $conn->query('SELECT id_leitor, nome FROM leitores');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_livro = (int)$_POST['id_livro'];
    $id_leitor = (int)$_POST['id_leitor'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'] ? $_POST['data_devolucao'] : null;
    // Data de devolução não pode ser anterior à data de empréstimo
    if ($data_devolucao && $data_devolucao < $data_emprestimo) {
        $erro = 'Data de devolução não pode ser anterior à data de empréstimo.';
    }
    if (!$erro) {
        $stmt = $conn->prepare('UPDATE emprestimos SET id_livro=?, id_leitor=?, data_emprestimo=?, data_devolucao=? WHERE id_emprestimo=?');
        $stmt->bind_param('iissi', $id_livro, $id_leitor, $data_emprestimo, $data_devolucao, $id);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Erro ao atualizar empréstimo.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Empréstimo</title>
    <style>
        body { font-family: Arial; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 6px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Editar Empréstimo</h1>
    <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
    <form method="post">
        <label>Livro:</label>
        <select name="id_livro" required>
            <?php foreach ($livros as $l): ?>
                <option value="<?= $l['id_livro'] ?>" <?= $emp['id_livro'] == $l['id_livro'] ? 'selected' : '' ?>><?= htmlspecialchars($l['titulo']) ?></option>
            <?php endforeach; ?>
        </select>
        <label>Leitor:</label>
        <select name="id_leitor" required>
            <?php foreach ($leitores as $le): ?>
                <option value="<?= $le['id_leitor'] ?>" <?= $emp['id_leitor'] == $le['id_leitor'] ? 'selected' : '' ?>><?= htmlspecialchars($le['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <label>Data Empréstimo:</label>
        <input type="date" name="data_emprestimo" value="<?= $emp['data_emprestimo'] ?>" required>
        <label>Data Devolução:</label>
        <input type="date" name="data_devolucao" value="<?= $emp['data_devolucao'] ?>">
        <button type="submit">Salvar</button>
    </form>
    <p><a href="index.php">Voltar</a></p>
</body>
</html>
