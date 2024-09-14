<?php

  include_once 'app/control.php';
  $control     = new Control('index');
  
  if($control->permission() == 1){

    $session  = Components\getSession('maestro_adm');
    $userData = Control::isAccessible(
      $session['id']    ? $session['id']    : '', 
      $session['token'] ? $session['token'] : '',
      $session['tempo'] ? $session['tempo'] : ''
    );
    $userData = json_encode($userData);
    $userData = json_decode($userData);
    
    if(is_array($userData) == false):
      echo trim('
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Dashboard</title>
        <link rel="shortcut icon" type="image/x-icon" href="theme-assets/images/ico/favicon.png">
        <link rel="stylesheet" type="text/css" href="theme-assets/css/vendors.css">
        <style>
        .content{
          background: #1c1c1c;
          color: #fafafa;
          z-index: 1024;
          position: fixed;
          top: 0;
          left: 0;
          bottom: 0;
          right: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          text-align: center;
          padding: 12px;
        }
      </style>
      <div class="content">
        <div>
          <p style="font-size: 1.4em;">A sua sessão expirou, faça login novamente!</p>
          <a class="btn btn-primary" href="./?sair">Terminar sessão</a>
        </div>
      </div>
      ');
      exit();
    else:
      $userData = $userData[0];
    endif;

  }else{
    header('location: ./?sair');
    exit();
  }

  $conexao = new Conexao();

?>
<!DOCTYPE html>
<html class="loading" lang="<?= isset($getSystem['lang']) ? $getSystem['lang'] : 'pt-pt'; ?>" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title>Dashboard</title>
    <meta name="description" content="">
    <meta name="author" content="Rubro Ltd">

    <link rel="apple-touch-icon" href="theme-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="theme-assets/images/ico/favicon.png">
    <!--<link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">-->
    <link href="assets/line-awesome/1.3.0/css/line-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="theme-assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" href="theme-assets/vendors/css/charts/chartist.css">
    <link rel="stylesheet" type="text/css" href="theme-assets/css/app-lite.css">
    <link rel="stylesheet" type="text/css" href="theme-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="theme-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="theme-assets/css/pages/dashboard-ecommerce.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.toast.css">
    <link rel="stylesheet" type="text/css" href="assets/css/magnific-popup.css">
    <!--Jquery -->
    <script src="theme-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <script src="assets/js/jquery.magnific-popup.min.js" type="text/javascript"></script>
    <script src="assets/js/jquery.toast.js" type="text/javascript"></script>
  </head>
  <style type="text/css">
    html{font-size: <?php if($getSystem['font'] == 0): echo '14px'; elseif($getSystem['font'] == 1): echo '12pt'; elseif($getSystem['font'] == 2): echo '13pt'; else: echo '14px'; endif; ?>;}
  </style>
  <body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-chartbg" data-col="2-columns">

    <div class="preload">
      <div class="w-100">
        <center>
          <img src="./assets/img/preloader.gif">
        </center>			
      </div>
    </div>

    <!-- fixed-top-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light">
      <div class="navbar-wrapper">
        <div class="navbar-container content">
          <div class="collapse navbar-collapse show" id="navbar-mobile">
            <ul class="nav navbar-nav mr-auto float-left">
              <li class="nav-item d-block d-md-none">
                <a class="nav-link nav-menu-main menu-toggle hidden-xs" href=""><i class="ft-menu"></i></a>
              </li>
              <li class="nav-item d-none d-md-block">
                <a id="ft-maximize" class="nav-link nav-link-expand" href="javascript:void(0)"><i class="ficon ft-maximize"></i></a>
              </li>
              <li class="nav-item dropdown navbar-search">
                <a class="nav-link dropdown-toggle hide" data-toggle="dropdown" href="#">
                  <i class="ficon ft-search"></i>
                </a>
                <ul class="dropdown-menu">
                  <li class="arrow_box">
                    <form method="GET">
                      <div class="input-group search-box">
                        <div class="position-relative has-icon-right full-width">
                          <input class="form-control" id="search" type="text" name="s" placeholder="Procure aqui...">
                          <div class="form-control-position navbar-search-close">
                            <i class="ft-x"></i>
                          </div>
                        </div>
                      </div>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav float-right">         
              <li class="dropdown dropdown-language nav-item">
                <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="flag-icon flag-icon-pt"></i><span class="selected-language"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                  <div class="arrow_box">
                    <a class="dropdown-item" href="javascript:void(0)"><i class="flag-icon flag-icon-pt"></i> Português</a>
                  </div>
                </div>
              </li>
            </ul>
            <ul class="nav navbar-nav float-right">
              <?php
                if($getDef['modulo']['loja']){
                  ?>
                  
                  <li class="dropdown dropdown-notification nav-item">
                    <a id="shopping-cart-updating-state" class="nav-link nav-link-label link-bell" href="?pendentes">
                      <i class="ficon ft-shopping-cart">
                        <?php
                          $_factura_pendente = DB\Mysql::select(
                          "SELECT id FROM fatura WHERE estado = 0"
                          );
                          if(is_array($_factura_pendente)){
                            eco("<span class='badge badge-light'>".count($_factura_pendente)."</span>");
                          }
                        ?> 
                      </i>
                    </a>
                  </li>

                  <li class="dropdown dropdown-notification nav-item">
                    <a id="shopping-cart-updating-deliver-state" class="nav-link nav-link-label link-bell" href="?entregas">
                      <i class="ficon ft-map-pin">
                        <?php
                          $_factura_entrega = DB\Mysql::select(
                          "SELECT id FROM fatura WHERE estado > 0 AND entrega = 0"
                          );
                          if(is_array($_factura_entrega)){
                            eco("<span class='badge badge-light'>".count($_factura_entrega)."</span>");
                          }
                        ?> 
                      </i>
                    </a>
                  </li>
                  
                  <?php
                }
                if($getDef['modulo']['agenda']){
                  ?>
                  <li class="dropdown dropdown-notification nav-item">
                    <a class="nav-link nav-link-label link-bell" href="?notificacoes">
                      <i class="ficon ft-bell">
                        <?php
                          $bell = DB\Mysql::select(
                          "SELECT id FROM agenda WHERE data = :data AND checked = 0",
                            ['data' => date('Y-m-d')]
                          );
                          if(is_array($bell)){
                            eco("<span class='badge badge-light'>".count($bell)."</span>");
                          }
                        ?> 
                      </i>
                    </a>
                  </li>
                  <?php
                }
              ?>
              <li class="dropdown dropdown-user nav-item">
                <a class="dropdown-toggle nav-link dropdown-user-link" href="javascript:void(0)" data-toggle="dropdown">             
                  <span class="avatar avatar-online">
                    <img class="perfil-nav-image" id="userData-image" src="assets/img/perfil/<?= isset($userData->imagem) && is_file('assets/img/perfil/'.$userData->imagem) ? $userData->imagem : 'user.png'; ?>" alt="avatar"><i></i>
                  </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <div class="arrow_box_right">
                    <a class="dropdown-item" href="?perfil">
                      <span class="avatar avatar-online">
                        <img class="perfil-drop-image" id="userData-image" src="assets/img/perfil/<?= isset($userData->imagem) && is_file('assets/img/perfil/'.$userData->imagem) ? $userData->imagem : 'user.png'; ?>" alt="avatar">
                        <span class="user-name text-bold-700 ml-1"> <?= isset($userData->nome) ? $userData->nome : DEFAULT_STRING; ?></span>
                      </span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="?perfil&edit"><i class="ft-user"></i> Editar Perfil</a>
                    <?php
                      if($getDef['modulo']['agenda']){
                        ?>
                          <a class="dropdown-item" href="?agenda"><i class="ft-book"></i> Agenda</a>
                        <?php
                      }
                    ?>
                    <a class="dropdown-item" href="?def"><i class="las la-cog" style="font-size: 18px;"></i> Definições</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="?sair"><i class="ft-power"></i> Sair</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- ////////////////////////////////////////////////////////////////////////////menu-dark-->

    <div 
    class="main-menu menu-fixed menu-accordion menu-shadow menu-<?= isset($getSystem['theme']) && $getSystem['theme'] != '' ? $getSystem["theme"] : 'light'; ?> border-0" 
    data-scroll-to-active="true" data-img="theme-assets/images/backgrounds/<?= isset($getSystem['image']) && $getSystem['image'] != '' ? $getSystem["image"] : '01.jpg'; ?>">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">       
          <li class="nav-item mr-auto">
            <a class="navbar-brand" href="./">
              <img class="brand-logo" alt="Logo" src="theme-assets/images/logo/logo.png"/>
            </a>
            </li>
          <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
      </div>
      <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

          <li class="nav-item <?php if(count($_GET) <= 0): echo 'active'; endif; ?>">
            <a href="./">
              <i class="ft-home"></i><span class="menu-title">Dashboard</span>
            </a>
          </li>

          <?php
            if($getDef['modulo']['blog']){
              ?>
              <li class="nav-item <?php if(get('feed', true)): eco('active'); endif; ?>">
                <a href="./?feed">
                  <i class="las la-blog"></i><span class="menu-title" >Feed</span>
                </a>
              </li>              
              <?php
            }

            if($getDef['modulo']['anuncio']):
              ?>
              <li class="nav-item <?php if(get('anuncio', true)): eco('active'); endif; ?>">
                <a href="./?anuncio">
                  <i class="las la-bullhorn"></i><span class="menu-title">Anúncio</span>
                </a>
              </li>
              <?php
            endif;

            if($getDef['modulo']['cronometro']){
              ?>
              <li class="nav-item <?php if(get('cronometro', true)): eco('active'); endif; ?>">
                <a href="./?cronometro">
                  <i class="las la-clock"></i><span class="menu-title" >Cronômentro</span>
                </a>
              </li> 
              <?php
            }

            if($getDef['modulo']['usuarios']){
              ?>
                <li class="nav-item <?php if(get('usuarios', true)): eco('active'); endif; ?>">
                  <a href="./?usuarios">
                    <i class="ft-users"></i><span class="menu-title"> Usuários</span>
                  </a>
                </li>              
              <?php
            }
            
            if($getDef['modulo']['galeria']){
              ?>
                <li class="nav-item <?php if(get('galeria', true)): eco('active'); endif; ?>">
                  <a href="./?galeria">
                    <i class="ft-image"></i><span class="menu-title"> Galeria</span>
                  </a>
                </li>              
              <?php
            }

            if($getDef['modulo']['transmicao']){
              ?>
                <li class="nav-item has-sub 
                  <?php 
                    if(get('podcast', true) || get('transmicao', true) || get('artistas') || get('album')): 
                      eco('active'); 
                    endif; 
                  ?>">
                  <a href="javascript:void('transmicao')">
                    <i class="las la-broadcast-tower"></i><span class="menu-title"> Transmitir</span>
                  </a>
                  <ul class="menu-content">
                    <li class="drop <?php if(get('artistas', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?artistas"> Artistas</a> 
                    </li>
                    <li class="drop <?php if(get('album', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?album"> Album</a> 
                    </li>
                    <li class="drop <?php if(get('podcast', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?podcast"> Audio podcast</a> 
                    </li>
                    <li class="drop <?php if(get('transmicao', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?transmicao"> Vídeo podcast</a>
                    </li>
                  </ul>
                </li>              
              <?php
            }

            if($getDef['modulo']['curso']){
              ?>
                <li class="nav-item has-sub 
                  <?php 
                    if(get('cursos', true) || get('cursos_pendentes', true) || get('cursos_formadores', true)): 
                      eco('active'); 
                    endif; 
                  ?>">
                  <a href="javascript:void('Cursos')">
                    <i class="ft-book"></i><span class="menu-title">Cursos</span>
                  </a>
                  <ul class="menu-content">
                    <li class="drop <?php if(get('cursos', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?cursos">Lista de cursos</a> 
                    </li>
                    <li class="drop <?php if(get('cursos_pendentes', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?cursos_pendentes">Compras pendentes</a>
                    </li>
                    <li class="drop <?php if(get('cursos_formadores', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?cursos_formadores">Formadores</a>
                    </li>
                  </ul>
                </li>              
              <?php
            }

            if($getDef['modulo']['chatbox']){
              ?>
                <li class="nav-item <?php if(get('chat', true)): eco('active'); endif; ?>">
                  <a href="./?chat">
                    <i class="las la-sms"></i><span class="menu-title"> ChatBox</span>
                  </a>
                </li>            
              <?php
            }

            if($getDef['modulo']['loja']){
              ?>
                <li class="nav-item has-sub 
                  <?php 
                    if(get('pendentes', true) || get('historico', true) || get('entregas', true) || get('estatisticas', true) || get('artigos', true)): 
                      eco('active'); 
                    endif; 
                  ?>">
                  <a href="javascript:void('Vendas')">
                    <i class="las la-file-invoice-dollar"></i><span class="menu-title">Vendas</span>
                  </a>
                  <ul class="menu-content">
                    <li class="drop <?php if(get('artigos', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?artigos">Artigos</a>
                    </li>
                    <li class="drop <?php if(get('pendentes', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?pendentes">Compras pendentes</a>
                    </li>
                    <li class="drop <?php if(get('historico', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?historico">Histórico de vendas</a>
                    </li>
                    <li class="drop <?php if(get('entregas', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?entregas">Entregas</a>
                    </li>
                    <li class="drop <?php if(get('estatisticas', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?estatisticas">Estatísticas</a>
                    </li>
                  </ul>
                </li>              
              <?php
            }

            if($getDef['modulo']['imoveis']){
              ?>
              <li class="nav-item <?php if(get('imoveis', true)): eco('active'); endif; ?>">
                <a href="./?imoveis">
                  <i class="las la-warehouse"></i><span class="menu-title" >Imóveis</span>
                </a>
              </li>              
              <?php
            }

            if($getDef['modulo']['servicos']){
              ?>
                <li class="nav-item has-sub 
                  <?php 
                    if(get('servicos', true) || get('servicos_list', true) || get('servicos_categoria', true) || get('servicos_factura', true)): 
                      eco('active'); 
                    endif; 
                  ?>">
                  <a href="javascript:void('Cursos')">
                    <i class="ft-target"></i><span class="menu-title">Serviços</span>
                  </a>
                  <ul class="menu-content">
                    <li class="drop <?php if(get('servicos_list', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?servicos_list">Listar serviços</a>
                    </li>
                    <li class="drop <?php if(get('servicos_categoria', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?servicos_categoria">Categorias</a> 
                    </li>
                    <li class="drop <?php if(get('servicos_factura', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?servicos_factura">Facturas</a> 
                    </li>
                  </ul>
                </li>              
              <?php
            }

            if($getDef['modulo']['turismo']):
              ?>
              <li class="nav-item has-sub <?php if(get('city', true) || get('reservas', true)): eco('active'); endif; ?>">
                <a href="#">
                  <i class="las la-bus"></i><span class="menu-title">Turismo</span>
                </a>
                <ul class="menu-content">
                  <li class="drop <?php if(get('city', true)):eco('active'); endif; ?>">
                    <a class="menu-item" href="./?city">Cidades/Províncias</a>
                  </li>
                  <li class="drop <?php if(get('reservas', true)):eco('active'); endif; ?>">
                    <a class="menu-item" href="./?reservas">Reservas</a>
                  </li>
                  <li class="drop <?php if(get('transfer', true)):eco('active'); endif; ?>">
                    <a class="menu-item" href="./?transfer">Transfer</a>
                  </li>
                </ul>
              </li>
              <?php
            endif;

            if($getDef['modulo']['uber']):
              ?>
              <li class="nav-item has-sub <?php if(get('', true)): eco('active'); endif; ?>">
                <a href="#">
                  <i class="las la-taxi"></i><span class="menu-title">Uber</span>
                </a>
                <ul class="menu-content">
                  <li class="drop <?php if(get('', true)):eco('active'); endif; ?>">
                    <a class="menu-item" href="./?"></a>
                  </li>
                </ul>
              </li>
              <?php
            endif;

            //Utilitarios
            if($getDef['modulo']['blog'] || $getDef['modulo']['curso'] || $getDef['modulo']['loja'] || $getDef['modulo']['imoveis']){
              ?>
                <li class="nav-item has-sub <?php if(get('categorias', true) || get('subcategorias', true) || get('marcas', true) || get('tamanhos', true) || get('cores', true) ): eco('active'); endif; ?>">
                  <a href="#">
                    <i class="las la-tools"></i><span class="menu-title">Utilitários</span>
                  </a>
                  <ul class="menu-content">
                    <li class="drop <?php if(get('categorias', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?categorias">Categorias</a>
                    </li>
                    <li class="drop <?php if(get('subcategorias', true)): eco('active'); endif; ?>">
                      <a class="menu-item" href="./?subcategorias">Subcategorias</a>
                    </li>
                    <?php

                      if($getDef['modulo']['loja']):
                        ?>
                        <li class="drop <?php if(get('cores', true)): eco('active'); endif; ?>">
                          <a class="menu-item" href="./?cores">Cores</a>
                        </li>
                        <li class="drop <?php if(get('marcas', true)): eco('active'); endif; ?>">
                          <a class="menu-item" href="./?marcas">Marcas</a>
                        </li>
                        <?php
                      endif;

                      if($getDef['modulo']['loja'] || $getDef['modulo']['imoveis']){
                        ?>
                        <li class="drop <?php if(get('tamanhos', true)): eco('active'); endif; ?>">
                          <a class="menu-item" href="./?tamanhos">Tamanhos</a>
                        </li>
                        <?php
                      }
        
                      if($getDef['modulo']['imoveis']):
                        ?>
                        <li class="drop <?php if(get('pavimentos', true)): eco('active'); endif; ?>">
                          <a class="menu-item" href="./?pavimentos">Pavimentos</a>
                        </li>
                        <li class="drop <?php if(get('dormitorios', true)): eco('active'); endif; ?>">
                          <a class="menu-item" href="./?dormitorios">Dormitórios</a>
                        </li>
                        <li class="drop <?php if(get('banheiros', true)): eco('active'); endif; ?>">
                          <a class="menu-item" href="./?banheiros">Banheiros</a>
                        </li>
                        <li class="drop <?php if(get('garagem', true)): eco('active'); endif; ?>">
                          <a class="menu-item" href="./?garagem">Garagem</a>
                        </li>
                        <li class="drop <?php if(get('areas', true)): eco('active'); endif; ?>">
                          <a class="menu-item" href="./?areas">Áreas Construídas</a>
                        </li>
                        <?php
                      endif;

                    ?>
                  </ul>
                </li>
              <?php
            }
          ?>

          <?php
            if($getDef['modulo']['feedback']):
              ?>
              <li class="nav-item <?php if(get('feedback')): eco('active'); endif; ?>">
                <a href="./?feedback">
                  <i class="las la-smile"></i><span class="menu-title">Feedback</span>
                </a>
              </li>
              <?php
            endif;

            if($getDef['modulo']['newsletter']):
              ?>
              <li class="nav-item <?php if(get('newsletter', true)): eco('active'); endif; ?>">
                <a href="./?newsletter">
                  <i class="las la-mail-bulk"></i><span class="menu-title">Newsletter</span>
                </a>
              </li>
              <?php
            endif;

            if($getDef['modulo']['pagamento']):
              ?>
              <li class="nav-item <?php if(get('pagamento', true)): eco('active'); endif; ?>">
                <a href="./?pagamento">
                  <i class="las la-money-bill"></i><span class="menu-title">Pagamentos</span>
                </a>
              </li>
              <?php
            endif;

            if($getDef['modulo']['deliver']):
              ?>
              <li class="nav-item <?php if(get('deliver', true)): eco('active'); endif; ?>">
                <a href="./?deliver">
                  <i class="las la-truck"></i><span class="menu-title">Deliver</span>
                </a>
              </li>
              <?php
            endif;

          ?>

        </ul>

      </div>
      <div class="navigation-background"></div>
    </div>

    <div class="app-content content">
      <div class="content-wrapper">
        <?php
          if(get('feed', true) && $getDef['modulo']['blog']):
            include_once 'app/pg/feed/feed.php';

          elseif(get('usuarios', true) && $getDef['modulo']['usuarios']):
            include_once 'app/pg/usuarios.php';

          // <Vendas>
          elseif(get('artigos', true) && $getDef['modulo']['loja']):
            include_once 'app/pg/artigos/artigos.php';

          elseif(get('pendentes', true) && $getDef['modulo']['loja']):
            include_once 'app/pg/vendas/pendentes.php';

          elseif(get('historico', true) && $getDef['modulo']['loja']):
            include_once 'app/pg/vendas/historico.php';

          elseif(get('entregas', true) && $getDef['modulo']['loja']):
            include_once 'app/pg/vendas/entregas.php';

          elseif(get('estatisticas', true) && $getDef['modulo']['loja']):
            include_once 'app/pg/vendas/estatisticas.php';

          // </Vendas>

          // <Imoveis>
          elseif(get('imoveis', true) && $getDef['modulo']['imoveis']):
            include_once 'app/pg/imoveis/feed.php';

          // </Imoveis>

          // <Serviços>
          elseif(get('servicos_list', true) && $getDef['modulo']['servicos']):
            include_once 'app/pg/servicos/servicos_list.php';

          elseif(get('servicos_categoria', true) && $getDef['modulo']['servicos']):
            include_once 'app/pg/servicos/categoria.php';
  
            elseif(get('servicos_factura', true) && $getDef['modulo']['servicos']):
              include_once 'app/pg/servicos/factura.php';

          // </Serviços>

          // <Cursos>
          elseif(get('cursos', true) && $getDef['modulo']['curso']):
            include_once 'app/pg/cursos/cursos.php';

          elseif(get('cursos_pendentes', true) && $getDef['modulo']['curso']):
            include_once 'app/pg/cursos/pendentes.php';

          elseif(get('cursos_formadores', true) && $getDef['modulo']['curso']):
            include_once 'app/pg/cursos/formadores.php';
          // </Cursos>

          // <ChatBox>
          elseif(get('chat', true) && $getDef['modulo']['chatbox']):
            include_once 'app/pg/chat/chat.php';

          // <Turismo>
          elseif(get('city', true) && $getDef['modulo']['turismo']):
            include_once 'app/pg/turismo/turismo.php';
          
          elseif(get('reservas', true) && $getDef['modulo']['turismo']):
            include_once 'app/pg/turismo/reservas.php';

          elseif(get('transfer', true) && $getDef['modulo']['turismo']):
            include_once 'app/pg/turismo/transfer.php';

          // </Turismo>

          // <Utilitários>
          elseif(get('categorias', true)):
            include_once 'app/pg/utilitarios/categorias.php';

          elseif(get('subcategorias', true)):
            include_once 'app/pg/utilitarios/subcategorias.php';

          elseif(get('marcas', true) && $getDef['modulo']['loja']):
            include_once 'app/pg/utilitarios/marcas.php';
 
          elseif(get('tamanhos', true) && $getDef['modulo']['loja'] || get('tamanhos', true) && $getDef['modulo']['imoveis']):
            include_once 'app/pg/utilitarios/tamanhos.php';
          
          elseif(get('cores', true) && $getDef['modulo']['loja']):
            include_once 'app/pg/utilitarios/cores.php';
    
          elseif(get('pavimentos', true) && $getDef['modulo']['imoveis']):
            include_once 'app/pg/utilitarios/pavimentos.php';
            
          elseif(get('dormitorios', true) && $getDef['modulo']['imoveis']):
            include_once 'app/pg/utilitarios/dormitorios.php';

          elseif(get('areas', true) && $getDef['modulo']['imoveis']):
            include_once 'app/pg/utilitarios/areas.php';
      
          elseif(get('banheiros', true) && $getDef['modulo']['imoveis']):
            include_once 'app/pg/utilitarios/banheiros.php';

          elseif(get('garagem', true) && $getDef['modulo']['imoveis']):
            include_once 'app/pg/utilitarios/garagem.php';

          // </Utilitários>

          elseif(get('feedback', true) && $getDef['modulo']['feedback']):
            include_once 'app/pg/feedback.php';
          
          elseif(get('newsletter', true) && $getDef['modulo']['newsletter']):
            include_once 'app/pg/newsletter/newsletter.php';
            
          // <Perfil>
          elseif(get('perfil', true)):
            include_once 'app/pg/perfil.php';
          
          // </Perfil>
          
          // <Agenda>
          elseif(get('agenda', true) && $getDef['modulo']['agenda']):
            include_once 'app/pg/agenda/agenda.php';
          
          elseif(get('notificacoes', true) && $getDef['modulo']['agenda']):
            include_once 'app/pg/agenda/notificacoes.php';
          // </Agenda>
          
          elseif(get('anuncio', true) && $getDef['modulo']['anuncio']):
            include_once 'app/pg/anuncio.php';
          
          elseif(get('cronometro', true) && $getDef['modulo']['cronometro']):
            include_once 'app/pg/cronometro.php';
          
          elseif(get('s')):
            include_once 'app/pg/search.php';

          //Definições
          elseif(get('def', true)):
            include_once 'app/pg/definicoes.php';

          //Galeria
          elseif(get('galeria', true) && $getDef['modulo']['galeria']): 
            include_once 'app/pg/galeria/galeria.php';

          //Transmição
          elseif(get('artistas', true) && $getDef['modulo']['transmicao']): 
            include_once 'app/pg/transmicao/artistas.php';
    
          elseif(get('album', true) && $getDef['modulo']['transmicao']): 
              include_once 'app/pg/transmicao/album.php';

          elseif(get('podcast', true) && $getDef['modulo']['transmicao']): 
            include_once 'app/pg/transmicao/podcast.php';

          elseif(get('transmicao', true) && $getDef['modulo']['transmicao']): 
            include_once 'app/pg/transmicao/transmicao.php';

          //Metodos de pagamento
          elseif(get('pagamento', true) && $getDef['modulo']['pagamento']): 
            include_once 'app/pg/pagamento/pagamento.php';
          
          //Deliver
          elseif(get('deliver', true) && $getDef['modulo']['deliver']): 
            include_once 'app/pg/deliver/deliver.php';

          elseif(get('termos')):
            include_once 'app/pg/termos.php';

          else:
            if(count($_GET) > 0 ): eco('<script> location.href = "./"; </script>'); exit(); endif;
            include_once 'app/pg/home.php';

          endif;
        ?>
      </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <footer class="footer footer-static footer-light navbar-border navbar-shadow">
      <div class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <ul class="list-inline float-md-left d-block d-md-inline-blockd-none d-lg-block mb-0">
          <li class="list-inline-item">
            <a class="my-1" style="font-weight: 600;">&copy;<?= date('Y'); ?></a>
            <a style="font-weight: 600;" href="" target="_blank"> </a> 
          </li>
        </ul>
        <ul class="list-inline float-md-right d-block d-md-inline-blockd-none d-lg-block mb-0">
          <li class="list-inline-item">
            <a class="my-1" href="./?termos"> Termos de serviços & privacidade</a>
          </li>
        </ul>
      </div>
    </footer>

    <!-- BEGIN VENDOR JS-->
    <script src="theme-assets/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-menu-lite.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-lite.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>
  </body>
</html>