<script src="assets/js/jqueryForm.js"></script>
<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Categorias</h3>
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
										<input type="hidden" name="categorias">
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
										$select = "SELECT * from categoria WHERE categoria LIKE '%$busca%'";
										$select.= " OR registo LIKE '%$busca%' ORDER BY id DESC";
									else:
										$select = 'SELECT * from categoria ORDER BY id DESC';
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
											<input type="checkbox" class="custom-control-input" id="categoria-id">
											<label class="custom-control-label" for="categoria-id"></label>
										</div>'
										);

										$table.= trim('
										    <th scope="col">#</th>
										    <th scope="col">Categoria</th>
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
                                            <td id="categoria-nome-<?php echo $mostra->id; ?>">
                                                <?php echo $mostra->categoria;  ?>
                                            </td>
                                            <td><?php echo $mostra->descricao;  ?></td>
											<td><?php echo $mostra->registo;  ?></td>
											<th>
												<div class="d-flex w-100 align-items-center">
													<div class="custom-control custom-checkbox d-inline-block">
														<input 
														type="checkbox" 
														class="custom-control-input"
														categoria-select="<?php echo $mostra->id; ?>"
														id="categoria-<?php echo $mostra->id; ?>">
														
														<label 
														class="custom-control-label" 
														for="categoria-<?php echo $mostra->id; ?>"></label>
													</div>
													
													<i class="las la-edit modal-edit-<?php echo $mostra->id; ?>"
													id="modal-edit"
													data-categoria="<?php echo $mostra->categoria; ?>"
                          							data-imagem="<?php eco($mostra->imagem); ?>"
                          							data-descricao="<?php echo $mostra->descricao; ?>"
													data-blog="<?php echo $mostra->blog; ?>"  
													data-curso="<?php echo $mostra->curso; ?>"
													data-loja="<?php echo $mostra->loja; ?>"
													data-imoveis="<?php echo $mostra->imoveis; ?>"
													data-id="<?php echo $mostra->id; ?>"></i>	                                        	

						                          <?php
						                              if(is_file('../publico/img/categorias/'.$mostra->imagem)):
						                                  ?>
						                                  <i class="las la-image visualizar-imagem"
						                                  data-imagem="<?php eco('../publico/img/categorias/'.$mostra->imagem); ?>"
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
										$paginacao->queryString = 'categorias';
										$paginacao->select      = $select;
										$paginacao->quantidade  = $quantidade;
										$paginacao->pg          = $pg;
										$paginacao->getPaginacao();

									}else{
										if (isset($pg) && $pg > 1):
											echo '<script type="text/javascript">location.href = "./?categorias";</script>';
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

<!-- Eliminar categoria -->
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

<!-- Editar categoria -->
<div class="modal fade" id="modal-edit-item" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<h5>Editar categoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
          <form id="edit-form-categoria" method="POST" enctype="multipart/form-data">
            <input name="edit_categoria" type="hidden" value="true">
            <input name="old_image" type="hidden" id="edit-old-imagem" value="">
            <input name="categoria_id" type="hidden" id="edit-categoria-id" value="">
            <input name="old_categoria" type="hidden" id="edit-old-categoria" value="">
                <label>Imagem</label>
                <div class="input-group" style="margin-bottom: 15px;">
                    <div class="custom-file">
                        <input type="file" name="img[]" onchange="editFileName(this.value)" class="custom-file-input" id="edit-input-image">
                        <label id="edit-input-file-label" class="custom-file-label" for="edit-input-image">Escolher arquivo</label>
                    </div>
                </div>
                <div class="form-group">
                <label for="edit-categoria-nome">Categoria</label>
                <input type="text" name="categoria" class="form-control" id="edit-categoria-nome" placeholder="Ex: Software...">
            </div>
            
            <div class="form-group">
            	<label for="edit-categoria-descricao">Descrição</label>
            	<textarea name="descricao" class="form-control" id="edit-categoria-descricao" rows="4"></textarea>
            </div>

			<div class="row">
			<?php
					if($getDef['modulo']['blog']){
						?>
						<div class="col-6">
							<div class="form-group">
								<label><b>Blog</b></label>
								<select id="edit-categoria-blog" name="blog" class="custom-select">
									<option value="0">Oculto</option>
									<option value="1">Visível</option>
								</select>            
							</div>
						</div>
						<?php
					}

					if($getDef['modulo']['curso']){
						?>
						<div class="col-6">
							<div class="form-group">
								<label><b>Cursos</b></label>
								<select id="edit-categoria-curso" name="curso" class="custom-select">
									<option value="0">Oculto</option>
									<option value="1">Visível</option>
								</select>            
							</div>
						</div>
						<?php
					}

					if($getDef['modulo']['loja']){
						?>
						<div class="col-6">
							<div class="form-group">
								<label><b>Loja</b></label>
								<select id="edit-categoria-loja" name="loja" class="custom-select">
									<option value="0">Oculto</option>
									<option value="1">Visível</option>
								</select>            
							</div>
						</div>
						<?php
					}

					if($getDef['modulo']['imoveis']){
						?>
						<div class="col-6">
							<div class="form-group">
								<label><b>Imóveis</b></label>
								<select id="edit-categoria-imoveis" name="imoveis" class="custom-select">
									<option value="0">Oculto</option>
									<option value="1">Visível</option>
								</select>            
							</div>
						</div>
						<?php
					}
				?>
			</div>

          </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="edit-categoria">Editar</button>
      </div>
    </div>
  </div>
</div>

<!-- Criar categoria -->
<div class="modal fade" id="modal-add-item" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<h5>Nova categoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="form-categoria" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="add_categoria" value="true">
            <label>Imagem</label>
            <div class="input-group" style="margin-bottom: 15px;">
                <div class="custom-file">
                    <input type="file" name="img[]" onchange="fileName(this.value)" class="custom-file-input" id="input-image">
                    <label id="input-file-label" class="custom-file-label" for="input-image">Escolher arquivo</label>
                </div>
            </div>
            <div class="form-group">
                <label for="categoria-nome">Categoria</label>
                <input type="text" name="categoria" class="form-control" id="categoria-nome" placeholder="Ex: Software...">
            </div>

            <div class="form-group">
            	<label for="categoria-descricao">Descrição</label>
            	<textarea name="descricao" class="form-control" id="categoria-descricao" rows="4"></textarea>
            </div>
			
			<div class="row">
				<?php
					if($getDef['modulo']['blog']){
						?>
						<div class="col-6">
							<div class="form-group">
								<label><b>Blog</b></label>
								<select id="categoria-blog" name="blog" class="custom-select">
									<option value="0">Oculto</option>
									<option value="1">Visível</option>
								</select>            
							</div>
						</div>
						<?php
					}

					if($getDef['modulo']['curso']){
						?>
						<div class="col-6">
							<div class="form-group">
								<label><b>Cursos</b></label>
								<select id="categoria-curso" name="curso" class="custom-select">
									<option value="0">Oculto</option>
									<option value="1">Visível</option>
								</select>            
							</div>
						</div>
						<?php
					}

					if($getDef['modulo']['loja']){
						?>
						<div class="col-6">
							<div class="form-group">
								<label><b>Loja</b></label>
								<select id="categoria-loja" name="loja" class="custom-select">
									<option value="0">Oculto</option>
									<option value="1">Visível</option>
								</select>            
							</div>
						</div>
						<?php
					}

					if($getDef['modulo']['imoveis']){
						?>
						<div class="col-6">
							<div class="form-group">
								<label><b>Imóveis</b></label>
								<select id="categoria-imoveis" name="imoveis" class="custom-select">
									<option value="0">Oculto</option>
									<option value="1">Visível</option>
								</select>            
							</div>
						</div>
						<?php
					}
				?>
			</div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-danger" id="add-categoria">Registar</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/js/utilitarios/categoria.js"></script>