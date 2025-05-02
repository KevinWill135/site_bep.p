<?php
    session_start();
    $pdo = new PDO('mysql:host=localhost;dbname=site_bep', 'root', 'root');

    $response = ['success'  => false, 'message' => 'Erro desconhecido'];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //receber dados do formulário
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirmPassword']);

        //validar o username
        if(strlen($username) < 3) {
            $response['message'] = 'Username precisa ter pelo menos 3 caracteres';
            echo json_encode($response);
            exit;
        }

        //validar o email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Email inválido';
            echo json_encode($response);
            exit;
        }

        //validar senha
        if(strlen($password) < 8 || !preg_match('/[\W]/', $password)) {
            $response['message'] = 'A senha deve ter pelo menos 8 letras e conter pelo menos um caractere especial';
            echo json_encode($response);
            exit;
        }

        //verificar se a senha confere
        if($password !== $confirmPassword) {
            $response['message'] = 'As senhas não coincidem';
            echo json_encode($response);
            exit;
        }

        //verificar se o username já existe
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        $userExists = $stmt->fetchColumn(0);

        if($userExists) {
            $response['message'] = 'Usuário ou email já existem';
            echo json_encode($response);
            exit;
        }

        //fazer o upload da foto de perfil
        $profilePicPath = null;
        if(!empty($_FILES['profilePic']['name'])) {
            $file = $_FILES['profilePic'];
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir. basename($file['name']);

            //verificando se a pasta uploads para as imagens existe
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            //verificando se a imagem foi movido para o diretório correto
            if(move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $profilePicPath = $uploadFile;
            } else {
                $response['message'] = 'Falha ao fazer o upload da imagem';
                echo json_decode($response);
                exit;
            }
        }

        //criptografia da senha
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        //inserir os dados na base de dados
        $stmt = $pdo->prepare('INSERT INTO users (username, email, _password, profile_pic) VALUES (?,?,?,?)');
        $success = $stmt->execute([$username, $email, $hashedPassword, $profilePicPath]);
        if($success) {
            $response['success'] = true;
            $response['message'] = 'Usuário criado com sucesso';
        } else {
            $response['message'] = 'Falha ao cadastrar o usuário';
        }

        echo json_encode($response);
    }

?>