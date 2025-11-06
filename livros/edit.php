<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$erro = '';
$autores = $conn->query('SELECT id_autor, nome FROM autores ORDER BY nome');

// Busca livro
$stmt = $conn->prepare('SELECT * FROM livros WHERE id_livro = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$livro = $res->fetch_assoc();
if (!$livro) {
    echo 'Livro não encontrado.';
    exit;