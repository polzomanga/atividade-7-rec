<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verifica se existe
$stmt = $conn->prepare('SELECT * FROM autores WHERE id_autor = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res->fetch_assoc()) {
    echo 'Autor não encontrado.';
    exit;
}

// Exclui
$stmt = $conn->prepare('DELETE FROM autores WHERE id_autor = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: index.php');
exit;
?>
