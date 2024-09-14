<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Reservas</h3>
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
										<input type="hidden" name="reservas">
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
										$select = "SELECT * from city_reservas WHERE id LIKE '%$busca%' OR data LIKE '%$busca%'";
										$select.= " OR registo LIKE '%$busca%' ORDER BY id DESC";
									else:
										$select = 'SELECT * from city_reservas ORDER BY id DESC';
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
											<input type="checkbox" class="custom-control-input" id="reserva-id">
											<label class="custom-control-label" for="reserva-id"></label>
										</div>'
										);

										$table.= trim('
										    <th scope="col">#</th>
										    <th scope="col">City Tour</th>
										    <th scope="col">Cliente</th>
										    <th scope="col">Passageiro(s)</th>
											<th scope="col">Data</th>
											<th scope="col">Hora</th>
										    <th scope="col">Registo</th>
										    <th scope="col">'.$tableCheck.'</th>
										');

										$table       .= '</tr>';
										$table       .= '</thead>';
										$table       .= '<tbody>';
										$tableClose   = '<tbody></table></div>';
										echo $table;
										while($mostra = $result->FETCH(PDO::FETCH_OBJ)){
											@$clienteData   = json_decode(getCliente($mostra->id_usuario, ''), true);
											@$tourDada      = json_decode(getTour($mostra->city, ''), true);
											@$data          = explode('/', $mostra->data);
											@$dias_restantes = strtotime($data[2].'-'.$data[1].'-'.$data[0]);
											if($dias_restantes != false){
												$dias_restantes = $dias_restantes - (strtotime(date('Y-m-d')));
											}
										?>
										<tr class="<?php
											if(is_numeric($dias_restantes) && $dias_restantes == 0){
												//Marcado para hoje
												eco('table-info'); 
											}else if($dias_restantes != false && $dias_restantes < 0){ 
												//Data ultrapassada
												eco('table-danger'); 
											}else if($dias_restantes != false && $dias_restantes <= (86400 * 2)){
												//No maximo 2 dias restantes
												eco('table-warning');
											}else{
												eco('tr');
											}
										?>">
											<th scope="row"><?= $mostra->id; ?></th>
                                            <td>
											<?= is_array($tourDada) ? $tourDada[0]['city'] : DEFAULT_STRING; ?>
                                            </td>
                                            <td><?= is_array($clienteData) ? $clienteData[0]['nome'].' '.$clienteData[0]['sobrenome'] : DEFAULT_STRING; ?></td>
                                            <td><?= $mostra->passageiros; ?></td>
											<td><?= $mostra->data; ?></td>
											<td><?= $mostra->hora; ?></td>
											<td><?= $mostra->registo; ?></td>
											<th>
												<div class="d-flex w-100 align-items-center">
												<div class="custom-control custom-checkbox d-inline-block">

												<input 
												type="checkbox" 
												class="custom-control-input"
												reserva-select="<?= $mostra->id; ?>"
												id="reserva-<?= $mostra->id; ?>">
												
												<label 
												class="custom-control-label" 
												for="reserva-<?= $mostra->id; ?>"></label>
											
												<i class="las la-info-circle"
												id="modal-info"
												data-nome="<?= is_array($clienteData)     ? $clienteData[0]['nome'].' '.$clienteData[0]['sobrenome'] : DEFAULT_STRING; ?>" 
												data-email="<?= is_array($clienteData)    ? $clienteData[0]['email']    : DEFAULT_STRING; ?>"
												data-contacto="<?= is_array($clienteData) ? $clienteData[0]['telemovel'] : DEFAULT_STRING; ?>"
												data-morada="<?= is_array($clienteData)   ? $clienteData[0]['morada']   : DEFAULT_STRING; ?>"
												data-id="<?= $mostra->id; ?>"
												data-total="<?= get_moeda($tourDada[0]['preco'] * $mostra->passageiros); ?>"></i>

												</div>	                                        		
												</div>
											</th>
										</tr>
										<?php
										}
										
										echo $tableClose;

										//Paginação
										$paginacao = new Paginacao();
										$paginacao->queryString = 'reservas';
										$paginacao->select      = $select;
										$paginacao->quantidade  = $quantidade;
										$paginacao->pg          = $pg;
										$paginacao->getPaginacao();

									}else{
										if (isset($pg) && $pg > 1):
											echo '<script type="text/javascript">location.href = "./?reservas";</script>';
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

<!-- Informações adicionais -->
<div class="modal fade" id="modal-info-view" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
			<h5>Informações adicionais</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
  
		<div class="modal-body">
			<p><b>Nome:</b> <span class="info-nome"></span></p>
			<hr>
			<p><b>Email:</b> <span class="info-email"></span></p>
			<hr>
			<p><b>Contacto:</b> <span class="info-contacto"></span></p>
			<hr>
			<p><b>Morada:</b> <span class="info-morada"></span></p>
			<hr>
			<p><b>Total a pagar:</b> <span class="info-total"></span></p>
		</div>
  
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
		</div>
	  </div>
	</div>
</div>

<!-- Anular reserva -->
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

<script src="assets/js/turismo/reserva.js"></script>