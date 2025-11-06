<?php
require_once '../config/db.php';

// Filtros
$filtro_titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$filtro_genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$filtro_ano = isset($_GET['ano_publicacao']) ? $_GET['ano_publicacao'] : '';
$filtro_autor = isset($_GET['id_autor']) ? $_GET['id_autor'] : '';

// Paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

// Monta a query de filtros
$where = [];
if ($filtro_titulo) $where[] = "l.titulo LIKE '%$filtro_titulo%'";
if ($filtro_genero) $where[] = "l.genero LIKE '%$filtro_genero%'";
if ($filtro_ano) $where[] = "l.ano_publicacao = '$filtro_ano'";
if ($filtro_autor) $where[] = "l.id_autor = '$filtro_autor'";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Conta total para paginação
$total = $conn->query("SELECT COUNT(*) as total FROM livros l $where_sql")->fetch_assoc()['total'];

// Busca autores para filtro
$autores = $conn->query("SELECT id_autor, nome FROM autores ORDER BY nome");

// Busca livros
$sql = "SELECT l.*, a.nome as autor_nome FROM livros l LEFT JOIN autores a ON l.id_autor = a.id_autor $where_sql LIMIT $limite OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Livros</title>
    <style>
        body { font-family: Arial; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        .filtros { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Livros</h1>
    <div class="filtros">
        <form method="get">
            Título: <input type="text" name="titulo" value="<?= htmlspecialchars($filtro_titulo) ?>">
            Gênero: <input type="text" name="genero" value="<?= htmlspecialchars($filtro_genero) ?>">
            Ano: <input type="number" name="ano_publicacao" value="<?= htmlspecialchars($filtro_ano) ?>">
            Autor: <select name="id_autor">
                <option value="">Todos</option>
                <?php while ($a = $autores->fetch_assoc()): ?>
                    <option value="<?= $a['id_autor'] ?>" <?= $filtro_autor == $a['id_autor'] ? 'selected' : '' ?>><?= htmlspecialchars($a['nome']) ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Filtrar</button>
        </form>
        <a href="create.php">Novo Livro</a>
    </div>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Gênero</th>
            <th>Ano</th>
            <th>Autor</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_livro'] ?></td>
            <td><?= htmlspecialchars($row['titulo']) ?></td>
            <td><?= htmlspecialchars($row['genero']) ?></td>
            <td><?= $row['ano_publicacao'] ?></td>
            <td><?= htmlspecialchars($row['autor_nome']) ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id_livro'] ?>">Editar</a> |
                <a href="delete.php?id=<?= $row['id_livro'] ?>" onclick="return confirm('Excluir livro?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <div>
        <?php
        $total_paginas = ceil($total / $limite);
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='?pagina=$i&titulo=$filtro_titulo&genero=$filtro_genero&ano_publicacao=$filtro_ano&id_autor=$filtro_autor'>[$i]</a> ";
        }
        ?>
    </div>
</body>
</html>
