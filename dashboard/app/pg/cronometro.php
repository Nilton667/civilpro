<script src="assets/js/jqueryForm.js"></script>
<link rel="stylesheet" type="text/css" href="assets/css/mdtimepicker.css">
<link rel="stylesheet" type="text/css" href="assets/css/mdtimepicker-theme.css">

<style type="text/css">
	.cronometro-content{
		position: relative;
	}
	.cronometro-content > div{
		pointer-events: none;
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
	}
	.cronometro-content img, .cronometro-content video{
		min-height: 300px;
	}
	.cronometro-content div{
		word-wrap: break-word;
		word-break: break-all;
		height: initial;
		font-size: 1em!important;
	}
	.cronometro-content .label, .cronometro-content .conta{
		font-size: .8em!important;
		text-shadow: 5px 5px 5px rgba(0, 0, 0, 0.3);
	}
	.cronometro-content .block{
		margin: 1em!important;
	}
</style>
<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Cronômentro</h3>
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
											<input type="hidden" name="cronometro">
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
									@$quantidade = 8;
									@$inicio     = ($pg * $quantidade) - $quantidade;
									@$limit      = ' LIMIT :inicio, :quantidade';

									try{

									if (isset($_GET['filtro']) && $_GET['filtro'] != ''):
										$busca  = filter_var(trim(strip_tags($_GET['filtro'])), FILTER_SANITIZE_STRING);
										$select = "SELECT * from cronometro WHERE titulo LIKE '%$busca%'";
										$select.= " OR data LIKE '%$busca%' ORDER BY id DESC";
									else:
										$select = 'SELECT * from cronometro ORDER BY id DESC';
									endif;

									$result = $conexao->getCon(1)->prepare($select.$limit);
									$result->bindParam(':inicio', $inicio, PDO::PARAM_INT);
									$result->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
									$result ->execute();
									$contar = $result->rowCount();
									if($contar > 0){
										while($mostra  = $result->FETCH(PDO::FETCH_OBJ)){
										?>
										<div class="contador mb-1" data-until="<?php echo strtotime($mostra->data.$mostra->hora); ?>"> 
											
											<div class="w-100 d-flex w-100 align-items-center" style="background: #2c303b;">
												<div class="custom-control custom-checkbox d-inline-block ml-1">
													<input 
													type="checkbox" 
													class="custom-control-input"
													cronometro-select="<?php echo $mostra->id; ?>"
													id="cronometro-<?php echo $mostra->id; ?>">
													<label 
													class="custom-control-label" 
													for="cronometro-<?php echo $mostra->id; ?>"></label>
												</div>
												<i class="las la-eye pointer" data-toggle="modal" 
												data-target="#modal-view-<?= $mostra->id; ?>" 
												style="font-size: 1.5em; margin-left: 5px; color: #fff;"></i>

												<p class="p-1 m-0 lead text-white contador-title text-uppercase" style="font-weight: 500;">
													<?php echo $mostra->titulo; ?>
												</p>
											</div>

										</div>

										<!-- Visualizar cronometro -->
										<div class="modal fade" id="modal-view-<?= $mostra->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog" role="document">
											  <div class="modal-content">
												<div class="modal-header">
												  <h5><?= $mostra->titulo; ?></h5>
												  <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
													<span aria-hidden="true">&times;</span>
												  </button>
												</div>
										  
												<div class="modal-body">
													<?php
														$array  = explode('.', strtolower($mostra->file));
														$isFile = is_file('./../publico/cronometro/'.$mostra->file); 
														if(end($array) == 'png' || end($array) == 'jpg' && $isFile || end($array) == 'jpeg' && $isFile){
															?>
															<div class="cronometro-content contador" data-until="<?php echo strtotime($mostra->data.$mostra->hora); ?>">
																<img class="w-100" src="./../publico/cronometro/<?= $mostra->file; ?>">

																<div class="d-flex justify-content-between align-items-center w-100 text-white" style="overflow: auto;">
																	<div class="dias block">
																		<div class="conta">0</div>
																		<div class="label">Dia(s)</div>
																	</div>

																	<div class="horas block">
																		<div class="conta">0</div>
																		<div class="label">Hora(s)</div>
																	</div>

																	<div class="minutos block">
																		<div class="conta">0</div>
																		<div class="label">Minuto(s)</div>
																	</div>

																	<div class="segundos block">
																		<div class="conta">0</div>
																		<div class="label">Segundo(s)</div>
																	</div>										
																</div>
															</div>
															<?php	
														}else if(end($array) == 'mp4' && $isFile || end($array) == 'mkv' && $isFile){
															?>
															<div class="cronometro-content contador" data-until="<?php echo strtotime($mostra->data.$mostra->hora); ?>">
																<video class="w-100" controls=""
																src="./../publico/cronometro/<?= $mostra->file; ?>"></video>
																<div class="d-flex justify-content-between align-items-center w-100 text-white" style="overflow: auto;">

																	<div class="dias block">
																		<div class="conta">0</div>
																		<div class="label">Dia(s)</div>
																	</div>

																	<div class="horas block">
																		<div class="conta">0</div>
																		<div class="label">Hora(s)</div>
																	</div>

																	<div class="minutos block">
																		<div class="conta">0</div>
																		<div class="label">Minuto(s)</div>
																	</div>

																	<div class="segundos block">
																		<div class="conta">0</div>
																		<div class="label">Segundo(s)</div>
																	</div>

																</div>
															</div>
															<?php
														}else{
															?>
															<div class="contador mb-1" data-until="<?php echo strtotime($mostra->data.$mostra->hora); ?>"> 
																
																<div class="w-100 d-flex w-100 align-items-center" style="background: #2c303b;">
																	<p class="p-1 m-0 lead text-white contador-title text-uppercase" style="font-weight: 500;">
																		<?php echo $mostra->titulo; ?>
																	</p>
																</div>

																<div class="d-flex justify-content-between w-100 text-white" style="overflow: auto; background: #28afd0;">
																	<div class="dias block">
																		<div class="conta">0</div>
																		<div class="label">Dia(s)</div>
																	</div>

																	<div class="horas block">
																		<div class="conta">0</div>
																		<div class="label">Hora(s)</div>
																	</div>

																	<div class="minutos block">
																		<div class="conta">0</div>
																		<div class="label">Minuto(s)</div>
																	</div>

																	<div class="segundos block">
																		<div class="conta">0</div>
																		<div class="label">Segundo(s)</div>
																	</div>										
																</div>

															</div>
															<?php
														}
													?>
												</div>
										  
												<div class="modal-footer">
												  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
												</div>
											  </div>
											</div>
										</div>

										<?php
										}

										//Paginação
										$paginacao = new Paginacao();
										$paginacao->queryString = 'cronometro';
										$paginacao->select      = $select;
										$paginacao->quantidade  = $quantidade;
										$paginacao->pg          = $pg;
										$paginacao->getPaginacao();

									}else{
										if (isset($pg) && $pg > 1):
											echo '<script type="text/javascript">location.href = "./?cronometro";</script>';
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

<!-- Eliminar cronometro -->
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

<!-- Criar cronometro -->
<div data-backdrop="static" data-keyboard="false" class="modal fade" id="modal-add-item" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<div class="modal-header">
			<h5>Novo cronômetro</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<div id="add-input">
			<div class="modal-body">
				<form id="cronometro-form" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="add_cronometro" value="true">
					<input type="hidden" name="header" value="application/json">
					<label>Arquivo</label>
					<div class="input-group" style="margin-bottom: 5px;">
						<div class="custom-file">
							<input type="file" name="file[]" onchange="fileName(this.value)" class="custom-file-input" id="input-cronometro">
							<label id="input-file-label" class="custom-file-label" for="input-cronometro">Escolher arquivo</label>
						</div>
					</div>
					<small class=" mb-1 d-block">Suporte: png, jpg, jpeg, mp4, mkv.</small>

					<div class="form-group">
						<label for="cronometro-nome">Título</label>
						<input type="text" name="titulo" class="form-control" id="cronometro-titulo" placeholder="Ex: Nome do evento">
					</div>

					<div class="form-group">
						<label for="cronometro-nome">Data</label>
						<input type="date" name="data" class="form-control" id="cronometro-data">
					</div>
					<div class="form-group">
						<label for="cronometro-nome">Hora</label>
						<input type="text" id="timepicker" name="hora" class="form-control" placeholder="00:00">
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				<button type="button" class="btn btn-danger" id="add-cronometro">Registar</button>
			</div>			
		</div>

		<div id="add-upload">
			<div class="modal-body">
			  	<div class="progress mb-2">
					<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<p><small>Estamos agendando o seu evento, evite atualizar a paginar ou efetuar qualquer alteração.</small></p>				
			</div>
		</div>

    </div>
  </div>
</div>

<script src="assets/js/cronometro/contador.js"></script>
<script src="assets/js/mdtimepicker.js"></script>
<script>
$(document).ready(function(){
	$('#timepicker').mdtimepicker({
		theme: 'dark',
		timeFormat:'hh:mm',
		format:'hh:mm',
	});
 	 $('#timepicker').mdtimepicker().on('timechanged',function(e){
		console.log(e.time);
	});
});
</script>