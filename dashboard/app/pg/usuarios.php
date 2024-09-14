<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Usuários</h3>
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
							<li><a id="modal-add" data-toggle="modal" data-target="#modal-new-user" class="mr-2"><i class="ft-plus"></i></a></li>
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
											<input type="hidden" name="usuarios">
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
										$select = "SELECT * from usuarios WHERE nome LIKE '%$busca%'";
										$select.= " OR registo LIKE '%$busca%' ORDER BY id DESC";
									else:
										$select = 'SELECT * from usuarios ORDER BY id DESC';
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
											<input type="checkbox" class="custom-control-input" id="usuario-id">
											<label class="custom-control-label" for="usuario-id"></label>
										</div>'
										);

										$table.= trim('
										    <th scope="col">#</th>
										    <th scope="col">Nome</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Gênero</th>
                                            <th scope="col">Contacto</th>
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
											<td><?php echo $mostra->nome.' '.$mostra->sobrenome; ?></td>
                                            <td><?php echo $mostra->email; ?></td>
                                            <td><?php echo $mostra->genero; ?></td>
                                            <td><?php echo $mostra->telemovel; ?></td>
											<td><?php echo $mostra->registo;  ?></td>
											<th>
												<div class="d-flex w-100 align-items-center">
													<div class="custom-control custom-checkbox d-inline-block">
														<input 
														type="checkbox" 
														class="custom-control-input"
														usuario-select="<?php echo $mostra->id; ?>"
														id="usuario-<?php echo $mostra->id; ?>">
														
														<label 
														class="custom-control-label" 
														for="usuario-<?php echo $mostra->id; ?>"></label>
													</div>
													
													<i class="las la-file-alt"
													id="usuario-visualize"
													data-nome="<?php echo $mostra->nome.' '.$mostra->sobrenome; ?>"
													data-identificacao="<?php if($mostra->identificacao == ''): echo 'n/a'; else: echo $mostra->identificacao; endif; ?>"
                                                    data-nacionalidade="<?php if($mostra->nacionalidade == ''): echo 'n/a'; else: echo $mostra->nacionalidade; endif; ?>"
                                                    data-morada="<?php if($mostra->morada == ''): echo 'n/a'; else: echo $mostra->morada; endif; ?>"
                                                    data-conta="<?php if($mostra->cashe == $mostra->checkCashe): echo 1; else: echo 0; endif; ?>" 
													data-id="<?php echo $mostra->id; ?>"></i>	                                        		
												</div>
											</th>
										</tr>
										<?php
										}
										
										echo $tableClose;

										//Paginação
										$paginacao = new Paginacao();
										$paginacao->queryString = 'usuarios';
										$paginacao->select      = $select;
										$paginacao->quantidade  = $quantidade;
										$paginacao->pg          = $pg;
										$paginacao->getPaginacao();

									}else{
										if (isset($pg) && $pg > 1):
											echo '<script type="text/javascript">location.href = "./?usuarios";</script>';
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

<!-- Registar usuario -->
<div class="modal fade" id="modal-new-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Registar usuário</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
	  	<div class="bg-white p-1">
			<div class="row m-0">
				<div class="col-12 col-sm-6 p-0 pr-sm-1">
					<div class="form-group">
						<label for="email">Nome</label>
						<input type="text" class="form-control" id="nome" name="nome" aria-describedby="emailHelp" placeholder="Nome">
					</div>
				</div>
				<div class="col-12 col-sm-6 p-0 pl-sm-1">
					<div class="form-group">
						<label for="senha">Sobrenome</label>
						<input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Sobrenome">
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="senha">Email</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<i class="input-group-text las la-envelope"></i>
					</div>
					<input type="email" class="form-control" id="email" name="email" placeholder="Seu email">
				</div>
			</div>

			<div class="row m-0">
				<div class="col-12 col-sm-6 p-0 pr-sm-1">
					<div class="form-group">
						<label>Nacionalidade</label>
						<select id="nacionalidade" class="custom-select">
							<option selected value="">-- Nacionalidade --</option>
							<option value="Angola">Angola</option>
							<option value="Brasil">Brasil</option>
							<option value="Moçambique">Moçambique</option>
							<option value="Portugal">Portugal</option>
						</select>
					</div>
				</div>
				<div class="col-12 col-sm-6 p-0 pl-sm-1">
					<div class="form-group">
						<label>Gênero</label>
						<select id="genero" class="custom-select">
							<option selected value="">-- Gênero --</option>
							<option value="M">Masculino</option>
							<option value="F">Feminino</option>>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="senha">Senha</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<i class="input-group-text">***</i>
					</div>
					<input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
				</div>
			</div>

		</div>  
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="cadastrar">Registar</button>
      </div>
    </div>
  </div>
</div>

<!-- Dados de usuario -->
<div class="modal fade" id="show-usuario" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="modal-user-nome"></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
            <ul class="list-group">
				<li class="list-group-item"><span style="font-weight: 500;">Identificacao:</span> <span id="modal-user-identificacao"></span></li>
                <li class="list-group-item"><span style="font-weight: 500;">Nacionalidade:</span> <span id="modal-user-nacionalidade"></span></li>
                <li class="list-group-item"><span style="font-weight: 500;">Morada:</span> <span id="modal-user-morada"></span></li>
                <li class="list-group-item"><span style="font-weight: 500;">Conta:</span> <span id="modal-user-conta"></span></li>
            </ul>
        </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
		</div>
    </div>
  </div>
</div>

<!-- Eliminar usuário -->
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

<script src="assets/js/usuario.js"></script>