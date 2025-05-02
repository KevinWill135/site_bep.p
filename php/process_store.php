<?php

    session_start();
    $pdo = new PDO('mysql:host=localhost;dbname=site_bep', 'root', 'root');

    //lógica para os filtros
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $precos = isset($_POST['precos']) ? $_POST['precos'] : [];

    //construindo a query sql dinamicamente
    $sql = "SELECT * FROM products WHERE 1=1";
    if(!empty($tipo)) {
        $sql .= " AND type = :tipo";
    }

    if(!empty($precos)) {
        $condition = [];
        foreach($precos as $preco) {
            if($preco === 'preco1') {
                $condition[] = 'price < 11';
            } elseif($preco === 'preco2')  {
                $condition[] = 'price BETWEEN 11 AND 25';
            } elseif($preco === 'preco3') {
                $condition[] = 'price BETWEEN 26 AND 50';            
            }
        }
        if(!empty($condition)) {
            $sql .= " AND (" . implode(' OR ', $condition) . ")";
        }
    }

    $stmt = $pdo->prepare($sql);

    if(!empty($tipo)) {
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(count($products) > 0) {
        foreach($products as $product) {
            echo "
                <div class='card border-success text-bg-dark col-sm-3' style='max-width: 18rem; margin: 1.5rem;'>
                    <div class='gallery-item card-img-top mt-2 imagem-item' data-tipo='{$product['type']}'>
                          <img src='{$product['image']}' class='img-fluid' alt='{$product['name']}'>
                    </div>
                    <div class='card-body'>
                        <h4 class='card-title'>{$product['name']}</h4>
                        <p class='card-text descricao'>{$product['description']}</p>
                        <p class='card-text precos'>€{$product['price']}</p>
                        <div>
                            <button type='submit' class='btn btn-success adicionar' data-id=\"{$product['id']}\">
                                Adicionar ao carrinho
                            </button>
                        </div>
                    </div>
              </div>
            ";
        }
    } else {
        echo "<p class='text-danger'>Nenhum produto encontrado</p>";
    }

    //lógica para adicionar ao carrinho
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
        $product_id = intval($_POST['id']);
        $user_id = $_SESSION['user_id'];

        $productQuery = $conn->prepare('SELECT * FROM products WHERE id = ?');
        $productQuery->bind_param('i', $product_id);
        $productQuery->execute();
        $result = $productQuery->get_result();

        //verifica se o produto existe
        if($result->num_rows > 0) {
            //verificar se ja existe o produto na tabela order_items
            $sql_check = 'SELECT quantity FROM order_items WHERE user_id = ? AND product_id = ?';
            $stmt = $conn->prepare($sql_check);
            $stmt->bind_param('ii', $user_id, $product_id);
            $stmt->execute();
            $query_order = $stmt->get_result();
            if($row = $query_order->fetch_assoc()) {
                $new_quantity = $row['quantity'] + 1;
                $stmt = $conn->prepare('UPDATE order_items SET quantity = ? WHERE user_id = ? AND product_id = ?');
                $stmt->bind_param('iii', $new_quantity, $user_id, $product_id);
                $stmt->execute();
                exit;
            }

            if($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $idPDT = $product['id'];
                $quantity = 1;

                //inserir produto na tabela order_items
                $stmt = $conn->prepare('INSERT INTO order_items(user_id, product_id, quantity) VALUES (?, ?, ?)');
                $stmt->bind_param('iii', $user_id, $product_id, $quantity);

                if($stmt->execute()) {
                    //inserindo produto na tabela orders e publicando mensagem de sucesso
                    $stmt = $conn->prepare('INSERT INTO orders(user_id) VALUES (?)');
                    $stmt->bind_param('i', $user_id);
                    $stmt->execute();

                    echo 'Produto adicionado ao carrinho';
                } else {
                    echo 'Erro ao adicionar ao carrinho';
                }
            }
        } else {
            echo 'Produto não econtrado';
        }
        $productQuery->close();
    }

?>