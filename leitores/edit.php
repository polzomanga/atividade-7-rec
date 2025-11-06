<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$erro = '';

$stmt = $conn->prepare('SELECT * FROM leitores WHERE id_leitor = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$leitor = $res->fetch_assoc();
if (!$leitor) {
    echo 'Leitor não encontrado.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    if (!$nome || !$email) {
        $erro = 'Nome e email são obrigatórios.';
    } else {
        $stmt = $conn->prepare('UPDATE leitores SET nome=?, email=?, telefone=? WHERE id_leitor=?');