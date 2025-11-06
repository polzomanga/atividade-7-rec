<?php
require_once '../config/db.php';

$id_leitor = isset($_GET['id_leitor']) ? (int)$_GET['id_leitor'] : 0;

// Busca leitor
$stmt = $conn->prepare('SELECT nome FROM leitores WHERE id_leitor = ?');
$stmt->bind_param('i', $id_leitor);
$stmt->execute();
$res = $stmt->get_result();
$leitor = $res->fetch_assoc();
if (!$leitor) {
    echo 'Leitor não encontrado.';
    exit;
}

// Busca livros emprestados
$sql = "SELECT l.titulo, e.data_emprestimo, e.data_devolucao FROM emprestimos e JOIN livros l ON e.id_livro = l.id_livro WHERE e.id_leitor = $id_leitor";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Livros emprestados a <?= htmlspecialchars($leitor['nome']) ?></title>
    <style>
        body { font-family: Arial; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Livros emprestados a <?= htmlspecialchars($leitor['nome']) ?></h1>
    <table>
        <tr>
            <th>Título</th>
            <th>Data Empréstimo</th>
            <th>Data Devolução</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['titulo']) ?></td>
            <td><?= $row['data_emprestimo'] ?></td>
            <td><?= $row['data_devolucao'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="../leitores/index.php">Voltar para leitores</a></p>
</body>
</html>
