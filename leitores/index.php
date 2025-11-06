<?php
require_once '../config/db.php';

// Filtros
$filtro_nome = isset($_GET['nome']) ? $_GET['nome'] : '';
$filtro_email = isset($_GET['email']) ? $_GET['email'] : '';

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

$where = [];
if ($filtro_nome) $where[] = "nome LIKE '%$filtro_nome%'";
if ($filtro_email) $where[] = "email LIKE '%$filtro_email%'";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total = $conn->query("SELECT COUNT(*) as total FROM leitores $where_sql")->fetch_assoc()['total'];
$sql = "SELECT * FROM leitores $where_sql LIMIT $limite OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Leitores</title>
    <style>
        body { font-family: Arial; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        .filtros { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Leitores</h1>
    <div class="filtros">
        <form method="get">
            Nome: <input type="text" name="nome" value="<?= htmlspecialchars($filtro_nome) ?>">
            Email: <input type="text" name="email" value="<?= htmlspecialchars($filtro_email) ?>">
            <button type="submit">Filtrar</button>
        </form>
        <a href="create.php">Novo Leitor</a>
    </div>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_leitor'] ?></td>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['telefone']) ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id_leitor'] ?>">Editar</a> |
                <a href="delete.php?id=<?= $row['id_leitor'] ?>" onclick="return confirm('Excluir leitor?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <div>
        <?php
        $total_paginas = ceil($total / $limite);
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='?pagina=$i&nome=$filtro_nome&email=$filtro_email'>[$i]</a> ";
        }
        ?>
    </div>
</body>
</html>
