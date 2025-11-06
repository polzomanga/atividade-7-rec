<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verifica se existe
$stmt = $conn->prepare('SELECT * FROM livros WHERE id_livro = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res->fetch_assoc()) {
    echo 'Livro não encontrado.';
    exit;
}

// Exclui
$stmt = $conn->prepare('DELETE FROM livros WHERE id_livro = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: index.php');
exit;
?>
