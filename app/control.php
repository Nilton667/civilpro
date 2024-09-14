<?php

  include_once 'api/conexao.php';

  //Controlo de sessão
  class Control
  {
    private $pagina;

    function __construct($pagina)
    {
      $this->pagina = $pagina;
    }

    function permission()
    {
      if($this->pagina == 'login'){
        if(Components\getSession('maestro')){
          header('location: ./');
          exit();
        }
      }else if($this->pagina == 'index'){
        if(Components\getSession('maestro')){
          $session = Components\getSession('maestro');
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
  if(isset($_REQUEST['sair'])): unset($_SESSION['maestro']); header('location: ./login'); exit(); endif;

  //Paginação
  class Paginacao extends Conexao{

    public  $queryString;
    public  $select;
    public  $quantidade;
    public  $pg;
    private $filtro;

    function __construct(){
      $this->filtro = get('filtro', false) 
      ? '&filtro='.filterVar(get('filtro'), false) 
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
          <div class="col-12 mt-3">
            <div>
              <nav style="background: transparent!important;">
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
            </div>
          </div>
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
        <br>
        <div class="d-flex">
          <input id="comment-id" type="hidden" name="id_post" value="<?= $this->post; ?>">
          <div class="w-100">
            <?php
              if(Components\getSession('maestro') === false):
                eco('
                  <input  style="height: calc(2.25rem + 9px);" id="comment-nome" type="text" name="nome" class="form-control mb-3" placeholder="Seu nome">
                ');
              else:
                eco('
                  <input id="comment-user-id" type="hidden" name="id_usuario" value="'.Components\getSession('maestro', 'id').'">
                ');
              endif;
            ?>
            <textarea id="comment-data" class="w-100 form-control" placeholder="Comentario..." rows="3" style="resize: none;"></textarea>
          </div>
          <div class="pl-1 pr-1">
            <button class="btn btn-outline-primary" id="comment-post" style="height: calc(2.25rem + 8px);">Comentar</button>
          </div>
        </div>
        <script src="publico/js/comentarios.js"></script>
        <br>
        <h2>Comentarios</h2>
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
                src="publico/img/perfil/<?= eco($this->getUserData($comment['id_usuario'], 'imagem')); ?>" 
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
                <?php
                  if(Components\getSession('maestro')){
                    if(Components\getSession('maestro', 'id') == $comment['id_usuario']){
                      ?>
                        <span>•</span>

                        <small>
                          <a href="javascript:void(0)" 
                          class="comment-edit"
                          data-comentario="<?= $comment['comentario']; ?>"
                          data-target="comentario" 
                          data-id="<?= $comment['id']; ?>">Editar</a>
                        </small>

                        <span>•</span>
                        <small><a href="javascript:void(0)" class="comment-remove" data-id="<?= $comment['id']; ?>">Remover</a></small>
                      <?php
                    }
                  }
                ?>
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
                            src="publico/img/perfil/<?= eco($this->getUserData($subComment['id_usuario'], 'imagem')); ?>" 
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
                            <?php
                              if(Components\getSession('maestro')){
                                if(Components\getSession('maestro', 'id') == $subComment['id_usuario']){
                                  ?>
                                    <span>•</span>

                                    <small>
                                      <a href="javascript:void(0)" 
                                      class="sub-comment-edit" 
                                      data-comentario="<?= $subComment['comentario']; ?>" 
                                      data-target="subcomentario" 
                                      data-id="<?= $subComment['id']; ?>">Editar</a>
                                    </small>

                                    <span>•</span>
                                    <small><a href="javascript:void(0)" class="sub-comment-remove" data-id="<?= $subComment['id']; ?>">Remover</a></small>
                                  <?php
                                }
                              }
                            ?>
                          </p>
                        </div>

                      </div>
                    </div>
                    <?php
                  }
                }
              ?>

              <!-- SubComentar -->
              <div>
                <div class="input-group mb-2 mt-2 w-100">
                  <input placeholder="Responder..."
                  type="text"
                  data-sub="<?= $comment['id']; ?>" 
                  class="form-control sub-comment sub-comment-<?= $comment['id']; ?>"
                  style="height: 40px; border-top-left-radius: 16px; border-bottom-left-radius: 16px;">
                  <div class="input-group-append">
                    <span 
                    style="border-top-right-radius: 16px; border-bottom-right-radius: 16px;" 
                    class="input-group-text btn btn-outline-primary sub-comment-add" data-sub="<?= $comment['id']; ?>">
                      <i class="las la-paper-plane"></i>
                    </span>
                  </div>
                </div>
              </div>

            </div>

          </div>

          <!-- Editar comentario e subcomentario -->
          <!-- Modal -->
          <div class="modal fade" id="edit-comment-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-body p-2">
                  <input type="hidden" name="id" id="edit-comment-id" value="">
                  <input type="hidden" name="target" id="edit-comment-target" value="">
                  <textarea name="comentario" id="edit-comment-txt" class="form-control" placeholder="Comentario..." rows="5"></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                  <button onclick="comentarioEdit()" type="button" class="btn btn-primary">Editar</button>
                </div>
              </div>
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
    public $filter;

    function getData($nome, $id)
    {
      $artigo      = explode('-', $nome);
      $filter      = '';
      
      if($id != 0){
        $select = DB\Mysql::select(
          "SELECT * FROM artigos WHERE id = $id LIMIT 1",
          []
        );
      }else{
        
        foreach ($artigo as $key => $data) {
          $and     = count($artigo) > ($key + 1) ? ' AND nome' : '';
          $filter .= " LIKE '%$data%'$and";
        }
        $select = DB\Mysql::select(
          "SELECT * FROM artigos WHERE nome $filter LIMIT 1",
          []
        );

      }

      if(is_array($select)):
        //Return 
        return json_encode($select); 
      else: 
        return json_encode(0); 
      endif;

    }

    function getDataPost($titulo)
    {
      $post      = explode('-', $titulo);
      $filter    = '';
      foreach ($post as $key => $data) {
        $and     = count($post) > ($key + 1) ? ' AND titulo' : '';
        $filter .= " LIKE '%$data%'$and";
      }
      $select = DB\Mysql::select(
        "SELECT * FROM blog WHERE titulo $filter LIMIT 1",
        []
      );

      if(is_array($select)):
        //Return 
        return json_encode($select); 
      else: 
        return json_encode(0); 
      endif;
    }

    function getGaleriaImagens($id)
    {
      try {
        $SELECT = "SELECT imagem FROM galeria_imagem WHERE id_galeria = :id ORDER BY id DESC";
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
    
    function getNameCategoria($name)
    {
      $select = DB\Mysql::select(
        'SELECT * FROM categoria WHERE categoria = :categoria ORDER BY id DESC',
        [
          'categoria' => $name, 
        ]
      );

      if(is_array($select)){
        return json_encode($select);
      }else{
        return json_encode(0);
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
      
      $SELECT = "SELECT * FROM visitas WHERE ip = :ip and data = :data ORDER BY id DESC";

      try{
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
          eco($result->rowCount());
        endif;
      }catch(Exception $i){
        //null
      }

    }

    #Inserir a visita no banco de dados
    private function add()
    {
      DB\Mysql::insert(
        "INSERT INTO visitas (ip, data, hora) VALUES (:ip, :data, :hora)",
        ['ip' => $this->ip, 'data' => $this->data, 'hora' => $this->hora]
      );
    }
}

function convert_link($link = '')
{
  $filter  = array(' ', ':', '&');
  $replace = array('-');
  $url     = str_replace($filter, $replace, $link);
  return $url;
}

function postData($value = array())
{
  ?>
  <div class="card-posts mb-4 w-100">  

    <img src="<?= is_file('publico/img/posts/'.$value['imagem']) || is_file('../../publico/img/posts/'.$value['imagem']) 
        ? 'publico/img/posts/'.$value['imagem'] 
        : 'publico/img/posts/default.png'; ?>" 
    class="card-img-top w-100"
    onclick="location.href='./post/<?= convert_link($value['titulo']); ?>'" 
    alt="Imagem do post">

    <div>
        
        <div class="w-100 mb-3">
            <h4
            onclick="location.href='./post/<?= convert_link($value['titulo']); ?>'" 
            class="card-title m-0"><?= $value['titulo']; ?></h4>                        
        </div>
        
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <small style="font-weight: 500;" class="d-block"><i class="las la-globe-africa"></i> <?= $value['registo']; ?></small>
            </div>
        </div>
        
        <div class="w-100 mb-3 lead">
            <?= limitarTexto($value['subtitulo'], 150); ?>                       
        </div>

        <a 
        href="./post/<?= convert_link($value['titulo']); ?>" 
        class="btn btn-outline-danger" 
        style="font-size: 12px;">LER MAIS</a> 

    </div>

  </div>
  <?php
}

//Artigos
function ArtigoData($value = array())
{
  ?>
  <div class="card-artigos mb-4 w-100" onclick="location.href='./artigo/<?= convert_link($value['nome']); ?>/<?= $value['id']; ?>'">  

    <img src="<?= is_file('publico/img/artigos/'.$value['imagem']) || is_file('../../publico/img/artigos/'.$value['imagem']) 
        ? 'publico/img/artigos/'.$value['imagem'] 
        : 'publico/img/artigos/default.png'; ?>" 
    class="card-img-top w-100" alt="">

    <div>
        <div class="w-100 mb-2">
            <p class="card-title m-0 lead pt-2 pb-2"><b><?= $value['nome']; ?></b></p>
            <!--<div><?php echo limitarTexto(filterVar($value['descricao']), 50); ?></div>-->
            <p class="red-text"><b><?= get_moeda($value['preco']); ?></b></p>                        
        </div>
    </div>

  </div>
  <?php
}
?>