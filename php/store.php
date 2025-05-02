<?php

  session_start();
  include 'db.php';
  $pdo = new PDO('mysql:host=localhost;dbname=site_bep', 'root', 'root');

  if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
  }

  $user_id = $_SESSION['user_id'];

  //armazenando os produtos para introduzir na página
  $sql_store = "SELECT * FROM products";
  $stmt = $conn->prepare($sql_store);
  $stmt->execute();
  $result = $stmt->get_result();

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
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link rel="stylesheet" href="../CSS/style.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/aa07e9ca9b.js" crossorigin="anonymous"></script>
    <script async src="../JavaScript/store.js"></script>
    <script async src="../JavaScript/process_store.js"></script>
    
  </head>
  <body>
    <!--** Cabeçalho **-->
    <header class="container-fluid">
      <section class="row">
        <!--** LOGO **-->
        <div class="col-sm-1 logo">
          <div class="logo-img">
            <a href="../html/home.html">
              <img
                src="../imagens/image_logo_teste1.png"
                alt="Logo"
                title="Logo-black-eyed-peas"
                width="110"
              />
            </a>
          </div>
        </div>
        <nav class="col-sm-10">
          <!--** Navegação Horizontal **-->
          <ul class="nav nav-pills justify-content-center">
            <li class="nav-item">
              <a
                class="nav-link text-success"
                aria-current="page"
                href="../html/home.html"
                >Home</a
              >
            </li>
            <li class="nav-item">
              <a class="nav-link text-success" href="../html/sobre.html">Sobre</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-success" href="../html/albuns.html">Álbuns</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-success" href="../html/tour.html">Tour</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-success" href="../html/contactos.html"
                >Contactos</a
              >
            </li>
            <li class="nav-item">
              <a class="nav-link active bg-success" href="store.php">Loja</a>
            </li>
          </ul>
        </nav>
        <div class="col-sm-1 tour">
          <!--** Login e logout **-->
          <a href="profile.php">
            <button class="btn"><i class="fa-solid fa-user"></i></button>
          </a>
          <a href="logout.php">
            <button class="btn"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
          </a>
        </div>
      </section>
    </header>
    <!--** FIM Cabeçalho **-->

    <!--** Conteúdo da página **-->
    <main class="container-fluid">
      <section id="conteudo" class="row">
        <!--*** Alert caso não haja produto com o filtro selecionado ***-->
        <div id="alerta-filtro" class="col-sm-12 alert alert-success d-none"><!-- Mensagem estará aqui --></div>
        <!-- DIV para filtros -->
        <div id="area-filtro" class="col-sm-3">
          <h3 class="bg-success btn dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Filtros</h3>
          <div id="filtros" class="dropdown-menu">
            <h5>Tipo do Produto</h5>
            <select name="select_type" id="tipo" class="bg-success">
              <option value="">Selecione:</option>
              <option value="tshirt">T-Shirt</option>
              <option value="cds">CDs</option>
              <option value="acessorios">Acessórios</option>
              <option value="casa">Quadros</option>
            </select>
            <div id="precos">
              <input type="checkbox" name="preco1" id="preco1" class="precos"><span class="precosclass"> €0 - €10</span><br>
              <input type="checkbox" name="preco2" id="preco2" class="precos"><span class="precosclass"> €11 - €25</span><br>
              <input type="checkbox" name="preco3" id="preco3" class="precos"><span class="precosclass"> €26 - €50</span><br>
            </div>
            <div id="div-resetar">
              <button id="resetar-filtros" class="btn btn-success" onclick="reset()">Resetar Filtros</button>
            </div>
          </div>
        </div>
        <!-- DIV para produtos e carrinho -->
        <div id="area-principal" class="col-sm-9 row">
          <!-- Área para titulo e carrinho dos produtos -->
          <div id="titulo_produtos" class="row bg-success text-emphases order-sm-1">
            <div class="col-sm-11 titulo"><h3>Produtos</h3></div>
            <div id="carrinho" class="col-sm-1">
              <button id="btn-carrinho" class="btn btn-success">
                <i class="fa-solid fa-cart-shopping"></i>
              </button>
            </div>
            <div id="espaco-carrinho" class="order-sm-0">
              <div id="fechar-carrinho" class="btn">
                  <i class="fa-regular fa-circle-xmark"></i>
              </div>
              <div id="produtos-carrinho">
                <table id="table_carrinho" class="mb-4">
                  <thead id="thead_carrinho">
                    <tr>
                      <th class="th_carrinho">Item</th>
                      <th class="th_carrinho">Preços</th>
                      <th class="th_carrinho">QTD</th>
                    </tr>
                  </thead>
                  <tbody id="tbody_carrinho">
                    <!-- Produtos do carrinho serão carregados aqui -->
                  </tbody>
                </table>
                <div style="display: inline-block;">
                  <button type="button" id="finalizar" class="btn btn-success">Finalizar Compra</button>
                </div>
                <div id="total" style="display: inline-block;">
                  <p>Total: €0.00</p>
                </div>
              </div>
            </div>
          </div>
          <!-- Área para produtos -->
           <div id="produtos-container" class="container-sm row order-sm-5">
            <?php while($row = $result->fetch_assoc()): ?>
              <div class="card text-bg-dark col-sm-3" style="max-width: 18rem; margin: 1.5rem;">
                    <div class="gallery-item card-img-top mt-2 imagem-item">
                          <img src="<?php echo $row['image']; ?>" class="img-fluid" alt="<?php echo $row['name']; ?>">
                    </div>
                    <div class="card-body">
                      <h4 class="card-title"><?php echo $row['name']; ?></h4>
                      <p class="card-text descricao"><?php echo $row['description']; ?></p>
                      <p class="card-text precos">€<?php echo $row['price']; ?></p>
                      <div>
                        <button type="submit" class="btn btn-success adicionar" data-id="<?php echo $row['id']; ?>">
                          Adicionar ao carrinho
                        </button>
                      </div>
                    </div>
              </div>
              <?php endwhile; ?>
           </div>
        </div>
      </section>
    </main>
    <!--** FIM do conteúdo **-->

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
          style="--bs-bg-opacity: 0.3"
        >
          <!-- **Mensagem para os fãs** -->
          <p>
            Gostaríamos que aproveitassem ao máximo de nosso conteúdo e que
            tenha a melhor experiência navegando pelo site que criamos para
            alinhar nossos pensamentos e espírito com os fãs que tanto nos amam
            e nos acompanham.
          </p>
        </div>
      </section>
      <div class="copy">
        &copy; 2024 Black Eyed Peas. Todos os direitos reservados.
      </div>
    </footer>
    <!--** FIM do rodape **-->


    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
    <script src="../JavaScript/carrinho.js"></script>
  </body>
</html>
<!-- <script src="JavaScript/testePOO.js"></script> -->
