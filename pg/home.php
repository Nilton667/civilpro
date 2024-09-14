<style>
    .carousel.home img.alter{
        height: calc(100vh / 2 + 100px)!important;
        object-fit: cover;
        object-position: center;
        cursor: default;
        transition: opacity 0.5s;
    }
</style>
<div id="carouselExampleCaptions" class="carousel slide home" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="publico/img/1.jpeg" class="d-block w-100 alter" alt="...">
      <div style="position: absolute; width: 100%; top: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>
      <div class="carousel-caption d-none d-md-block">
        <h1 style="text-shadow: 5px 5px 7px rgba(0,0,0,0.7);">Bem vindo a CivilPro</h1>
        <h2 style="text-shadow: 7px 7px 12px rgba(0,0,0,0.9); color: #fafafa;">Atuamos no mercado da construção civil nas áreas de gestão de obra, planejamento, orçamentos e construção, com QUALIDADE, RESPONSABILIDADE e COMPROMETIMENTO, buscando a excelência nos trabalhos.</h2>
      </div>
    </div>
    <div class="carousel-item">
      <img src="publico/img/2.jpg" class="d-block w-100 alter" alt="...">
      <div style="position: absolute; width: 100%; top: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>
      <div class="carousel-caption d-none d-md-block">
        <h1 style="text-shadow: 5px 5px 7px rgba(0,0,0,0.7);">CivilPro - Engenharia & Gestão</h1>
        <h2 style="text-shadow: 7px 7px 12px rgba(0,0,0,0.9); color: #fafafa;">Projetamos uma visão real do seu imóvel para ajuda-lo a se preocupar apenas com as coisas mais simples.</h2>
      </div>
    </div>
    <div class="carousel-item">
      <img src="publico/img/3.jpg" class="d-block w-100 alter" alt="...">
      <div style="position: absolute; width: 100%; top: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>
      <div class="carousel-caption d-none d-md-block">
        <h1 style="text-shadow: 5px 5px 7px rgba(0,0,0,0.7);">CivilPro</h1>
        <h2 style="text-shadow: 7px 7px 12px rgba(0,0,0,0.9); color: #fafafa;">Existimos profissionalmente com orgulho focados no trabalho em equipe como técnicos, comprometidos com rigor e responsabilidade no cumprimento eficiente e eficaz das obrigações.</h2>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <br><br>
                    <h4 class="mb-2 text-center" style="color: #666;"><b>CATEGORIAS</b></h4>
                <br><br>
            </div>
        </div>
        <div  class="row owl-categorias owl-carousel owl-theme">
            <?php
                $categoria_artigos = DB\Mysql::select(
                'SELECT * FROM categoria ORDER BY rand() LIMIT 3',
                []
                );
                if(is_array($categoria_artigos)){
                foreach ($categoria_artigos as $key => $value):

                    $imagem_ = is_file("./publico/img/categorias/{$value['imagem']}") 
                    ? "./publico/img/categorias/{$value['imagem']}" 
                    : "./publico/img/categorias/default.png";

                    ?>
                    <div class="col-12 mb-3 p-3">
                        <a href="./categoria/<?= $value['categoria']; ?>">
                        <div style="color: #fff; background: url('<?= $imagem_; ?>'); height: calc(100vh / 3); background-size: cover; background-position: center; display: flex; justify-content: center; align-items: center;">
                            <h3 style="text-shadow: 6px 6px 6px rgba(0, 0, 0, 0.4);" 
                            class="p-2 text-center"><b><?= $value['categoria']; ?></b></h3>
                        </div>
                        </a>
                    </div>
                    <?php
                endforeach;
                }
            ?>
        </div>
        <div class="text-center">
            <?php
                if(is_array($categoria_artigos) && count($categoria_artigos) >= 3){
                    ?>
                    <br>
                    <a 
                    href="./categorias" 
                    class="btn btn-outline-danger" 
                    style="font-size: 12px;">VER TODAS</a> 
                    <?php
                }
            ?>
        </div>
    </div>
</div>

<div class="mt-4 blog-post-home-data">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <br><br>
                    <h4 id="blog" class="mb-2 text-center" style="color: #666;"><b>BLOG POSTS</b></h4>
                <br><br>
            </div>
            <?php
                $_blogPosts = DB\Mysql::select(
                    "SELECT * FROM blog WHERE estado = 1 ORDER BY id DESC LIMIT 30",
                    []
                );
                if(is_array($_blogPosts)):
                    foreach ($_blogPosts as $key => $value) {
                        eco("<div class='col-12 col-sm-6 col-md-4' data-id='".$value['id']."'>");
                            postData($value);
                        eco("</div>");
                    }
                else:
                    ?>
                    <style type="text/css">
                        .blog-post-home-data{
                            display: none!important;
                        }
                    </style>
                    <?php
                endif;
            ?>
        </div>
    </div>
</div>

<br>

<div class="offset-image">
	<div class="home">
	    <div class="container pt-4 pb-4">
	        <div class="row">
	            <div class="col-12">
                    <br><br>
                        <h4 id="blog" class="mb-2 text-center" style="color: #666;"><b>SERVIÇOS</b></h4>
                    <br><br>
	            </div>
	        </div>
	        <div class="row home-service justify-content-left">
	            <div class="col-12 col-sm-6 col-md-3 pt-3">
	            	<a href="./servicos/#construcao" style="color: inherit;">
	                <div data-aos="flip-up" class="media shadow p-2">
	                    <div class="media-body">
                    	<center>
       	                    <i class="las la-city display-4" style="color: #666;"></i>
                    		<h5 class="m-3">Construção Civil</h5>
                    	</center>
	                    <hr>
	                    <p class="m-3">Construção civil é o termo que engloba a confecção de obras como casas, edifícios, pontes, barragens, fundações de máquinas, estradas, aeroportos...</p>
	                    </div>
	                </div>
	            	</a>
	            </div>
	            <div class="col-12 col-sm-6 col-md-3 pt-3">
	                <a href="./servicos/#fiscalizacao" style="color: inherit;">
	                <div data-aos="flip-up" class="media shadow p-2">
	                    <div class="media-body">
                    	<center>
                    		<i class="las la-ban display-4" style="color: #666;"></i>
                    		<h5 class="m-3">Fiscalização</h5>
                    	</center>
	                    <hr>
	                    <p class="m-3">A fiscalização de obra consiste no serviço de verificação da real e efectiva conformidade da construção com as definições de todos os projectos de licenciamento e execução...</p>
	                    </div>
	                </div>
	            	</a>
	            </div>
	        </div>
	    </div> 
	</div>
</div>

<div>
    <div class="container pt-4 pb-4">
        <div class="row">
            <div class="col-12">
                <br><br>
                    <h4 id="blog" class="mb-2 text-center" style="color: #666;"><b>OUTROS SERVIÇOS</b></h4>
                <br><br>
            </div>
        </div>
        <div class="row home-service justify-content-left">
            <div class="col-12 col-sm-6 col-md-3 pt-3">
                <a href="./ledyboy" style="color: inherit;">
                <div data-aos="flip-up" class="media shadow p-2">
                    <div class="media-body">
                    <center>
                        <i class="las la-glass-cheers display-4" style="color: #666;"></i>
                        <h5 class="m-3">LedyBoy</h5>
                    </center>
                    <hr>
                    <p class="m-3">Casa de vinhos, bebidas alcoólicas e não alcoólicas. Temos serviços de entregas...</p>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3 pt-3">
                <a href="./tleva" style="color: inherit;">
                <div data-aos="flip-up" class="media shadow p-2">
                    <div class="media-body">
                    <center>
                        <i class="las la-taxi display-4" style="color: #666;"></i>
                        <h5 class="m-3">T'leva</h5>
                    </center>
                    <hr>
                    <p class="m-3">Serviço de taxi personalizado disponível 24/24...</p>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3 pt-3">
                <a href="./acabamentos" style="color: inherit;">
                <div data-aos="flip-up" class="media shadow p-2">
                    <div class="media-body">
                    <center>
                        <i class="las la-industry display-4" style="color: #666;"></i>
                        <h5 class="m-3">Acabamentos e Acessórios</h5>
                    </center>
                    <hr>
                    <p class="m-3">Acabamento é o resultado de um ato finalizador de um trabalho de construção civil...</p>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>
    <br> 
</div>

<div class="container">
    <div class="row">
        <div class="col-12 col-md-6 mb-4">
            <div 
            data-aos="fade-left" 
            class="p-4 shadow" 
            style="background: linear-gradient(to left, #4c4b50, #ff2b34); border-radius: 10px; color: #fff!important;">
                <div class="d-flex pointer" onclick="location.href='./ledyboy';">
                    <img src="publico/img/servicos/ledyboy.png" 
                    class="img-fluid me-2 mb-2" 
                    style="width: 100px; height: 100px; border-radius: 5px!important; object-fit: cover;">
                    <div>
                        <h3 style="color: #fff;">LedyBoy</h3>
                        <p class="lead" style="color: #fff;">Casa de vinhos, bebidas alcoólicas e não alcoólicas. Temos serviços de entregas.</p>                          
                    </div>
                </div>
                <hr>
                <p class="lead">
                    <a style="color: #fff!important;" href="tel:244949214128"><i class="las la-phone"></i> +244 949 214 128</a>                    
                </p>                       
                <p class="lead">
                    <a style="color: #fff!important;" href="https://www.facebook.com/Ledy-Boy-Casa-de-vinhos-100977125357209/?ref=page_internal">
                    <i class="lab la-facebook"></i> Ledy Boy - Casa de vinhos</a>
                </p>
                <p class="lead">
                    <a style="color: #fff!important;" href="https://www.instagram.com/ledyboy_vinhos/">
                    <i class="lab la-instagram"></i> Ledyboy_vinhos</a>
                </p>
            </div>
        </div>

        <div class="col-12 col-md-6 mb-4">
            <div 
            data-aos="fade-right" 
            class="p-4 shadow" 
            style="background: linear-gradient(to left, #4c4b50, #ff2b34); border-radius: 10px; color: #fff!important;">
                <div class="d-flex pointer" onclick="location.href='./tleva';">
                    <img src="publico/img/tleva/t.png" 
                    class="img-fluid me-2 mb-2" 
                    style="width: 100px; height: 100px; border-radius: 5px!important; object-fit: cover;">
                    <div>
                        <h3 style="color: #fff;">T'Leva</h3>
                        <p class="lead" style="color: #fff;">Geramos oportunidades colocando o mundo em movimento.</p>                          
                    </div>
                </div>
                <hr>
                <p class="lead">
                    Serviço de taxi personalizado disponível 24/24.                
                </p> 
                <p class="lead">
                    <a style="color: #fff!important;" href="tel:244933006565"><i class="las la-phone"></i> +244 933 006 565</a>                    
                </p>                        
                <p class="lead">
                    <a style="color: #fff!important;" href="mailto:geral@civilpro.co.ao">
                    <i class="las la-envelope"></i> geral@civilpro.co.ao</a>
                </p>
            </div>
        </div>

        <div class="col-12 col-md-6 mb-4">
            <div 
            data-aos="fade-right" 
            class="p-4 shadow" 
            style="background: linear-gradient(to left, #4c4b50, #ff2b34); border-radius: 10px; color: #fff!important;">
                <div class="d-flex pointer" onclick="location.href='./acabamentos';">
                    <img src="publico/img/acabamento.jpg" 
                    class="img-fluid me-2 mb-2" 
                    style="width: 100px; height: 100px; border-radius: 5px!important; object-fit: cover;">
                    <div>
                        <h3 style="color: #fff;">Acabamentos e Acessórios</h3>
                        <p class="lead" style="color: #fff;">Buscamos ser uma referência no segmento de acabamentos.</p>                          
                    </div>
                </div>
                <hr>
                <p class="lead">
                    Como o próprio nome já sugere acabamento é a etapa de finalização da construção de uma casa, apartamento ou imóvel.             
                </p>
                <div class="row">
                    <div class="col-3">
                        <img data-aos="flip-left" style="max-width: 100%; max-height: 100px; height: 100%; width: 100%; object-fit: cover; border-radius: 5px;" 
                        class="gallery-image" 
                        src="publico/img/acabamentos/7.JPG">
                    </div>
                    <div class="col-3">
                        <img data-aos="flip-left" style="max-width: 100%; max-height: 100px; height: 100%; width: 100%; object-fit: cover; border-radius: 5px;" 
                        class="gallery-image" 
                        src="publico/img/acabamentos/8.JPG">
                    </div>
                    <div class="col-3">
                        <img data-aos="flip-left" style="max-width: 100%; max-height: 100px; height: 100%; width: 100%; object-fit: cover; border-radius: 5px;" 
                        class="gallery-image" 
                        src="publico/img/acabamentos/6.JPG">
                    </div>
                    <div class="col-3">
                        <img data-aos="flip-left" style="max-width: 100%; max-height: 100px; height: 100%; width: 100%; object-fit: cover; border-radius: 5px;" 
                        class="gallery-image" 
                        src="publico/img/acabamentos/6.JPG">
                    </div>
                </div> 
            </div>
        </div>
        
    </div>

</div>

<div id="sobre">
	<div class="container pt-4 pb-4">
		<div class="row">
			<div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
				<img class="mb-4 mb-md-0" src="publico/img/sobre.jpg" style="max-width: 100%; border-radius: 10px; filter: blur(0.8px);">
			</div>
			<div data-aos="fade-left" class="col-12 col-md-6">
				<div class="p-4 shadow" style="background: linear-gradient(to left, #4c4b50, #ff2b34); border-radius: 10px; color: #fff;">
					<h2 style="color: #ffffff;">SOBRE NÓS</h2>
					<p class="lead" style="text-shadow: 5px 5px 5px rgba(0, 0, 0, 0.3);">Somos uma empresa angolana de direito privado, criado com capital próprio registado na Conservatório do Registo Comercial de Luanda 2ª Secção do Guiché Único de Empresa nº 1924/14. Com sede em Luanda, Rua Major Cahangulo, Bairro Patrice Lumumba, Distrito Urbano da Ingombota e filial na Província do Uíge com o Número de Identificação Fiscal 5417283525.</p>					
				</div>
				<br>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid pl-md-0">
	<br>
	<div class="row">
		<div class="col-12 col-md-8">
			<div 
			class="paralax" 
			style="background-image: url(publico/img/home/paralax.jpeg); border-radius: 10px!important;">
				<div 
				style="background: linear-gradient(to left, #4c4b50, #ff2b34); position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.7; border-radius: 10px!important;"></div>
				<br><br>
				<br><br>
				<br><br>
				<br><br>
			</div>
		</div>
		<div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
			<div>
				<center>
					<p class="display-4" style="text-align: left!important;">Os melhores serviços <span style="color: #ffc107;">No melhor preço possível.</span></p>
				</center>
			</div>
		</div>
	</div>
	<br>
</div>

<div class="offset-image" id="missao">
	<div class="container-fluid pt-4 pb-4">
		<div class="row justify-content-center">
			<div class="col-12 col-md-4">
				<br>
				<div data-aos="fade-right" 
				class="p-4 shadow" 
				style="background: linear-gradient(to left, #4c4b50, #ff2b34); border-radius: 10px; color: #fff;">
					<h2 style="color: #ffffff;">MISSÃO</h2>
					<p class="lead" style="text-shadow: 5px 5px 5px rgba(0, 0, 0, 0.3);">Suprir as necessidades e expectativas dos clientes na utilização de serviços em todo território nacional, criando oportunidade para actuação em outras áreas, sem esquecer a sua participação quanto a responsablidade social na comunicação a fim de que as organizações atinjam a condição de um ambiente de alto desempenho e de legado positivo para a sociedade.</p>
				</div>
			</div>
			<div class="col-12 col-md-5 d-flex justify-content-center align-items-center">
				<img class="mt-4 mt-md-0" src="publico/img/missao.jpg" style="max-width: 100%; border-radius: 10px; filter: blur(0px);">
			</div>
		</div>
	</div>
</div>

<div id="visao">
	<br>
	<div class="container pt-4 p-4">
		<div class="row">
			<div class="col-12">
				<div data-aos="fade-up" 
				class="p-4 shadow" 
				style="background: linear-gradient(to left, #4c4b50, #ff2b34); border-radius: 10px; color: #fff;">
					<h2 style="color: #ffffff;" class="m-3">VISÃO</h2>
					<blockquote class="p-1">
						<p class="lead pl-3">Ser uma empresa de referência no desenvolvimento do potencial humano por meio do cultivo de valores e princípios universais e estabelecimento de culturas organizacionais baseadas no encorajamento e cooperação.</p>
					</blockquote>
				</div>
			</div>
		</div>
	</div>
	<br>
</div>

<div class="offset-image" id="valores">
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-6">
					<h2 class="text-center">VALORES</h2>
                <hr>
            </div>
        </div>
        <div>
            <div class="row owl-home owl-carousel owl-theme m-0">
                <div class="col-12">
                    <div class="item">
                        <div class="mb-4">
                            <center>
								<i class="las la-user-check display-4"></i>                              
                            </center>
                        </div>
                        <h5 class="text-center m-3">Quantidade e qualidade dos serviços prestados</h5>
                        <p class="text-center m-3">Garantimos um serviço de qualidade rapido e eficiente.</p>   
                    </div>
                </div>

                <div class="col-12">
                    <div class="item">
                        <div class="mb-4">
                            <center>
							<i class="las la-user-alt display-4"></i>
							</center>                          
                        </div>
                        <h5 class="text-center m-3">Satisfação ao cliente</h5>
                        <p class="text-center m-3">Os nossos clientes são a nossa maior preocupação por esta razão garantimos a sua satisfação em todos os nossos serviços.</p>  
                    </div>
                </div>

                <div class="col-12">
                    <div class="item">
                        <div class="mb-4">
                            <center>
								<i class="las la-user-friends display-4"></i>                            
                            </center>                            
                        </div>
                        <h5 class="text-center m-3">Bom ambiente de trabalho e espírito de equipa</h5>
                        <p class="text-center m-3">Motivar e garantir o bem estar dos colaboradores é muito importante para que eles tenham o melhor desempenho possível.</p>  
                    </div>         
				</div>

                <div class="col-12">
                    <div class="item">
                        <div class="mb-4">
                            <center>
								<i class="las la-user-friends display-4"></i>                            
                            </center>                            
                        </div>
                        <h5 class="text-center m-3">Responsabilidade social</h5>
                        <p class="text-center m-3">Total cumprimento dos deveres e obrigações com a sociedade em geral.</p>  
                    </div>         
                </div>
                
            </div>

        </div>
    </div>
    <br>
</div>

<script src="./publico/js/owl.carousel.min.js"></script>
<script>
$('.owl-categorias').owlCarousel({
    loop: true,
    items: 3,
    margin: 0,
    stagePadding: 0,
    smartSpeed: 450,
    autoplay:true,
    autoplayTimeout: 3000,
    autoplayHoverPause:true,
    nav: true,
    dots: true,
    responsive:{
        0:{
            items:1,
            nav:false,
            center:true,
        },
        600:{
            items:2,
            nav:false,
            center:true,
        },
        1000:{
            items:3,
            nav:false,
            center:true,
        }
    }
});
$('.owl-home').owlCarousel({
    center: true,
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
        items:1,
        center:true,
    },
    600:{
        items:2,
        center:true,
    },
    1000:{
        items:3,
        center:true,
    }
    }
});
</script>