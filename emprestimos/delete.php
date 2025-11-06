<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare('SELECT * FROM emprestimos WHERE id_emprestimo = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res->fetch_assoc()) {
    echo 'Empréstimo não encontrado.';
    exit;
}

$stmt = $conn->prepare('DELETE FROM emprestimos WHERE id_emprestimo = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: index.php');
exit;
?>
