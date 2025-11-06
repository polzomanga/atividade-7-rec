<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare('SELECT * FROM leitores WHERE id_leitor = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res->fetch_assoc()) {
    echo 'Leitor não encontrado.';
    exit;
}

$stmt = $conn->prepare('DELETE FROM leitores WHERE id_leitor = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: index.php');
exit;
?>
