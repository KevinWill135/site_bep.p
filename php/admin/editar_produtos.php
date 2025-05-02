<?php

    session_start();
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=site_bep', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro de conexão ' . $e->getMessage()]);
        exit;
    }
    //restringindo acesso
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../login.php');
        exit();
    }

    $response = ['success' => false, 'message' => '', 'resultado' => ''];
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
        $code = trim($_POST['code']);
        $comando = strtolower(strtok($code, " \n"));

        //permitindo apenas algumas queries
        if(!in_array($comando, ['select', 'insert', 'update'])) {
            $response['message'] = 'Apenas SELECT, INSERT ou UPDATE são permitidos';
            echo json_encode($response);
            exit;
        }

        // bloqueando múltiplas queries
        if (preg_match('/;\s*$/', $code)) {
            $response['message'] = 'Múltiplas queries não são permitidas.';
            echo json_encode($response);
            exit;
        }

        try {
            $stmt = $pdo->prepare($code);
            $stmt->execute();

            if($comando === 'select') {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $response['success'] = true;
                $response['resultado'] = print_r($data, true);
            } else {
                $response['success'] = true;
                $response['resultado'] = 'Query executada com sucesso. Linhas afetadas: ' . $stmt->rowCount();
            }
        } catch(PDOException $e) {
            $response['message'] = 'Erro na execução: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Requisição inválida';
    }

    echo json_encode($response);
    exit;

?>