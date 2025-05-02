<?php

    session_start();
    require 'db.php';

    $user_id = $_SESSION['user_id'];

    if(!$user_id) {
        header('Location: login.php');
        exit;
    }

    $stmt = $conn->prepare('DELETE FROM order_items WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    if($stmt->execute()) {
        $total = isset($_POST['total']) ? floatval($_POST['total']) : 0.0;
        $fim = $conn->prepare('ALTER TABLE order_items AUTO_INCREMENT = 1');
        $fim->execute();
        //alterando o status depois de compra finalizada
        $completed = 'completed';
        $pending = 'pending';
        $stmt = $conn->prepare('UPDATE orders SET _status = ? WHERE user_id = ? AND _status = ?');
        $stmt->bind_param('sis', $completed, $user_id, $pending);
        $stmt->execute();
        echo "
            Compra finalizada com sucesso!
            Obrigado por comprar conosco!
            Valor total da compra: €" . number_format($total, 2);
    } else {
        echo 'Erro ao finalizar a compra';
    }
    $stmt->close();

?>