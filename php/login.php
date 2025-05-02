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
    <link rel="stylesheet" href="../CSS/style.css?v=1" />
    <link rel="stylesheet" href="../CSS/phpStyle.css?v=3" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/aa07e9ca9b.js" crossorigin="anonymous"></script>
    <script async src="../JavaScript/process_login.js"></script>
    
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
              <a class="nav-link text-success" href="store.php">Loja</a>
            </li>
          </ul>
        </nav>
        <div class="col-sm-1 tour">
          <!--** Login e logout **-->
          <a href="profile.php">
            <button class="btn btn-success active"><i class="fa-solid fa-user"></i></button>
          </a>
          <a href="logout.php">
            <button class="btn"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
          </a>
        </div>
      </section>
    </header>
    <!--** FIM Cabeçalho **-->
    <main> <!--*** form para o Login do Utilizador ***-->
        <section>
            <div class="container">
                <h2 style="display:inline-block;">Sign In</h2>
                <div class="col-sm-1 logo" style="display:inline-block;position:relative;top:10px;margin-left:10px">
                  <div class="logo_img_login">
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
                <form id="login-form" method="POST">
                    <div>
                      <label for="email">E-mail:</label>
                      <input type="email" id="email" name="email" required class="form-control-plaintext" placeholder="exemple@exemple.com">
                    </div>
                    <div>
                      <label for="password">Senha:</label>
                      <input type="password" id="password" name="password" required class="form-control-plaintext" placeholder="Digite aqui sua senha">
                    </div>
                    <div id="passwordHelp" class="form-text mb-3">
                      <p id="helpSenha"></p>
                    </div>
                    <div>
                      <p id="message" class="message"></p>
                      <input type="submit" value="Entrar" class="btn btn-outline-light">
                      <p>Ainda não é registado? <a href="register.php">Registe-se</a></p>
                    </div>
                </form>
            </div>
        </section>
    </main><!--** FIM do FORM **-->
    <!--** Início do rodape **-->
    <footer class="container-fluid footer_login">
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
  </body>
</html>