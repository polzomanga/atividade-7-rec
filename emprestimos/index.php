<?php
require_once '../config/db.php';

// Filtros
$status = isset($_GET['status']) ? $_GET['status'] : 'ativos';

$where = $status === 'ativos' ? 'WHERE data_devolucao IS NULL' : 'WHERE data_devolucao IS NOT NULL';
$sql = "SELECT e.*, l.titulo, le.nome as leitor_nome FROM emprestimos e 
        JOIN livros l ON e.id_livro = l.id_livro 
        JOIN leitores le ON e.id_leitor = le.id_leitor 
        $where ORDER BY e.data_emprestimo DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Empréstimos</title>
    <style>
        body { font-family: Arial; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Empréstimos <?= $status === 'ativos' ? 'Ativos' : 'Concluídos' ?></h1>
    <a href="?status=ativos">Ativos</a> | <a href="?status=concluidos">Concluídos</a> | <a href="create.php">Novo Empréstimo</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Livro</th>
            <th>Leitor</th>
            <th>Data Empréstimo</th>
            <th>Data Devolução</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_emprestimo'] ?></td>
            <td><?= htmlspecialchars($row['titulo']) ?></td>
            <td><?= htmlspecialchars($row['leitor_nome']) ?></td>
            <td><?= $row['data_emprestimo'] ?></td>
            <td><?= $row['data_devolucao'] ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id_emprestimo'] ?>">Editar</a> |
                <a href="delete.php?id=<?= $row['id_emprestimo'] ?>" onclick="return confirm('Excluir empréstimo?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
