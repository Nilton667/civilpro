<script src="assets/js/jqueryForm.js"></script>

<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Anúncios</h3>
    </div>
</div>
<div class="content-body">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                	<div class="container">
                    <div class="heading-elements-ignore d-flex justify-content-end">
                        <ul class="list-inline mb-0">
                            <li>
                                <a id="modal-add" data-toggle="modal" data-target="#modal-new-anuncio" class="mr-2">
                                    <i class="ft-plus"></i>
                                </a>
                            </li>
                        	<li><a id="modal-delete" class="mr-2"><i class="ft-trash"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                	</div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">                        

                    <div class="container">
                      <div class="row m-0 justify-content-center">
                        <div class="col-12 col-md-5">
                          <form method="GET">
                            <div class="input-group mb-2 card-body-search">
                              <input type="hidden" name="anuncio">
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
                            $select = "SELECT * from anuncio WHERE anuncio LIKE '%$busca%'";
                            $select.= " OR registo LIKE '%$busca%' ORDER BY id DESC";
                          else:
                            $select = 'SELECT * from anuncio ORDER BY id DESC';
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
                              <input type="checkbox" class="custom-control-input" id="anuncio-id">
                              <label class="custom-control-label" for="anuncio-id"></label>
                            </div>'
                            );

                            $table.= trim('
                                <th scope="col">#</th>
                                <th scope="col">Anúncio</th>
                                <th scope="col">Orientação</th>
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
                              <td id="anuncio-nome-<?php echo $mostra->id; ?>">
                                  <?php echo $mostra->anuncio; ?>
                              </td>
                              <td><?php echo $mostra->orientacao; ?></td>
                              <td><?php echo $mostra->registo; ?></td>
                              <th>
                                <div class="d-flex w-100 align-items-center">
                                  <div class="custom-control custom-checkbox d-inline-block">
                                    <input 
                                    type="checkbox" 
                                    class="custom-control-input"
                                    anuncio-select="<?php echo $mostra->id; ?>"
                                    id="anuncio-<?php echo $mostra->id; ?>">
                                    
                                    <label 
                                    class="custom-control-label" 
                                    for="anuncio-<?php echo $mostra->id; ?>"></label>
                                  </div>
      
                                  <i class="las la-external-link-alt"
                                  onclick="<?php if($mostra->url != ''){ 
                                  ?> window.open('<?php echo $mostra->url; ?>','_blank'); <?php }else{ 
                                    ?> 
                                    $.toast({
                                      heading: 'Alerta',
                                      text: 'Nenhuma url especificada!',
                                      showHideTransition: 'fade',
                                      icon: 'error',
                                      loader: true,
                                    }); 
                                  <?php } ?>" 
                                  data-id="<?php echo $mostra->id; ?>"></i>	

                                  <i class="las la-edit modal-edit-<?php echo $mostra->id; ?>"
                                  id="modal-edit"
                                  data-anuncio="<?php echo $mostra->anuncio; ?>"
                                  data-orientacao="<?= eco($mostra->orientacao); ?>"
                                  data-estado="<?php echo $mostra->estado; ?>"
                                  data-url="<?= eco($mostra->url); ?>" 
                                  data-id="<?php echo $mostra->id; ?>"></i>
                            
                                  <i class="las la-eye visualizar-imagem" 
                                  data-orientacao="<?php echo $mostra->orientacao; ?>"
                                  data-image="<?php echo $mostra->imagem; ?>" 
                                  data-id="<?php echo $mostra->id; ?>"></i>	 
                                </div>
                              </th>
                            </tr>
                            <?php
                            }
                            
                            echo $tableClose;

                            //Paginação
                            $paginacao = new Paginacao();
                            $paginacao->queryString = 'anuncio';
                            $paginacao->select      = $select;
                            $paginacao->quantidade  = $quantidade;
                            $paginacao->pg          = $pg;
                            $paginacao->getPaginacao();

                          }else{
                            if (isset($pg) && $pg > 1):
                              echo '<script type="text/javascript">location.href = "./?anuncio";</script>';
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
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Criar anuncio -->
<div class="modal fade" id="modal-new-anuncio" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Novo anúncio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" id="anuncio-form">
          <input type="hidden" name="add_anuncio" value="true">
          <!--<input type="hidden" name="header" value="application/json">-->
          <div class="custom-file mb-2">
              <input type="file" class="custom-file-input" onchange="fileName(this.value)" id="input-file" name="img[]">
              <label for="input-file" id="input-file-label" class="custom-file-label" for="input-file">Escolher imagem</label>
          </div>

          <div class="form-group">
            <label for="anuncio-nome">Nome</label>
            <input type="text" class="form-control" id="anuncio-nome" placeholder="Ex: mulemba_host" name="anuncio">
          </div>

          <div class="form-group">
            <label for="anuncio-url">Link</label>
            <input type="url" class="form-control" id="anuncio-url" placeholder="Ex: https://mulembatechnology.com/" name="url">
          </div>

          <div class="form-group">
            <label for="anuncio-orientacao">Orientação</label>
            <select class="form-control" id="anuncio-orientacao" name="orientacao">
              <option value="">-- Selecione uma orientação --</option>
              <option value="Vertical">Vertical</option>
              <option value="Horizontal">Horizontal</option>
            </select>
          </div>

          <div class="form-group">
            <div>
              <label>Estado</label>
              <select class="custom-select" id="anuncio-estado" name="estado">
                <option value="1">Visível</option>
                <option value="0">Oculto</option>
              </select>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-danger" id="add-anuncio">Registar</button>
      </div>
    </div>
  </div>
</div>

<!-- Editar anuncio -->
<div class="modal fade" id="modal-edit-anuncio" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Editar anúncio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="anuncio-form-update" method="POST" enctype="multipart/form-data">

          <input type="hidden" id="edit-anuncio-id" name="anuncio_id"> 
          <input type="hidden" name="edit_nuncio">

          <div class="form-group">
            <label for="edit-anuncio-nome">Anúncio</label>
            <input type="text" class="form-control" id="edit-anuncio-nome" name="anuncio">
          </div>

          <div class="form-group">
            <label for="edit-anuncio-url">Link</label>
            <input type="url" class="form-control" id="edit-anuncio-url" placeholder="Ex: https://mulembatechnology.com/" name="url">
          </div>

          <div class="form-group">
            <label for="edit-anuncio-orientacao">Orientação</label>
            <select class="form-control" id="edit-anuncio-orientacao" name="orientacao">
              <option value="">-- Selecione uma orientação --</option>
              <option value="Vertical">Vertical</option>
              <option value="Horizontal">Horizontal</option>
            </select>
          </div>

          <div class="form-group">
            <div>
              <label>Estado</label>
              <select class="custom-select" id="edit-anuncio-estado" name="estado">
                <option value="1">Visível</option>
                <option value="0">Oculto</option>
              </select>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="edit-anuncio">Editar</button>
      </div>
    </div>
  </div>
</div>

<!-- Eliminar anuncio -->
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

<!-- Visualizar imagem -->
<div class="modal fade" id="image-test" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="las la-image"></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <img id="view-image" src="" class="w-100" style="height: auto;">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/js/anuncio.js"></script>