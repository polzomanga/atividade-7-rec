<?php
require_once '../config/db.php';

// Filtros
$filtro_nome = isset($_GET['nome']) ? $_GET['nome'] : '';
$filtro_nacionalidade = isset($_GET['nacionalidade']) ? $_GET['nacionalidade'] : '';
$filtro_ano = isset($_GET['ano_nascimento']) ? $_GET['ano_nascimento'] : '';

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

// Monta a query de filtros
$where = [];
if ($filtro_nome) $where[] = "nome LIKE '%$filtro_nome%'";
if ($filtro_nacionalidade) $where[] = "nacionalidade LIKE '%$filtro_nacionalidade%'";
if ($filtro_ano) $where[] = "ano_nascimento = '$filtro_ano'";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Conta total para paginação
$total = $conn->query("SELECT COUNT(*) as total FROM autores $where_sql")->fetch_assoc()['total'];

// Busca autores
$sql = "SELECT * FROM autores $where_sql LIMIT $limite OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Autores</title>
    <style>
        body { font-family: Arial; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        .filtros { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Autores</h1>
    <div class="filtros">
        <form method="get">
            Nome: <input type="text" name="nome" value="<?= htmlspecialchars($filtro_nome) ?>">
            Nacionalidade: <input type="text" name="nacionalidade" value="<?= htmlspecialchars($filtro_nacionalidade) ?>">
            Ano de nascimento: <input type="number" name="ano_nascimento" value="<?= htmlspecialchars($filtro_ano) ?>">
            <button type="submit">Filtrar</button>
        </form>
        <a href="create.php">Novo Autor</a>
    </div>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Nacionalidade</th>
            <th>Ano de Nascimento</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_autor'] ?></td>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= htmlspecialchars($row['nacionalidade']) ?></td>
            <td><?= $row['ano_nascimento'] ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id_autor'] ?>">Editar</a> |
                <a href="delete.php?id=<?= $row['id_autor'] ?>" onclick="return confirm('Excluir autor?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <div>
        <?php
        $total_paginas = ceil($total / $limite);
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='?pagina=$i&nome=$filtro_nome&nacionalidade=$filtro_nacionalidade&ano_nascimento=$filtro_ano'>[$i]</a> ";
        }
        ?>
    </div>
</body>
</html>
