<?php

session_start();
include '../db.php';
//restringindo acesso
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../profile.php');
    exit();
}

//buscando produtos
$products = [];
if(isset($_GET['search'])) {
    $search = $_GET['search'];
    
    $sql = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR type LIKE ?");
    $likeSearch = "%$search%";
    $sql->bind_param('ss', $likeSearch, $likeSearch);
    $sql->execute();
    $result = $sql->get_result();
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $sql->close();
    
} else {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

//adicionando produtos
$response = ['success' => false, 'message' => 'Acao invalida'];
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['type'])) {
        
        //fazer o upload da foto do produto
        $image = '';
        if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image = 'uploads/'. basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], '../'.$image);
        }


        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $type = trim($_POST['type']);

        //verifica se o produto já existe
        $stmt = $conn->prepare('SELECT COUNT(*) FROM products WHERE name = ?');
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($productExists);
        $stmt->fetch();

        if($productExists > 0) {
            $response['message'] = 'Produto já existe';
            echo json_encode($response);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, type, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdsss', $name, $description, $price, $stock, $type, $image);
        $success = $stmt->execute();
        if($success) {
            $response['success'] = true;
            $response['message'] = "Produto adicionado com sucesso";
        } else {
            $response['message'] = "Erro ao adicionar produto";
        }
        echo json_encode($response);
    }

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
    <link rel="stylesheet" href="../../CSS/admin.css?v=2">
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
                <li><a class="dropdown-item text-success" href="orders.php">Orders</a></li>
                <li><a class="dropdown-item active bg-success" href="products.php">Products</a></li>
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
    <main class="container-fluid d-flex flex-column">
        <section class="container-fluid">
            <h3>Gerenciar Products</h3>
            <div class="mb-4 search">
                <form action="products.php" method="get" id="form-user">
                    <h5>Buscar produtos</h5>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Digite o nome ou tipo do produto" style="width: 50%; display: inline-block;">
                    <button type="submit" class="btn btn-outline-light form-control"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <div class="container-fluid">
                <table id="table_admin" class="table table-borderless table-dark">
                    <?php if($products): ?>
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $product): ?>
                        <tr>
                            <td><img src="../<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="75"></td>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['description']; ?></td>
                            <td>€<?php echo $product['price']; ?></td>
                            <td><?php echo $product['stock']; ?></td>
                            <td><?php echo $product['type']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm editar_produto" data-id="<?php echo $product['id']; ?>">Editar</button>
                                <button class="btn btn-danger btn-sm remover_produto" data-id="<?php echo $product['id']; ?>">Remover</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p class="text-danger">Nenhum produto encontrado</p>
                <?php endif; ?>
            </div>
        </section>
        <section id="section_textarea" class="container-fluid mb-4">
            <form id="edit_form" method="post">
                <label for="code" class="form-label">Digite código SQL para editar:</label>
                <textarea name="code" id="code" rows="4" cols="5" class="form-control mb-3" placeholder="UPDATE products SET coluna = 'valor' WHERE id = ?"></textarea>
                <button type="submit" id="edit_" class="btn btn-outline-light">Editar</button>
            </form>
            <div id="resultado"></div>
        </section>
        <!--
        <section id="editProducts">
            <h3>Editar Produto</h3>
            <form id="form_edit" method="get" enctype="multipart/form-data">
                <label for="name" class="form-label">Name: </label>
                <input type="text" name="name" id="name" class="form-control">
                <label for="description" class="form-label">Description: </label>
                <input type="text" name="description" id="description" class="form-control">
                <label for="price" class="form-label">Price: </label>
                <input type="text" name="price" id="price" class="form-control">
                <label for="stock" class="form-label">Stock: </label>
                <input type="number" name="stock" id="stock" class="form-control">
                <label for="type" class="form-label">Type: </label>
                <input type="text" name="type" id="type" class="form-control">
                <label for="image" class="form-label">Imagem: </label>
                <input type="file" name="image" id="image" class="form-control">
                <input type="submit" class="btn btn-success" id="form_edit_product" value="Editar">
            </form>
        </section> -->
        <section id="addProducts" class="d-flex flex-column align-items-center">
            <h3 id="add_product">Adicionar produto</h3>
            <form id="form_products" method="post" enctype="multipart/form-data" class="row gy-2 gx-3 align-items-center">
                <div>
                    <label for="name" class="form-label">Name: </label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <div>
                    <label for="description" class="form-label">Description: </label>
                    <input type="text" name="description" id="description" class="form-control">
                </div>
                <div>
                    <label for="price" class="form-label">Price: </label>
                    <input type="number" name="price" id="price" class="form-control" step="0.01" min="0">
                </div>
                <div>
                    <label for="stock" class="form-label">Stock: </label>
                    <input type="number" name="stock" id="stock" class="form-control" min="1">
                </div>
                <div>
                    <label for="type" class="form-label">Type: </label>
                    <input type="text" name="type" id="type" class="form-control">
                </div>
                <div>
                    <label for="image" class="form-label">Imagem: </label>
                    <input type="file" name="image" id="image" class="form-control mb-3">
                </div>
                <div>
                    <button type="submit" class="btn btn-outline-light">Adicionar produto</button>
                    <p id="error-message" style="color: red;"></p>    
                </div>
            </form>
        </section>
    </main>
    <!--** Início do rodape **-->
    <footer class="container-fluid">
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