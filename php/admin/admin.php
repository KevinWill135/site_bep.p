<?php

  session_start();
  if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')  {
    header('Location: ../profile.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link rel="stylesheet" href="../../CSS/style.css" />
    <link rel="stylesheet" href="../../CSS/admin.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/aa07e9ca9b.js" crossorigin="anonymous"></script>
    <script async src="../../JavaScript/admin.js"></script>
    
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
              <li><a class="dropdown-item active bg-success" href="admin.php">Admin</a></li>
                <li><a class="dropdown-item text-success" href="users.php">Users</a></li>
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
    <main class="container mt-2">
      <h1>Olá Admin!</h1>
      <h4>Painel da Administração</h4>
      <ul class="list-group">
        <li class="list-group-item"><a href="users.php">Gerenciar Usuários</a></li>
        <li class="list-group-item"><a href="orders.php">Gerenciar Encomendas</a></li>
        <li class="list-group-item"><a href="products.php">Gerenciar Produtos</a></li>
      </ul>
    </main>
    <!--** Início do rodape
    <footer class="container-fluid footer_admin">
      ** Rodapé
      <section class="contato row conte">
        <div class="col-sm-4 item1" id="contatos-footer">
           **contatos nas redes sociais
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
           **Mensagem para os fãs**
          <h2>Olá Admin. Espero que tenha resolvido tudo!</h2>
        </div>
      </section>
      <div class="copy">
        &copy; 2024 Black Eyed Peas. Todos os direitos reservados.
      </div>
    </footer>
    ** FIM do rodape **-->


    <script src="../../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
</body>
</html>