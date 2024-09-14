<?php

  include_once 'api/conexao.php';

  //Get Definições
  $getDef         = Components\jsonReader((__DIR__.'/../app.json'), true);
  //Get Definições do sistema
  $getSystem      = Components\jsonReader((__DIR__.'/prefs/system.json'), true);
  //Get Definições de email
  $getSystemEmail = Components\jsonReader((__DIR__.'/prefs/email.json'), true);


  
  function getCliente($id, $index = '')
  {
      try {
          $SELECT = "SELECT * FROM usuarios WHERE id = :id";
          $result = Conexao::getCon(1)->prepare($SELECT);
          $result->bindParam(':id', $id, PDO::PARAM_STR);
          $result->execute();
          $contar = $result->rowCount();
          if ($contar > 0):
            $data = $result->fetchAll();
            if($index == ''){
              return json_encode($data);
            }else{
              return json_encode($data[0][$index]);
            }
          else:
            return json_encode('Nenhum usuário encontrado!');
          endif;
      } catch (\Throwable $th) {
        return json_encode($th);
      }
  }
  
  function getCursoId($id, $index = '')
  {
      try {
          $SELECT = "SELECT * FROM curso WHERE id = :id";
          $result = Conexao::getCon(1)->prepare($SELECT);
          $result->bindParam(':id', $id, PDO::PARAM_STR);
          $result->execute();
          $contar = $result->rowCount();
          if ($contar > 0):
            $data = $result->fetchAll();
            if($index == ''){
              return json_encode($data);
            }else{
              return json_encode($data[0][$index]);
            }
          else:
            return json_encode('Nenhum usuário encontrado!');
          endif;
      } catch (\Throwable $th) {
        return json_encode($th);
      }
  }
  
  function getTour($id, $index)
  {
      try {
          $SELECT = "SELECT * FROM city WHERE id = :id";
          $result = Conexao::getCon(1)->prepare($SELECT);
          $result->bindParam(':id', $id, PDO::PARAM_STR);
          $result->execute();
          $contar = $result->rowCount();
          if ($contar > 0):
            $data = $result->fetchAll();
            if($index == ''){
              return json_encode($data);
            }else{
              return json_encode($data[0][$index]);
            }
          else:
            return json_encode('Nenhum tour encontrado!');
          endif;
      } catch (\Throwable $th) {
        return json_encode($th);
      }
  }
  
  function getServico($id, $index)
  {
      try {
          $SELECT = "SELECT * FROM servicos WHERE id = :id";
          $result = Conexao::getCon(1)->prepare($SELECT);
          $result->bindParam(':id', $id, PDO::PARAM_STR);
          $result->execute();
          $contar = $result->rowCount();
          if ($contar > 0):
            $data = $result->fetchAll();
            if($index == ''){
              return json_encode($data);
            }else{
              return json_encode($data[0][$index]);
            }
          else:
            return json_encode('Nenhum serviço encontrado!');
          endif;
      } catch (\Throwable $th) {
        return json_encode($th);
      }
  }
  
  //Controlo de sessão
  class Control
  {
    private $pagina;

    function __construct($pagina)
    {
      $this->pagina = $pagina;
    }
  
    static function isAccessible($id, $token, $tempo)
    {
      try {
          $SELECT = "SELECT id_adm, tempo FROM acesso WHERE id_adm = :id AND token = :token";
          $result = Conexao::getCon(1)->prepare($SELECT);
          $result->bindParam(':id', $id, PDO::PARAM_INT);
          $result->bindParam(':token', $token, PDO::PARAM_STR);
          $result->execute();
          $contar = $result->rowCount();
          if ($contar > 0){
  
            $data = $result->fetchAll();
            $now  = date('Y-m-d');
            $time = strtotime(date('Y-m-d', strtotime(str_replace('/', '-', $data[0]['tempo'])))) - strtotime($now);
  
            if($data[0]['tempo'] != $tempo){
              header('location: ./?sair');
            }else if($time < 0){
              header('location: ./?sair');
            }
            
            return DB\Mysql::get("adm", "*", "id = {$data[0]['id_adm']}", 1);
  
          }else{
            return 'Acesso negado!';              
          }
      } catch (\Throwable $th) {
        return $th;
      }
    }

    function permission()
    {
      if($this->pagina == 'login'){
        if(Components\getSession('maestro_adm')){
          header('location: ./');
          exit();
        }
      }else if($this->pagina == 'index'){
        if(!Components\getSession('maestro_adm')){
          header('location: ./login');
          exit();
        }else{
          $session = Components\getSession('maestro_adm');
          if(isset($session['id']) && isset($session['token'])){
            return 1;
          }else{
            return false;
          }
        }
      }else{
        header('location: ./404');
        exit();  
      }
    }
  }

  //Terminar sesssão
  if(request('sair')):
    unset($_SESSION['maestro_adm']);
    header('location: ./login');
    exit();
  endif;

  //Paginação
  class Paginacao extends Conexao{

    public  $queryString;
    public  $select;
    public  $quantidade;
    public  $pg;
    private $filtro;

    function __construct(){
      $this->filtro = get('filtro') 
      ? '&filtro='.filterVar(get('filtro')) 
      : '';
    }

    function getPaginacao(){
      try{
        $result = Conexao::getCon(1)->prepare($this->select);
        $result ->execute();
        $contar = $result->rowCount();
        if ($contar > $this->quantidade) {
          $paginas = ceil($contar/$this->quantidade);
          $links   = 2;
          if(!isset($i)): $i = 1; endif;
          ?>
          <nav>
            <ul class="pagination justify-content-center mb-0">
              <li class="page-item">
                <a class="page-link" 
                  href="./?<?php echo $this->queryString.$this->filtro; ?>&pg=<?php echo @($this->pg-1);?>" tabindex="-1">
                  <i class="las la-angle-left"></i>
                </a>
              </li>
              <?php

                for($i = $this->pg-$links; $i <= $this->pg-1; $i++):
                  if($i <= 0): continue; endif;
                  echo trim('
                    <li class="page-item">
                      <a class="page-link" href="./?'.$this->queryString.$this->filtro.'&pg='.$i.'">'.$i.'</a>
                    </li>
                  ');
                endfor;
              
                echo trim('
                  <li class="page-item active">
                    <a class="page-link" href="javascript:void(0)">'.$this->pg.'</a>
                  </li>
                ');
              
                for($i = $this->pg+1; $i <= $this->pg+$links; $i++):
                  if($i > $paginas): continue; endif;
                  echo trim('
                    <li class="page-item">
                        <a class="page-link" href="./?'.$this->queryString.$this->filtro.'&pg='.$i.'">'.$i.'</a>
                    </li>
                  ');
                endfor;
                
                if($paginas >= ($this->pg+1)):
                  ?>
                  <li class="page-item">
                    <a class="page-link" 
                      href="./?<?php echo $this->queryString.$this->filtro; ?>&pg=<?php echo @($this->pg+1);?>">
                      <i class="las la-angle-right"></i>
                    </a>
                  </li>                  
                  <?php
                endif;

              ?>
            </ul>
          </nav>
          <?php
        }
      }catch(Exception $error){
        return '<p class="text-center lead">'.$error.'!</p>';
      }
    }
  }

  //Comentarios
  Class Comentarios extends Conexao{

    public $post;

    function getComentarios()
    {

      $select = DB\Mysql::select(
        'SELECT * FROM blog_comentarios WHERE id_post = :id_post ORDER BY id DESC LIMIT 50',
        [
          'id_post' => $this->post, 
        ]
      );

      ?>
        <script src="assets/js/feed/comentarios.js"></script>
        <br>
        <h2>Comentarios</h2>
        <br>
      <?php
      if(is_array($select)){
        eco('<div>');
        foreach ($select as $key => $comment){
          ?>
          <div 
          class="d-flex w-100 shadow-sm p-2 <?php if($key > 0){ ?> mt-3 <?php } ?>" 
          style="border-radius: 8px; background: #f3f3f3;">

            <!-- Foto de perfil -->
            <div class="pl-2 pr-2">
              <img 
                src="../publico/img/perfil/<?= eco($this->getUserData($comment['id_usuario'], 'imagem')); ?>" 
                style="width: 50px; height: 50px; border-radius: 50px;"
              >
            </div>

            <!-- Dados dos comentario -->
            <div class="w-100">
              
              <p class="mb-1"><?= eco($this->getUserData($comment['id_usuario'], 'nome', $comment['nome'])); ?></p>
              
              <p class="p-2 m-0 shadow-sm" style="background-color: #ebe9ee; border-radius: 12px; cursor: default;">
                <?= $comment['comentario']; ?>
              </p>

              <p style="font-size: 14px;" class="mb-0 mt-2">
                <i class="las la-clock"></i> 
                <small><?= $comment['registo']; ?></small>
                <span>•</span>
                <small><a href="javascript:void(0)" class="comment-remove" data-id="<?= $comment['id']; ?>">Remover</a></small>
              </p>
              
              <!-- Respostas -->
              <?php
                $subComentarios = $this->subComment($comment['id']);
                if(is_array($subComentarios)){
                  foreach ($subComentarios as $key => $subComment) {
                    ?>
                    <div class="mt-3" style="border-left: #007bff 5px solid; padding-left: 12px;">
                      <div class="d-flex">

                        <!-- Foto de perfil -->
                        <div class="pl-2 pr-2">
                          <img 
                            src="../publico/img/perfil/<?= eco($this->getUserData($subComment['id_usuario'], 'imagem')); ?>" 
                            style="width: 40px; height: 40px; border-radius: 40px;"
                          >
                        </div>

                        <div class="w-100">
                          <p class="p-2 m-0 shadow-sm" style="background-color: #ebe9ee; border-radius: 16px; cursor: default;">
                            <?= $subComment['comentario']; ?>
                          </p>
                          <p style="font-size: 14px;" class="mb-0 mt-2">
                            <i class="las la-clock"></i> 
                            <small><?= $subComment['registo']; ?></small>
                            <span>•</span>
                            <small><a href="javascript:void(0)" class="sub-comment-remove" data-id="<?= $subComment['id']; ?>">Remover</a></small>
                          </p>
                        </div>

                      </div>
                    </div>
                    <?php
                  }
                }
              ?>

            </div>

          </div>
        <?php
        }
        eco('</div>');
      }else{
        ?>
        <p class="lead text-center">Seja o primeiro a comentar!</p>
        <?php
      }

    }

    function getUserData($id, $data, $nome = '')
    {
      if($id == null || $id == 0 || $id == ''){
        if($data == 'nome'){
          return $nome == '' ? 'Anônimo' : $nome;
        }else{
          return 'user.png';
        }
      }else{
        $select = DB\Mysql::select(
          'SELECT nome, sobrenome, imagem FROM usuarios WHERE id = :id LIMIT 1',
          [
            'id' => $id, 
          ]
        );
        if($data == 'nome'){
          return is_array($select) 
          ? $select[0]['nome'].' '.$select[0]['sobrenome'] 
          : 'Anônimo';
        }else{
          return is_array($select) && $select[0]['imagem'] != '' 
          ? $select[0]['imagem'] 
          : 'user.png';
        }
      }
    }

    function subComment($id)
    {
      $select = DB\Mysql::select(
        'SELECT * FROM blog_sub_comentarios WHERE id_comentario = :id_comentario ORDER BY id ASC LIMIT 20',
        [
          'id_comentario' => $id, 
        ]
      );

      if(is_array($select)){
        return $select;
      }else{
        return 0;
      }
    }

  }

  //Utilidade
  class Util extends Conexao{
    private $id;
    private $post_id;
    public $filter;

    function __construct()
    {
      $session      = unserialize($_SESSION['maestro_adm']);
      if(!isset($session['id'])){
          return json_encode('Usuário não encontrado!');
      }else{
          $this->id = $session['id'];
      }

      if(isset($_GET['post']) && is_numeric(trim($_GET['post']))){
        $this->post_id = trim(strip_tags($_GET['post']));

      }else if(isset($_GET['view']) && is_numeric(trim($_GET['view']))){
        $this->post_id = trim(strip_tags($_GET['view']));

      }else if(isset($_GET['curso']) && is_numeric(trim($_GET['curso']))){
        $this->post_id = trim(strip_tags($_GET['curso']));

      } else{
        $this->post_id = isset($_GET['edit']) && is_numeric(trim($_GET['edit']))
        ? trim(strip_tags($_GET['edit']))
        : DEFAULT_INT;
      }
    }

    function getData(){

      $SELECT = "SELECT * FROM blog WHERE id = :post_id LIMIT 1";

      try {
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':post_id', $this->post_id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      }
    }
  
    function getDataImoveis(){

      $SELECT = "SELECT * FROM imoveis WHERE id = :post_id LIMIT 1";

      try {
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':post_id', $this->post_id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      }
    }

    function getDataServicos(){

      $SELECT = "SELECT * FROM servicos WHERE id = :post_id LIMIT 1";

      try {
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':post_id', $this->post_id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      }
    }

    function getArtigo(){

      $SELECT = "SELECT * FROM artigos WHERE id = :post_id LIMIT 1";

      try {
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':post_id', $this->post_id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      }
    }

    function getCity(){

      $SELECT = "SELECT * FROM city WHERE id = :post_id LIMIT 1";

      try {
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':post_id', $this->post_id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      }
    }
    
    function getCategoria()
    {
      try {
          $SELECT = "SELECT * FROM categoria ORDER BY id DESC";
          $result = Conexao::getCon(1)->prepare($SELECT);
          $result->execute();
          $contar = $result->rowCount();
          if ($contar > 0) {
            $data = $result->fetchAll();
            return json_encode($data);
          }else{
            return json_encode(0);
          }
      } catch (\Throwable $th) {
          return json_encode($th);
      } 
    }

    function getCategoriaServicos()
    {
      try {
          $SELECT = "SELECT * FROM servicos_categoria ORDER BY id DESC";
          $result = Conexao::getCon(1)->prepare($SELECT);
          $result->execute();
          $contar = $result->rowCount();
          if ($contar > 0) {
            $data = $result->fetchAll();
            return json_encode($data);
          }else{
            return json_encode(0);
          }
      } catch (\Throwable $th) {
          return json_encode($th);
      } 
    }

    function getsubcategoria()
    {

      if($this->filter != '' && $this->filter != null && $this->filter != DEFAULT_STRING):
        $SELECT = "SELECT subcategoria FROM subcategoria WHERE categoria = :categoria ORDER BY id DESC";
      else:
        $SELECT = "SELECT subcategoria FROM subcategoria ORDER BY id DESC";
      endif;

      try {
        $result = Conexao::getCon(1)->prepare($SELECT);
        if($this->filter != '' && $this->filter != null && $this->filter != DEFAULT_STRING):
          $result->bindParam(':categoria', $this->filter, PDO::PARAM_STR);
        endif;
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      } catch (\Throwable $th) {
        return json_encode($th);
      } 

    }

    function getCor()
    {
      try {
        $SELECT = "SELECT * FROM cor ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }
  
    function getMarca()
    {
      try {
        $SELECT = "SELECT * FROM marca ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }

    function getTamanho()
    {
      try {
        $SELECT = "SELECT * FROM tamanho ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }

    function getDormitorio()
    {
      try {
        $SELECT = "SELECT * FROM dormitorio ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }

    function getPavimento()
    {
      try {
        $SELECT = "SELECT * FROM pavimento ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }

    function getAreaConstruida()
    {
      try {
        $SELECT = "SELECT * FROM area_construida ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }

    function getBanheiro(){

      $SELECT = "SELECT * FROM banheiros";

      try {
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      }
    }

    function getGaragem(){

      $SELECT = "SELECT * FROM garagem";

      try {
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      }
    }

    function getVideoPost($id)
    {
      try {
        $SELECT = "SELECT video FROM blog WHERE id = :id ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0){
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }

    function getImageArtigos($id)
    {
      try {
        $SELECT = "SELECT imagem FROM artigos_imagem WHERE id_artigo = :id ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0){
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }

    function getImageImoveis($id)
    {
      try {
        $SELECT = "SELECT imagem FROM imoveis_imagem WHERE id_imovel = :id ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0){
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }

    function getFileCurso($id)
    {
      try {
        $SELECT = "SELECT id, titulo, file FROM cursos_file WHERE id_curso = :id ORDER BY id ASC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0){
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th){
        return json_encode($th);
      } 
    }

    function getGaleriaImagens($id)
    {
      try {
        $SELECT = "SELECT imagem, localizacao_id FROM galeria_imagem WHERE id_galeria = :id ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0){
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      } 
    }
  
    function getCurso(){

      $SELECT = "SELECT * FROM curso WHERE id = :post_id LIMIT 1";

      try {
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':post_id', $this->post_id, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if ($contar > 0) {
          $data = $result->fetchAll();
          return json_encode($data);
        }else{
          return json_encode(0);
        }
      }catch (\Throwable $th) {
        return json_encode($th);
      }
    }

  }

  //Formato de moeda
  function get_moeda($moeda)
	{
    $formato  = 0;
    $sigla    = 'AOA';
    $direcao  = 0;

    if (!empty($sigla)): $espaco = ' '; else: $espaco = ''; endif;
    
    if ($formato == 0 OR $formato > 3):
      if($direcao == 1) { 
        return $sigla.$espaco.number_format($moeda, 2,',','.');
      }else{
        return number_format($moeda, 2,',','.').$espaco.$sigla;
      }
    elseif($formato == 1):
      if ($direcao == 1){
        return $sigla.$espaco.number_format($moeda, 2,'.',',');
      }else{
        return number_format($moeda, 2,'.',',').$espaco.$sigla;
      }
    elseif($formato == 2):
      if ($direcao == 1){
        return $sigla.$espaco.number_format($moeda, 2, ',', ' ');
      }else{
        return number_format($moeda, 2,',', ' ').$espaco.$sigla;
      }
    elseif ($formato == 3):
      if ($direcao == 1){
        return $sigla.$espaco.number_format($moeda, 2, '.', '');
      }else{
        return number_format($moeda, 2,'.', '').$espaco.$sigla; 
      }
    endif;
  }

  /*Calendario*/
  function diasMeses()
  {
    $data   = [];
    for ($i = 1; $i <= 12; $i++):
      $data[$i] = cal_days_in_month(CAL_GREGORIAN, $i, date('Y'));
    endfor;
    return $data;
  }

  function calendario($header, $selected, $event)
  {
    
    $selected = explode(',', $selected);
    $key      = array_search('', $selected);
    if($key !== false){
      unset($selected[$key]);
    }

    $daysWeek = [
      'Sun',
      'Mon',
      'Tue',
      'Wed',
      'Thu',
      'Fri',
      'Sat'
    ];

    $diasSemana = [
      'D',
      'S',
      'T',
      'Q',
      'Q',
      'S',
      'S'
    ];

    $meses = [
      1  => 'Janeiro',
      2  => 'Fevereiro',
      3  => 'Março',
      4  => 'Abril',
      5  => 'Maio',
      6  => 'Junho',
      7  => 'Julho',
      8  => 'Agosto',
      9  => 'Setembro',
      10 => 'Outubro',
      11 => 'Novembro',
      12 => 'Dezembro',
    ];

    $diasMeses = diasMeses();
    $data      = [];

    for($i = 1; $i <= 12; $i++):
      $data[$i] = [];
      for ($n   = 1; $n <= $diasMeses[$i]; $n++):
        $dayMonth  = GregorianToJD($i, $n, date('Y'));
        $weekMonth = substr(JDDayOfWeek($dayMonth, 1), 0, 3);
        if($weekMonth == 'Mun') : $weekMonth = 'Mon'; endif;
        $data[$i][$n] = $weekMonth;
      endfor;
    endfor;

    ?>
    <div class="cal_gregorian_header">
      <a class="cal-previus" href="javascript:void('Previus')">
        <i class="las la-chevron-left"></i>
      </a>

      <span class="lead">
        <b>
          <span id="cal_gregorian_header">
            <?= isset($meses[date('n')]) ? $meses[date('n')] : 'undefined'; ?>
          </span>
        </b>
      </span>

      <a class="cal-next" href="javascript:void('Next')">
        <i class="las la-chevron-right"></i>
      </a>
    </div>
    <?php
    echo '<table class="table-bordered cal_gregorian">';
    foreach ($meses as $num => $mes){
      $active = $num == date('n') ? 'active' : '';
      echo '<tbody id="mes_'.$num.'" data-mes="'.$num.'" class="cal_mes '.$active.'">';
      echo  $header  == 1 ? '<tr><td scope="col" colspna="7"><b>'.$mes.'</b></td></tr>' : '';
      echo '<tr>';
      foreach ($diasSemana as $i => $day){
        echo '<td><b>'.$day.'</b></td>';
      }
      echo '</tr>';
      echo '<tr>';
      $y = 0;
      foreach ($data[$num] as $numero => $dia){
        $dayActive = in_array(trim($numero.'/'.$num.'/'.date('Y')), $selected) ? 'active' : '';
        $y++;
        if($numero == 1):
          $qtd     = array_search($dia, $daysWeek);
          for ($i  = 1; $i <= $qtd ; $i++):
            echo '<td></td>';
            $y+= 1;
          endfor;
        endif;
        echo trim('
          <td 
          data-day="'.$numero.'" 
          data-month="'.$num.'" 
          data-year="'.date("Y").'" 
          class="cal_gregorian_day '.$event.' '.$dayActive.'">
            '.$numero.'
          </td>
        ');
        if ($y == 7):
          $y = 0;
          echo "<tr></tr>";
        endif;
      }
      echo '</tr>';
      echo '</tbody>';
    }
    echo '</table>';
  }

  //Contador de visitas
  class Visitas extends Conexao{
    private $id, $ip , $data , $hora , $limite;

    #Construtor para setar atributos
    public function __construct()
    {
      $this->ip     = $_SERVER['REMOTE_ADDR'];
      $this->data   = date("Y/m/d");
      $this->hora   = date("H:i");
      $this->limite = 60;
    }

    #Verifica se o usuário recebeu visita recentemente
    public function checkUser($count = true, $visible = false)
    {
      try{
        $SELECT = "SELECT * FROM visitas WHERE ip = :ip and data = :data ORDER BY id DESC";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(":ip", $this->ip,PDO::PARAM_STR);
        $result->bindParam(":data", $this->data,PDO::PARAM_STR);
        $result->execute();
        $contar = $result->rowCount();
        if($contar <= 0 && $count):
          $this->add();
        elseif($contar > 0 && $count):

          $select        = $result->fetch(PDO::FETCH_ASSOC);
          $HoraDB        = strtotime($select['hora']);
          $HoraAtual     = strtotime($this->hora);
          $HoraSubtracao = ($HoraAtual - $HoraDB);
          if($HoraSubtracao > $this->limite):
            $this->add();
          endif;

        endif;
        if($visible): 
          eco($this->read());
        else:
          return $this->read();
        endif;
      }catch(Exception $i){
        //null
      }
    }

    public function read(){
      try{
        $result = Conexao::getCon(1)->prepare('SELECT * FROM visitas');
        $result->execute();
        return $result->rowCount();
      }catch(Exception $i){
        return 0;
      }
    }

    #Inserir a visita no banco de dados
    private function add()
    {
      try{
        $INSERT = "INSERT INTO visitas (ip, data, hora) VALUES (:ip, :data, :hora)";
        $result = Conexao::getCon(1)->prepare($INSERT);
        $result->bindParam(':ip', $this->ip, PDO::PARAM_STR);
        $result->bindParam(':data', $this->data, PDO::PARAM_STR);
        $result->bindParam(':hora', $this->hora, PDO::PARAM_STR);
        $result->execute();
      }catch(Exception $i){
        //null
      }
    }
  }
?>