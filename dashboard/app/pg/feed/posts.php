<div class="row m-0 justify-content-center">
  <div class="col-12 col-md-5">
    <form method="GET">
      <div class="input-group mb-2 card-body-search">
        <input type="hidden" name="feed">
        <input type="text" class="form-control" name="filtro" value="<?php if(isset($_GET['filtro'])): echo trim($_GET['filtro']); endif; ?>" placeholder="Procure aqui...">
        <div class="input-group-append">
          <button type="submit" class="input-group-text pointer"><i class="las la-search"></i></button>
        </div>											
      </div>
    </form>
  </div>
</div>
<div class="row justify-content-center">
	<div class="col-12 mt-2 p-0">
    <?php
      //Definindo a paginação
      if(isset($_GET['pg']) && is_numeric(trim($_GET['pg'])) && trim($_GET['pg']) > 0):           
        $pg = trim($_GET['pg']); 
      else: 
        $pg = 1; 
      endif;

      //Quantidade a mostrar
      @$quantidade = 30;
      @$inicio     = ($pg * $quantidade) - $quantidade;
      @$limit      = ' LIMIT :inicio, :quantidade';

      try{

      if (isset($_GET['filtro']) && $_GET['filtro'] !=''):
        $busca  = filter_var(trim(strip_tags($_GET['filtro'])), FILTER_SANITIZE_STRING);
        $select = "SELECT * from blog WHERE titulo LIKE '%$busca%'";
        $select.= " OR registo LIKE '%$busca%' ORDER BY id DESC";
      else:
        $select = 'SELECT * from blog ORDER BY id DESC';
      endif;

      $result = $conexao->getCon(1)->prepare($select.$limit);
      $result->bindParam(':inicio', $inicio, PDO::PARAM_INT);
      $result->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
      $result ->execute();
      $contar = $result->rowCount();
      if($contar > 0){
        $table        = '<div class="table-responsive">';
        $table       .= '<table class="table table-striped table-hover">';
        $table       .= '<thead>';
        $table       .= '<tr>';
        
        $tableCheck   = trim(
        '<div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="feed-id">
          <label class="custom-control-label" for="feed-id"></label>
        </div>'
        );

        $table.= trim('
          <th scope="col">#</th>
          <th scope="col">Titulo</th>
          <th scope="col">Descrição</th>
          <th scope="col">Registo</th>
          <th scope="col">'.$tableCheck.'</th>
        ');

        $table       .= '</tr>';
        $table       .= '</thead>';
        $table       .= '<tbody>';
        $tableClose   = '<tbody></table></div>';
        echo $table;
        while($mostra = $result->FETCH(PDO::FETCH_OBJ)){
        ?>
        <tr <?= isset($mostra->estado) && $mostra->estado == 0 ? 'class="table-secondary"' : ''; ?>>
          <th scope="row"><?php echo $mostra->id; ?></th>
          <td><?php echo $mostra->titulo;  ?></td>
          <td><?php echo limitarTexto(trim(strip_tags($mostra->descricao)), 100); ?></td>
          <td><?php echo $mostra->registo; ?></td>
          <th>
            <div class="d-flex w-100 align-items-center">
              <div class="custom-control custom-checkbox d-inline-block">
                <input 
                type="checkbox" 
                class="custom-control-input"
                feed-select="<?php echo $mostra->id; ?>"
                id="feed-<?php echo $mostra->id; ?>">
                
                <label 
                class="custom-control-label" 
                for="feed-<?php echo $mostra->id; ?>"></label>
              </div>
              
              <a href="?feed&comment=<?php echo $mostra->id; ?>"><i class="las la-comment" data-id="<?php echo $mostra->id; ?>"></i></a>
              <a href="?feed&edit=<?php echo $mostra->id; ?>"><i class="las la-edit" data-id="<?php echo $mostra->id; ?>"></i></a>
              <a href="?feed&post=<?php echo $mostra->id; ?>"><i class="las la-eye" data-id="<?php echo $mostra->id; ?>"></i></a>		

            </div>
          </th>
        </tr>
        <?php
        }
        
        echo $tableClose;

        //Paginação
        $paginacao = new Paginacao();
        $paginacao->queryString = 'feed';
        $paginacao->select      = $select;
        $paginacao->quantidade  = $quantidade;
        $paginacao->pg          = $pg;
        $paginacao->getPaginacao();

      }else{
        if (isset($pg) && $pg > 1):
          echo '<script type="text/javascript">location.href = "./?feed";</script>';
          exit(); 
        endif;
        echo '<p class="text-center lead">Nenhum resultado encontrado!</p>';
      }

      }catch(Exception $error){
        echo '<p class="text-center lead">'.$error.'!</p>';
      }
    ?>
  </div>
</div>

<!-- Eliminar postagem -->
<div class="modal fade" id="modal-remove-item" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
  
		<div class="modal-body text-center lead" id="remove-content"></div>
  
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
		  <button type="button" class="btn btn-danger" id="remove-item">Remover</button>
		</div>
	  </div>
	</div>
</div>

<script src="assets/js/feed/posts.js"></script>