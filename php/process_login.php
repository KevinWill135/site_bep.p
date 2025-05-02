<?php

    session_start();
    header('Content-Type: application/json');

    $pdo = new PDO('mysql:host=localhost;dbname=site_bep', 'root', 'root');

    json_encode(['success' => false,'message' => 'Email e senha são obrigatórios']);
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(empty($email) || empty($password)) {
            echo json_encode(['success' => false,'message' => 'Email e senha são obrigatórios']);
            exit;
        }

        //verificar se utilizador existe
        $stmt = $pdo->prepare('SELECT id, _password, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['_password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
    
            $redirect = $user['role'] === 'admin' ? 'admin/admin.php' : 'store.php';
            echo json_encode(['success' => true, 'redirect' => $redirect]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Email ou senha inválidos']);
            exit;
        }
    }

?>