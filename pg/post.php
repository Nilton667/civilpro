<style>
  .facebook-share{
    width: 100%; 
    height: 45px; 
    line-height: 45px; 
    background: #1877f2; 
    color: #fff!important; 
    font-size: 16px!important; 
    border-radius: 8px;
    white-space: nowrap;
    overflow: hidden;
		text-overflow: ellipsis;
    transition: background 0.5s;
    padding-left: 8px;
    padding-right: 8px;
  }
  .facebook-share:hover{
    background: #004aab;
  }
  .twitter-share{
    width: 100%; 
    height: 45px; 
    line-height: 45px; 
    background: #1da1f2; 
    color: #fff!important; 
    font-size: 16px!important; 
    border-radius: 8px;
    white-space: nowrap;
    overflow: hidden;
		text-overflow: ellipsis;
    transition: background 0.5s;
    padding-left: 8px;
    padding-right: 8px;
  }
  .twitter-share:hover{
    background: #008be0;
  }
</style>
<style>
  p, h1, h2, h3, h4, h5, span, label{
    color: #666;
  }
</style>
<link href="publico/css/video-js.css" rel="stylesheet"/>
<script src="publico/js/videojs-ie8.min.js"></script>
<div class="container mt-2 mb-4">

  <div class="row justify-content-center">
    <div class="col-12 col-md-9 col-lg-10">
      <div class="row">

        <div class="col-12">
        <?php

          $post    = isset($data[0]->id) ? true : false;

          if ($post):
            $imagem = './publico/img/posts/'.$data[0]->imagem;
            if (is_file($imagem) == false): $imagem   = './publico/img/posts/default.png'; endif;
            ?>

              <head>
                <!--Facebook-->
                <meta property="og:url"           content="<?= "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>"/>
                <meta property="og:type"          content="website"/>
                <meta property="og:title"         content="<?= $header_title; ?>"/>
                <meta property="og:description"   content="<?= $header_description; ?>"/>
                <meta property="og:image"         content="<?= $imagem; ?>"/>
              </head>

              <div id="fb-root"></div>
              <script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v9.0&appId=1995881257112654&autoLogAppEvents=1" nonce="Cj2RkSqo"></script>

              <div>
                <?php
                  if($data[0]->categoria != '' && $data[0]->categoria != 'n/a'){
                    ?>
                      <p 
                        style="color: #767676; text-transform: uppercase;" 
                        class="mt-4 lead">
                        <b><?= $data[0]->categoria; ?></b>
                      </p>
                    <?php
                  }else{
                    eco('<br>');
                  }
                ?>
                <h1 class="mt-1 mb-3"><?= $data[0]->titulo; ?></h1>
                
                <!--Share -->
                <div class="row">

                  <div class="col-6">
                    <a href="javascript:void(0)" class="text-center d-block facebook-share"
                      onclick="
                      window.open(
                      'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),
                      'facebook-share-dialog',
                      'width=626,height=436');
                      return false;">
                      <i class="lab la-facebook" style="font-size: 20px;"></i> Compartilhar no Facebook
                    </a>
                  </div>

                  <div class="col-6">
                    <a href="javascript:void(0)" class="text-center d-block twitter-share"
                      onclick="window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(location.href)+'&text='+document.title+'&&hashtags=Rubro');">
                      <i class="lab la-twitter" style="font-size: 20px;"></i> Compartilhar no twitter
                    </a>
                  </div>

                </div>

                <hr>
                <h5><i><?= $data[0]->subtitulo; ?></i></h5>
                <a class="image-popup" href="<?= $imagem; ?>" style="cursor: zoom-in;">
                  <img class="img-fluid w-100 mt-3" src="<?= $imagem; ?>" style="border-radius: 8px;">
                </a>
                <?php
                  if ($data[0]->video != ''):
                    ?>
                      <div class="mt-3">
                        <video 
                        id="my-video" 
                        class="video-js vjs-default-skin" 
                        data-setup='{"preload": "auto"}'
                        controls
                        playsinline
                        style="border-radius: 8px; width: 100%; min-height: 400px;">
                          <source src="./publico/video/posts/<?= $data[0]->video; ?>#t=2" 
                          type="video/<?= @end(explode('.', $data[0]->video)); ?>"/>
                        </video>
                      </div>
                    <?php
                  endif;
                  if ($data[0]->youtube != ''):
                    eco("
                      <div class='mt-3'>
                        <iframe id='youtube-frame' style='border-radius: 8px;' 
                        width='100%' 
                        height='400px' 
                        src='{$data[0]->youtube}' 
                        frameborder='0' 
                        allow='autoplay; 
                        encrypted-media' 
                        allowfullscreen></iframe>
                      </div>
                    ");
                  endif;
                ?>
                
                <i><h5 class="text-right mt-3">Publicado aos: <small><?= $data[0]->registo; ?></small></h5></i>

                <div class="lead mt-3"><?= $data[0]->descricao; ?></div>
                <div>
                  <?php
                    //Comentarios
                    $comentarios = new Comentarios();
                    $comentarios->post = $data[0]->id;
                    $comentarios->getComentarios();
                  ?>
                </div>
              
              </div>
            <?php
          else:
            eco('<br><p class="text-center lead mt-4">Nenhum resultado encontrado!</p>');
          endif;
        ?>
        </div>

        <div class="col-12">

            <br><br>

            <h4 style="font-weight: 100!important;">Poderá também gostar</h4>
            <hr>

            <div>
                <div class="row owl-home owl-carousel owl-theme">

                <?php
                  $selectSugestion = DB\Mysql::select(
                    'SELECT * FROM blog ORDER BY rand() LIMIT 9',
                    []
                  );
                  if(is_array($selectSugestion)){
                    foreach ($selectSugestion as $key => $value):
                      eco('<div class="col-12 p-2">');
                        postData($value);
                      eco('</div> ');
                    endforeach;
                  }
                ?>

                </div>
            </div>

          <br>

        </div>

      </div>
    </div>

  </div>
</div>

<script src="./publico/js/owl.carousel.min.js"></script>
<script>
$('.owl-home').owlCarousel({
    center: false,
    loop: true,
    items: 3,
    margin: 0,
    stagePadding: 0,
    smartSpeed: 450,
    autoplay:true,
    autoplayTimeout: 5000,
    autoplayHoverPause: false,
    nav: false,
    dots: true,
    responsive:{
    0:{
        items: 1,
        center:true,
    },
    600:{
        items: 2,
        center:true,
    },
    1000:{
        items: 2,
        center:true,
    }
    }
});
</script>
<script src="publico/js/video.js"></script>