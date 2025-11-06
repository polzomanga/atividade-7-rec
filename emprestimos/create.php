<?php
require_once '../config/db.php';

$erro = '';
$livros = $conn->query("SELECT l.id_livro, l.titulo FROM livros l WHERE l.id_livro NOT IN (SELECT id_livro FROM emprestimos WHERE data_devolucao IS NULL)");
$leitores = $conn->query("SELECT id_leitor, nome FROM leitores");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_livro = (int)$_POST['id_livro'];
    $id_leitor = (int)$_POST['id_leitor'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'] ? $_POST['data_devolucao'] : null;

    // Regras de negócio
    // Livro só pode ser emprestado se não houver outro ativo
    $livro_ativo = $conn->query("SELECT * FROM emprestimos WHERE id_livro = $id_livro AND data_devolucao IS NULL")->num_rows;
    if ($livro_ativo) {
        $erro = 'Este livro já está emprestado.';
    }
    // Leitor pode ter no máximo 3 empréstimos ativos
    $emprestimos_ativos = $conn->query("SELECT * FROM emprestimos WHERE id_leitor = $id_leitor AND data_devolucao IS NULL")->num_rows;
    if ($emprestimos_ativos >= 3) {
        $erro = 'Este leitor já possui 3 empréstimos ativos.';
    }
    // Data de devolução não pode ser anterior à data de empréstimo
    if ($data_devolucao && $data_devolucao < $data_emprestimo) {
        $erro = 'Data de devolução não pode ser anterior à data de empréstimo.';
    }
    if (!$erro) {
        $stmt = $conn->prepare('INSERT INTO emprestimos (id_livro, id_leitor, data_emprestimo, data_devolucao) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiss', $id_livro, $id_leitor, $data_emprestimo, $data_devolucao);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Erro ao cadastrar empréstimo.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novo Empréstimo</title>
    <style>
        body { font-family: Arial; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 6px; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Novo Empréstimo</h1>
    <?php if ($erro): ?><div class="erro"><?= $erro ?></div><?php endif; ?>
    <form method="post">
        <label>Livro:</label>
        <select name="id_livro" required>
            <option value="">Selecione</option>
            <?php foreach ($livros as $l): ?>
                <option value="<?= $l['id_livro'] ?>"><?= htmlspecialchars($l['titulo']) ?></option>
            <?php endforeach; ?>
        </select>
        <label>Leitor:</label>
        <select name="id_leitor" required>
            <option value="">Selecione</option>
            <?php foreach ($leitores as $le): ?>
                <option value="<?= $le['id_leitor'] ?>"><?= htmlspecialchars($le['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <label>Data Empréstimo:</label>
        <input type="date" name="data_emprestimo" required>
        <label>Data Devolução:</label>
        <input type="date" name="data_devolucao">
        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="index.php">Voltar</a></p>
</body>
</html>
