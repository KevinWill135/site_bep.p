<?php

session_start();
include '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

//remover e editar usuários
$action = isset($_POST['action']) ? $_POST['action'] : '';
$response = ['success' => false, 'message' => 'Acao invalida'];
if ($action == 'remover_usuario' || $action == 'remover_produto') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if ($id) {
        $table = ($action == 'remover_usuario') ? 'users' : 'products';
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $response = array('success' => true, 'message' => 'Removido com sucesso');
        } else {
            $response = array('success' => false, 'message' => 'Erro ao remover');
        }
    }
} elseif ($action == 'editar_usuario') {
    $user_id = isset($_POST['id']) ? $_POST['id'] : null; 
    $role = isset($_POST['newType']) ? $_POST['newType'] : '';

    if ($user_id && ($role == 'admin' || $role == 'user')) {
        $stmt = $conn->prepare('UPDATE users SET role = ? WHERE id = ?');
        $stmt->bind_param('si', $role, $user_id);
        if ($stmt->execute()) {
            $response = array('success' => true, 'message' => 'Usuario atualizado com sucesso');
        } else {
            $response = array('success' => false, 'message' => "Erro ao atualizar usuario");
        }
    } else {
        $response = array('success' => false, 'message' => 'ID de usuario ou tipo invalido');
    }
}

echo json_encode($response);
exit;




?>