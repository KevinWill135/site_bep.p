<?php

    session_start();
    include '../db.php';

    if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: ../profile.php');
        exit;
    }

    //lógica search
    $orders = [];
    if(isset($_GET['search'])) {
        $search = $_GET['search'];
        
        $sql = $conn->prepare("SELECT * FROM pending_cart WHERE username LIKE ? OR product LIKE ? OR type LIKE ?");
        $likeSearch = "%$search%";
        $sql->bind_param('sss', $likeSearch, $likeSearch, $likeSearch);
        $sql->execute();
        $result = $sql->get_result();
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        $sql->close();
        
    } else {
        $sql = "SELECT * FROM pending_cart";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    //tabela com a view summary_orders
    $query = $conn->query("SELECT * FROM summary_orders");


?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="description" content="Black Eyed Peas" />
    <meta
      name="keywords"
      content="eyed, peas, Will.i.am, Taboo, Fergie, Apl.de.Ap, hip hop"
    />
    <meta name="robots" content="nosnippet, noarchive, noimgeindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Black Eyed Peas - Loja</title>
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../CSS/style.css" />
    <link rel="stylesheet" href="../../CSS/admin.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/aa07e9ca9b.js" crossorigin="anonymous"></script>
    
  </head>
<body>
    <!--** Cabeçalho **-->
    <header class="container-fluid">
      <section class="row">
        <!--** LOGO **-->
        <div class="col-sm-3 logo">
          <div class="logo-img">
            <a href="../../html/home.html">
              <img
                src="../../imagens/image_logo_teste1.png"
                alt="Logo"
                title="Logo-black-eyed-peas"
                width="100"
              />
            </a>
          </div>
        </div>
        <nav class="col-sm-6">
          <!--** Navegação Horizontal **-->
          <ul class="nav nav-pills justify-content-center">
            <li class="nav-item">
              <a
                class="nav-link text-success"
                aria-current="page"
                href="../../html/home.html"
                >Home</a
              >
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-light bg-success" href="admin.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Administração
              </a>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item text-success" href="admin.php">Admin</a></li>
                <li><a class="dropdown-item text-success" href="users.php">Users</a></li>
                <li><a class="dropdown-item active bg-success" href="orders.php">Orders</a></li>
                <li><a class="dropdown-item text-success" href="products.php">Products</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link text-success" href="../store.php">Loja</a>
            </li>
          </ul>
        </nav>
        <div class="col-sm-3 tour">
          <!--** Banner para a tour de Las Vegas **-->
          <ul class="nav nav-pills justify-content-end">
            <li class="nav-item">
              <a class="nav-link" href="../logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </li>
          </ul>
        </div>
      </section>
    </header>
    <main class="container-fluid">
      <section class="container-fluid mb-4">
        <h3>Pedidos finalizados</h3>
        <table class="table table-dark table-borderless">
          <thead>
            <tr>
              <th>Username</th>
              <th>Total Orders</th>
              <th>First Order</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $query->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['username'] ?></td>
              <td><?php echo $row['total_orders'] ?></td>
              <td><?php echo $row['first_order'] ?></td>
              <td><?php echo $row['_status'] ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </section>
        <section class="container-fluid mb-4">
            <h3>Produtos do carrinho</h3>
            <form action="orders.php" method="get">
              <label for="search" class="form-label">Buscar produtos no carrinho</label>
              <input type="text" name="search" id="search" class="form-control mb-3" style="width: 50%;">
              <button class="btn btn-outline-light" type="submit">Search</button>
            </form>
        </section>
        <section>
            <?php if($orders): ?>
              <table class="table table-dark table-borderless">
              <thead>
                <tr>
                  <th>Username</th>
                  <th>Product</th>
                  <th>Unit Price</th>
                  <th>Stock</th>
                  <th>Type</th>
                  <th>Quantity</th>
                  <th>SubTotal</th>
                  <th>Order Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($orders as $order): ?>
                <tr>
                  <td><?php echo $order['username'] ?></td>
                  <td><?php echo $order['product'] ?></td>
                  <td><?php echo $order['price'] ?></td>
                  <td><?php echo $order['stock'] ?></td>
                  <td><?php echo $order['type'] ?></td>
                  <td><?php echo $order['quantity'] ?></td>
                  <td><?php echo $order['subtotal'] ?></td>
                  <td><?php echo $order['order_status'] ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php else: ?>
              <p>Nenhum produto encontrado</p>
            <?php endif; ?>
        </section>
    </main>
    <!--** Início do rodape **-->
    <footer class="container-fluid footer_admin">
      <!--** Rodapé **-->
      <section class="contato row conte">
        <div class="col-sm-4 item1" id="contatos-footer">
          <!-- **contatos nas redes sociais** -->
          <h4 class="text-emphases bg-success">Fale conosco</h4>
          <ul>
            <li>
              <a class="link text-success" href=""
                ><i class="fa-solid fa-envelope"></i> blackeyedpeas@bep.com</a
              >
            </li>
            <li>
              <a
                class="link text-success"
                href="https://www.instagram.com/blackeyedpeas/"
                ><i class="fa-brands fa-instagram"></i> @blackeyedpeas</a
              >
            </li>
            <li>
              <a
                class="link text-success"
                href="https://www.facebook.com/blackeyedpeas/?locale=pt_PT"
                ><i class="fa-brands fa-facebook"></i> Black Eyed Peas</a
              >
            </li>
            <li>
              <a class="link text-success" href="https://x.com/bep"
                ><i class="fa-brands fa-x-twitter"></i> @bep</a
              >
            </li>
            <li>
              <a
                class="link text-success"
                href="https://www.threads.net/@blackeyedpeas"
                ><i class="fa-brands fa-threads"></i> blackeyedpeas</a
              >
            </li>
          </ul>
        </div>
        <div
          class="col-sm-6 bg-success item2"
          id="texto1"
          style="--bs-bg-opacity: 0"
        >
          <!-- **Mensagem para os fãs** -->
          <h2>Olá Admin. Espero que tenha resolvido tudo!</h2>
        </div>
      </section>
      <div class="copy">
        &copy; 2024 Black Eyed Peas. Todos os direitos reservados.
      </div>
    </footer>
    <!--** FIM do rodape **-->


    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../JavaScript/admin.js"></script>
</body>
</html>