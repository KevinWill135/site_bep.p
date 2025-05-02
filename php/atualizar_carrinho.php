<?php

    session_start();
    include 'db.php';

    $user_id = $_SESSION['user_id'];
    if(!isset($user_id)) {
        header('Location: login.php');
        exit;
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    //atualizando quantity no carrinho
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($product_id, $quantity)) {
        $product_id = intval($product_id);
        $quantity = intval($quantity);

        if($quantity <= 0) {
            $stmt = $conn->prepare('DELETE FROM order_items WHERE user_id = ? AND product_id = ?');
            $stmt->bind_param('ii', $user_id, $product_id);
            $stmt->execute();
            echo json_encode(['success' => 'Produto removido do carrinho']);
        } else {
            $stmt = $conn->prepare('UPDATE order_items SET quantity = ? WHERE user_id = ? AND product_id = ?');
            $stmt->bind_param('iii', $quantity, $user_id, $product_id);
            $stmt->execute();
            echo json_encode(['success' => 'Quantidade atualizada com sucesso']);
        }
        exit;
    }

    //carregar produtos do carrinho
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $conn->prepare('SELECT o.product_id, o.quantity, p.name, p.price, p.image FROM order_items o JOIN products p ON o.product_id = p.id WHERE o.user_id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_items = [];
        while($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
        echo json_encode($cart_items);
        exit;
    }

    //removendo do carrinho
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($product_id)) {
        $product_id = intval($product_id);
        $stmt = $conn->prepare('DELETE FROM order_items WHERE user_id = ? AND product_id = ?');
        $stmt->bind_param('ii', $user_id, $product_id);
        if($stmt->execute()) {
            echo json_encode(['success' => 'Produto removido do carrinho']);
        } else {
            echo json_encode(['error' => 'Erro ao remover o produto do carrinho']);
        }
        exit;
    }
?>