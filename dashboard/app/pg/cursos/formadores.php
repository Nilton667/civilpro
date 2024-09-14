<script src="assets/js/jqueryForm.js"></script>
<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Formadores</h3>
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
                                <a id="modal-add" data-toggle="modal" data-target="#modal-add-item" class="mr-2">
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
										<input type="hidden" name="cursos_formadores">
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
										$select = "SELECT * from cursos_formadores WHERE nome LIKE '%$busca%'";
										$select.= " OR registo LIKE '%$busca%' ORDER BY id DESC";
									else:
										$select = 'SELECT * from cursos_formadores ORDER BY id DESC';
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
											<input type="checkbox" class="custom-control-input" id="formador-id">
											<label class="custom-control-label" for="formador-id"></label>
										</div>'
										);

										$table.= trim('
										    <th scope="col">#</th>
										    <th scope="col">Nome</th>
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
										<tr>
											<th scope="row"><?php echo $mostra->id; ?></th>
                                            <td id="formador-nome-<?php echo $mostra->id; ?>">
                                                <?php echo $mostra->nome;  ?>
                                            </td>
                                            <td><?php echo limitarTexto(trim(strip_tags($mostra->descricao)), 50);  ?></td>
											<td><?php echo $mostra->registo;  ?></td>
											<th>
												<div class="d-flex w-100 align-items-center">
													<div class="custom-control custom-checkbox d-inline-block">
														<input 
														type="checkbox" 
														class="custom-control-input"
														formador-select="<?php echo $mostra->id; ?>"
														id="formador-<?php echo $mostra->id; ?>">
														
														<label 
														class="custom-control-label" 
														for="formador-<?php echo $mostra->id; ?>"></label>
													</div>
													
													<i class="las la-edit modal-edit-<?php echo $mostra->id; ?>"
													id="modal-edit"
													data-nome="<?php echo $mostra->nome; ?>"
													data-imagem="<?= $mostra->imagem; ?>"
													data-descricao="<?php echo $mostra->descricao; ?>" 
													data-id="<?php echo $mostra->id; ?>"></i>	
													
													<?php
						                              if(is_file('../publico/img/formadores/'.$mostra->imagem)):
						                                  ?>
						                                  <i class="las la-image visualizar-imagem"
						                                  data-imagem="<?php eco('../publico/img/formadores/'.$mostra->imagem); ?>"
						                                  data-id="<?php echo $mostra->id; ?>"></i>
						                                  <?php
						                              endif;
						                          	?>

												</div>
											</th>
										</tr>
										<?php
										}
										
										echo $tableClose;

										//Paginação
										$paginacao = new Paginacao();
										$paginacao->queryString = 'cursos_formadores';
										$paginacao->select      = $select;
										$paginacao->quantidade  = $quantidade;
										$paginacao->pg          = $pg;
										$paginacao->getPaginacao();

									}else{
										if (isset($pg) && $pg > 1):
											echo '<script type="text/javascript">location.href = "./?cursos_formadores";</script>';
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

<!-- Eliminar formador -->
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

<!-- Editar formador -->
<div class="modal fade" id="modal-edit-item" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<h5>Editar formador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
	  
		<form id="edit-form-formadores" method="POST" enctype="multipart/form-data">
			<input name="edit_formador" type="hidden" value="true">
			<input type="hidden" name="formador_id" id="edit-formador-id">
			<input name="old_image" type="hidden" id="edit-old-imagem" value="">
			<label>Imagem</label>
			<div class="input-group" style="margin-bottom: 15px;">
				<div class="custom-file">
					<input type="file" name="img[]" onchange="editFileName(this.value)" class="custom-file-input" id="edit-input-image">
					<label id="edit-input-file-label" class="custom-file-label" for="edit-input-image">Escolher arquivo</label>
				</div>
			</div>
			<div class="form-group">
				<label for="edit-formador-nome">Nome</label>
				<input name="nome" type="text" class="form-control" id="edit-formador-nome" placeholder="Ex: João...">
			</div>
			
			<textarea name="descricao" id="message-text-edit" placeholder="Descrição"></textarea>
		</form>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="edit-formador">Editar</button>
      </div>
    </div>
  </div>
</div>

<!-- Criar formador -->
<div class="modal fade" id="modal-add-item" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<h5>Novo formador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
      
		<form id="form-formadores" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="add_formador" value="true">
            <label>Imagem</label>
            <div class="input-group" style="margin-bottom: 15px;">
                <div class="custom-file">
                    <input type="file" name="img[]" onchange="fileName(this.value)" class="custom-file-input" id="input-image">
                    <label id="input-file-label" class="custom-file-label" for="input-image">Escolher arquivo</label>
                </div>
            </div>
			<div class="form-group">
				<label for="formador-nome">Nome</label>
				<input name="nome" type="text" class="form-control" id="formador-nome" placeholder="Ex: João...">
			</div>
			<textarea name="descricao" id="message-text" placeholder="Descrição"></textarea>
		</form>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-danger" id="add-formador">Registar</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/ckeditor/ckeditor.js"></script>
<script src="assets/ckeditor/translations/pt.js"></script>
<script src="assets/js/cursos/formadores.js"></script>