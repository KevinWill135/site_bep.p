<?php

    session_start();
    include '../db.php';
    
    if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')  {
        header('Location: ../profile.php');
        exit;
    }

    //buscando usuários
    $searchResults = [];
    if(isset($_GET['search'])) {
        $search = $_GET['search'];
        
        $sql = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ? OR role LIKE ?");
        $likeSearch = "%$search%";
        $sql->bind_param('sss', $likeSearch, $likeSearch, $likeSearch);
        $sql->execute();
        $result = $sql->get_result();
        while($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
        $sql->close();
        
    } else {
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }

    //adicionando usuário
    $response = ['success' => false, 'message' => "Erro desconhecido"];
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        
        //fazer o upload da foto de perfil
        $image = '';
        if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image = 'uploads/'. basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], '../'.$image);
        }


        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        //verifica se o username e email já existe
        $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?');
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userExists);
        $stmt->fetch();

        if($userExists > 0) {
            $response['message'] = 'Usuário ou email já existem';
            echo json_encode($response);
            exit;
        }

        //codifica a senha antes de ser enviada para o banco
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, _password, profile_pic) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $username, $email, $hashedPassword, $image);
        $success = $stmt->execute();
        if($success) {
            $response['success'] = true;
            $response['message'] = "Usuário adicionado com sucesso";
        } else {
            $response['message'] = "Erro ao adicionar usuário";
        }
        echo json_encode($response);
        exit;
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
                <li><a class="dropdown-item active bg-success" href="users.php">Users</a></li>
                <li><a class="dropdown-item text-success" href="orders.php">Orders</a></li>
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
        <h3>Gerenciar Users</h3>
        <section id="section_table_users" class="container-fluid mt-3">
            <div id="searchUser" class="mb-4 search">
                <form action="users.php" method="get" id="form-user">
                    <h5>Buscar usuário</h5>
                    <input type="text" name="search" id="search" class="mb-3 form-control" placeholder="Buscar usuário" style="width: 50%; display: inline-block;">
                    <button type="submit" class="btn btn-outline-light form-control"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <div id="div_table_users">
              <table id="table_admin_users" class="table table-striped table-borderless table-hover table-sm table-dark">
                  <?php if($searchResults): ?>
                  <thead>
                      <tr>
                          <th>Imagem</th>
                          <th>ID</th>
                          <th>Username</th>
                          <th>E-mail</th>
                          <th>Type</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach($searchResults as $user): ?>
                      <tr>
                          <td><img src="../<?php echo $user['profile_pic']; ?>" alt="<?php echo $user['role']; ?>" width="75"></td>
                          <td><?php echo $user['id']; ?></td>
                          <td><?php echo $user['username']; ?></td>
                          <td><?php echo $user['email']; ?></td>
                          <td><?php echo $user['role']; ?></td>
                          <td>
                              <button class="btn btn-warning btn-sm editar_usuario" data-id="<?php echo $user['id']; ?>">Editar</button>
                              <button class="btn btn-danger btn-sm remover_usuario" data-id="<?php echo $user['id']; ?>">Remover</button>
                          </td>
                      </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
              <?php else: ?>
                  <p class="text-danger">Nenhum usuário encontrado</p>
              <?php endif; ?>
            </div>
        </section>
        <section id="add_user" class="d-flex flex-column align-items-center">
          <h3>Adicionar Usuário</h3>
            <form id="form_add_users" method="post" enctype="multipart/form-data" class="row gy-2 gx-3 align-items-center">
                <div>
                  <label for="username" class="form-label">Username: </label>
                  <input type="text" name="username" id="username" class="form-control">
                </div>
                <div>
                  <label for="email" class="form-label">E-mail: </label>
                  <input type="email" name="email" id="email" class="form-control">
                </div>
                <div>
                  <label for="password" class="form-label">Password: </label>
                  <input type="text" name="password" id="password" class="form-control" aria-describedby="passwordHelp">
                </div>
                <div>
                  <label for="image" class="form-label">Imagem: </label>
                  <input type="file" name="image" id="image" class="form-control mb-3">
                </div>
                <div>
                  <button type="submit" class="btn btn-outline-light">Adicionar</button>
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