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

    //adicionar ao carrinho
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);

        //verificar se o produto ja existe no carrinho
        $stmt = $conn->prepare('SELECT quantity FROM order_items WHERE user_id = ? AND product_id = ?');
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        //verificando se ja existe o pedido na tabela orders
        
        //se ja existe, apenas aumenta a quantidade
        if($row = $result->fetch_assoc()) {
            $new_quantity = $row['quantity'] + 1;
            $stmt = $conn->prepare('UPDATE order_items SET quantity = ? WHERE user_id = ? AND product_id = ?');
            $stmt->bind_param('iii', $new_quantity, $user_id, $product_id);
            $stmt->execute();
            echo json_encode(['success' => 'Quantidade atualizada com sucesso']);
        } else {
            $stmt = $conn->prepare('INSERT INTO order_items(user_id, product_id, quantity) VALUES (?, ?, ?)');
            $quantity = 1;
            $stmt->bind_param('iii', $user_id, $product_id, $quantity);
            if($stmt->execute())  {
                $tb_orders = $conn->prepare('SELECT id FROM orders WHERE user_id = ? AND _status = ?');
                $status = 'pending';
                $tb_orders->bind_param('is', $user_id, $status);
                $tb_orders->execute();
                $orders_result = $tb_orders->get_result();
                if($row = $orders_result->fetch_assoc()) {
                    $order_id = $row['id'];
                    echo json_encode(['success' => 'Já existe um pedido pendente']);
                } else {
                    //adiciona o pedido em orders
                    $order = $conn->prepare('INSERT INTO orders(user_id) VALUES (?)');
                    $order->bind_param('i', $user_id);
                    $order->execute();
                }
                echo json_encode(['success' => 'Produto adicionado ao carrinho']);
            } else {
                echo json_encode(['error' => 'Erro ao adicionar o produto ao carrinho']);
            }
        }
        exit;
    }

    // query dos produtos para o carrinho
    $query = $conn->prepare('SELECT o.product_id, o.quantity, p.name, p.price, p.image FROM order_items o JOIN products p ON o.product_id = p.id WHERE o.user_id = ?');
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();

    $cart_items = [];
    while($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }

    error_log(json_encode($cart_items));
    echo json_encode($cart_items);


?>