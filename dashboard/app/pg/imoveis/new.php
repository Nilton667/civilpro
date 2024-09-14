<script src="assets/js/jqueryForm.js"></script>
<?php
	if(isset($_GET['edit'])){
		$data = new Util();
		$data = $data->getDataImoveis();
		$data = json_decode($data);
	}
?>
<div class="row justify-content-center">
	<div class="col-12 mt-2 p-0">
		<?php
		//Video
			if(isset($_GET['edit'])):
				?>
				<p class="lead"><b>Vídeo</b></p>
				<form method="POST" id="video-form" enctype="multipart/form-data">
					<input type="hidden" name="imoveis_id" id="imoveis_id" value="<?= $data[0]->id ?>">
					<input type="hidden" name="old_img" value="<?= $data[0]->video; ?>">
					<input type="hidden" name="setVideo" value="true">
					<div class="row edit-img">
						<div class="col-12 col-sm-3 col-md-2">
							<div class="edit-img-add d-flex align-items-center justify-content-center">
								<input type="file" name="video[]" id="add-video">
								<?= 
									is_file('../publico/video/imoveis/'.$data[0]->video) 
									? '<i class="las la-sync-alt"></i>' 
									: '<i class="las la-plus"></i>'; 
								?>
							</div>
						</div>
						<?php
							if(is_file('../publico/video/imoveis/'.$data[0]->video)):
								?>
								<div class="col-12 col-sm-4 col-md-3">
									<div>
										<i class="artigo-remove-image artigo-remove-image-video las la-times" data-video="<?= $data[0]->video ?>"></i>
										<video style="width: 100%; height: 100%;"
										controls="" 
										class="w-100 h-100" 
										src="../publico/video/imoveis/<?= $data[0]->video ?>"></video>
									</div>
								</div>									
								<?php
							endif;
						?>
					</div>
				</form>
				<?php
			endif;
		?>

		<?php
		//Imagens
			if(isset($_GET['edit'])):
				?>
				<p class="lead"><b>Imagens</b></p>
				<form method="POST" id="image-form" enctype="multipart/form-data">
					<input type="hidden" name="imoveis_id" id="imoveis_id" value="<?= $data[0]->id ?>">
					<input type="hidden" name="add_image" value="true">
					<div class="row edit-img">
						<div class="col-6 col-sm-3 col-md-2">
							<div class="edit-img-add d-flex align-items-center justify-content-center">
								<input multiple type="file" name="img[]" id="add-image">
								<i class="las la-plus"></i>
							</div>
						</div>
						<?php
							if(is_file('../publico/img/imoveis/'.$data[0]->imagem)):
								?>
								<div class="col-6 col-sm-3 col-md-2">
									<div>
										<a class="image-link" href="../publico/img/imoveis/<?= $data[0]->imagem ?>">
											<img src="../publico/img/imoveis/<?= $data[0]->imagem ?>">
										</a>
									</div>
								</div>									
								<?php
							endif;
							$imagens = new Util();
							$imagens = $imagens->getImageImoveis($data[0]->id);
							$imagens = json_decode($imagens, true);
							if(is_array($imagens)):
								foreach ($imagens as $key => $value){
									if(is_file('../publico/img/imoveis/'.$value['imagem'])):
										?>
										<div class="col-6 col-sm-3 col-md-2">
											<div>
											<i class="artigo-remove-image las la-times" data-image="<?= $value['imagem'] ?>"></i>
											<i class="artigo-update-image las la-sync-alt" data-image="<?= $value['imagem'] ?>"></i>
												<a class="image-link" href="../publico/img/imoveis/<?= $value['imagem'] ?>">
													<img src="../publico/img/imoveis/<?= $value['imagem'] ?>">
												</a>
											</div>
										</div>									
										<?php
									endif;
								}
							endif;
						?>
					</div>
				</form>
				<?php
			endif;
		?>

		<form method="POST" enctype="multipart/form-data" id="imoveis-form">

			<div class="row pb-2">
				<div class="col-12 text-right">
					<?php
						if (isset($_GET['edit'])){
							?>
								<button type="button" 
								onclick="location.href='./?imoveis&view=<?php echo trim($_GET['edit']); ?>'"  
								class="btn btn-warning"><i class="las la-eye"></i></button>

								<button type="button" id="edit-imoveis" class="btn btn-primary"><i class="las la-edit"></i></button>
							<?php
						}else{
							?>
								<button type="reset" id="reset-imoveis" class="btn btn-danger">
									<i class="las la-redo-alt"></i>
								</button>
								<button type="button" id="news-imoveis" class="btn btn-primary">
									<i class="las la-paper-plane"></i>
								</button>
							<?php
						}
						$edit = isset($data[0]->id) ? true : false;
					?>
				</div>
			</div>

			<div>
				<input type="hidden" name="imoveis_id" id="imoveis-id" value="<?php if($edit){ echo $data[0]->id; } ?>">
				<div class="row">

					<div class="col-12 col-md-6">
						<div class="form-group mb-3">
							<input style="outline: none!important; box-shadow: unset!important;" type="text" class="form-control border-top-0 border-left-0 border-right-0 rounded-0 shadow-none" id="titulo" placeholder="Título" name="titulo" value="<?php if($edit){ echo $data[0]->titulo; } ?>">
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group mb-3">
							<input style="outline: none!important; box-shadow: unset!important;" type="text" class="form-control border-top-0 border-left-0 border-right-0 rounded-0 shadow-none" id="subtitulo" placeholder="Subtítulo" name="subtitulo" value="<?php if($edit){ echo $data[0]->subtitulo; } ?>">
						</div>				
					</div>

					<?php 
						if(isset($_GET['edit'])){
							?>
							<input type="hidden" name="old_img" value="<?= $data[0]->imagem; ?>">
							<?php
						}
					?>

					<?php 
						if(!isset($_GET['edit'])){
						?>
						<div class="col-12 col-md-6">
							<label>Imagem</label>
							<div class="input-group mb-3">
								<div class="custom-file">
									<input onchange="fileName(this.value)" type="file" class="custom-file-input" id="image" name="img[]">
									<label id="input-file-label" class="custom-file-label" for="image">Escolher arquivo(s)</label>
								</div>
								<div class="input-group-append" data-toggle="tooltip" title="Visualizar">
									<button id="visualizar-imagem" class="btn btn-outline-secondary" type="button">
										<i class="las la-eye"></i>
									</button>
								</div>
							</div>
						</div>
						<?php
						}
					?>

					<!--<div class="col-12 col-md-6">
						<div class=" mb-3">
							<label>Vídeo</label>
							<div class="input-group">
								<div class="custom-file">
									<input onchange="videoName(this.value)" type="file" class="custom-file-input" id="video" name="video[]">
									<label id="input-video-label" class="custom-file-label" for="video">Escolher arquivo(s)</label>
								</div>
							</div>
							<small>Suporte: mp4, mkv.</small>							
						</div>
					</div>-->

					<div class="col-12 col-md-6">
						<label>Youtube</label>
						<div class="input-group mb-3">
						<input type="url" class="form-control" placeholder="Link do seu video aqui" id="youtube" name="youtube" value="<?php if($edit){ echo $data[0]->youtube; } ?>">
							<div class="input-group-append" data-toggle="tooltip" title="Testar">
								<button id="video-test" class="btn btn-outline-secondary" type="button">
									<i class="lab la-youtube"></i>
								</button>
							</div>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Categoria</label>
							<select class="custom-select" id="categoria" name="categoria">
							<option value="">-- Selecione uma categoria --</option>
							<?php
								$categoria = new Util();
								$categoria = $categoria->getCategoria();
								$categoria = json_decode($categoria);

								if (is_array($categoria)) {
									foreach ($categoria as $key => $value) {
										?>
											<option 
												value="<?php echo $value->categoria ?>"
												<?php 
													if($edit){ 
														if($data[0]->categoria == $value->categoria){
															$dataCategoria = $value->categoria;
															echo "selected"; 
														} 
													} 
												?>
												>
												<?php echo $value->categoria; ?>
											</option>
										<?php
									}
								}
							?>
							</select>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3 sub-update">
							<label>Subcategoria</label>
							<select <?php if(!isset($dataCategoria) || $dataCategoria == '' || $dataCategoria == DEFAULT_STRING){ echo 'disabled'; } ?> class="custom-select" id="subcategoria" name="subcategoria">
							<option value="">-- Selecione uma subcategoria --</option>
							<?php
								$subcategoria = new Util();
								$subcategoria->filter = isset($dataCategoria) ? $dataCategoria : '';
								$subcategoria = $subcategoria->getsubcategoria();
								$subcategoria = json_decode($subcategoria);

								if (is_array($subcategoria)) {
									foreach ($subcategoria as $key => $value) {
										?>
											<option 
												value="<?php echo $value->subcategoria ?>"
												<?php 
													if($edit){ 
														if($data[0]->subcategoria == $value->subcategoria){ 
															echo "selected"; 
														} 
													} 
												?>
												>
												<?php echo $value->subcategoria; ?>
											</option>
										<?php
									}
								}
							?>
							</select>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Pavimentos</label>
							<select class="custom-select" id="pavimento" name="pavimento">
							<option value="">-- Selecione --</option>
							<?php
								$pavimento = new Util();
								$pavimento = $pavimento->getPavimento();
								$pavimento = json_decode($pavimento);

								if (is_array($pavimento)) {
									foreach ($pavimento as $key => $value) {
										?>
											<option 
												value="<?php echo $value->pavimento ?>"
												<?php 
													if($edit){ 
														if($data[0]->pavimento == $value->pavimento){ 
															echo "selected"; 
														} 
													} 
												?>
												>
												<?php echo $value->pavimento; ?>
											</option>
										<?php
									}
								}
							?>
							</select>

						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Terreno</label>
							<select class="custom-select" id="tamanho" name="tamanho">
							<option value="">-- Selecione um tamanho --</option>
							<?php
								$tamanho = new Util();
								$tamanho = $tamanho->getTamanho();
								$tamanho = json_decode($tamanho);

								if (is_array($tamanho)) {
									foreach ($tamanho as $key => $value) {
										?>
											<option 
												value="<?php echo $value->tamanho ?>"
												<?php 
													if($edit){ 
														if($data[0]->tamanho == $value->tamanho){ 
															echo "selected"; 
														} 
													} 
												?>
												>
												<?php echo $value->tamanho; ?>
											</option>
										<?php
									}
								}
							?>
							</select>

						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Dormitórios</label>
							<select class="custom-select" id="dormitorio" name="dormitorio">
							<option value="">-- Selecione --</option>
							<?php
								$dormitorio = new Util();
								$dormitorio = $dormitorio->getDormitorio();
								$dormitorio = json_decode($dormitorio);

								if (is_array($dormitorio)) {
									foreach ($dormitorio as $key => $value) {
										?>
											<option 
												value="<?php echo $value->dormitorio; ?>"
												<?php 
													if($edit){ 
														if($data[0]->dormitorio == $value->dormitorio){ 
															echo "selected"; 
														} 
													} 
												?>
												>
												<?php echo $value->dormitorio; ?>
											</option>
										<?php
									}
								}
							?>
							</select>

						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Banheiros</label>
							<select class="custom-select" id="banheiro" name="banheiro">
							<option value="">-- Selecione --</option>
							<?php
								$banheiro = new Util();
								$banheiro = $banheiro->getBanheiro();
								$banheiro = json_decode($banheiro);

								if (is_array($banheiro)) {
									foreach ($banheiro as $key => $value) {
										?>
											<option 
												value="<?php echo $value->banheiro; ?>"
												<?php 
													if($edit){ 
														if($data[0]->banheiro == $value->banheiro){ 
															echo "selected"; 
														} 
													} 
												?>
												>
												<?php echo $value->banheiro; ?>
											</option>
										<?php
									}
								}
							?>
							</select>

						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Garagem</label>
							<select class="custom-select" id="garagem" name="garagem">
							<option value="">-- Selecione --</option>
							<?php
								$garagem = new Util();
								$garagem = $garagem->getGaragem();
								$garagem = json_decode($garagem);

								if (is_array($garagem)) {
									foreach ($garagem as $key => $value) {
										?>
											<option 
												value="<?php echo $value->garagem; ?>"
												<?php 
													if($edit){ 
														if($data[0]->garagem == $value->garagem){ 
															echo "selected"; 
														} 
													} 
												?>
												>
												<?php echo $value->garagem; ?>
											</option>
										<?php
									}
								}
							?>
							</select>

						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Área Construída</label>
							<select class="custom-select" id="area_construida" name="area_construida">
							<option value="">-- Selecione --</option>
							<?php
								$area_construida = new Util();
								$area_construida = $area_construida->getAreaConstruida();
								$area_construida = json_decode($area_construida);

								if (is_array($area_construida)) {
									foreach ($area_construida as $key => $value) {
										?>
											<option 
												value="<?php echo $value->area; ?>"
												<?php 
													if($edit){ 
														if($data[0]->area_construida == $value->area){ 
															echo "selected"; 
														} 
													} 
												?>
												>
												<?php echo $value->area; ?>
											</option>
										<?php
									}
								}
							?>
							</select>

						</div>
					</div>

					<div class="col-12 col-md-6">
						<label>Preço</label>
						<div class="input-group mb-3">
						<input type="number" class="form-control" id="preco" name="preco" value="<?php if($edit): echo $data[0]->preco; else: echo 0; endif; ?>">
						</div>
					</div>
					
					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Estado</label>
							<select class="custom-select" id="estado" name="estado">
								<option value="1" 
								<?= isset($data[0]->estado) && $data[0]->estado == 1 ? 'selected' : ''; ?>>Visível</option>
								<option value="0" 
								<?= isset($data[0]->estado) && $data[0]->estado == 0 ? 'selected' : ''; ?>>Oculto</option>
							</select>
						</div>
					</div>

					<?php
					if($edit && isset($data[0]->documentacao) && $data[0]->documentacao != '' && $data[0]->documentacao != 'n/a'){
						?>
						<div class="col-12 col-md-6">
							<div class="mb-3 p-1" style="border: #ebe9ee 1px solid; border-radius: 8px;">
								<a href="../publico/img/imoveis/<?= $data[0]->documentacao; ?>" target="_blank" style="text-decoration: none;">
								<div class="media">
									<i class="las la-file la-2x"></i>
									<div class="media-body">
										<h5 class="mt-0">Documentação.</h5>
										Clique aqui para ver a documentação.
									</div>
									</div>
								</a>
							</div>
						</div>
						<?php
					}

					if($edit && isset($data[0]->id_usuario) && $data[0]->id_usuario != 0){
						$userDataRegistered = DB\Mysql::get("usuarios", "*", "id = {$data[0]->id_usuario}", 1);
						if(is_array($userDataRegistered)):
						?>
						<div class="col-12 col-md-6">
							<div class="mb-3 p-1" style="border: #ebe9ee 1px solid; border-radius: 8px;">
								
								<div class="media">

									<div class="media-body">
										<h4>Dados do fornecedor</h4>
										<hr>
										<h5 class="mt-0 mb-2"><?= $userDataRegistered[0]['nome'].' '.$userDataRegistered[0]['sobrenome']; ?></h5>
										
										<p class="mb-1 d-flex align-items-center">
											<i class="las la-id-card red-text" style="font-size: 1.45em;"></i> 
											<span style="font-size: .9em; font-weight: 500;" 
											class="ml-1"><?= $userDataRegistered[0]['identificacao'] != '' ? $userDataRegistered[0]['identificacao'] : 'n/a'; ?></span>
										</p>
										<p class="mb-1 d-flex align-items-center">
											<i class="las la-map red-text" style="font-size: 1.45em;"></i> 
											<span style="font-size: .9em; font-weight: 500;" 
											class="ml-1"><?= $userDataRegistered[0]['morada'] != '' ? $userDataRegistered[0]['morada'] : 'n/a'; ?></span>
										</p>
										<p class="mb-1 d-flex align-items-center">
											<i class="las la-phone red-text" style="font-size: 1.45em;"></i> 
											<span style="font-size: .9em; font-weight: 500;" class="ml-1">
											<?php 
												if( $userDataRegistered[0]['telemovel'] != ''):
													$phoneFormat = str_split($userDataRegistered[0]['telemovel']);
													$userDataTelemovel = '';
													foreach ($phoneFormat as $key => $value):
														if($key == 3 || $key == 6 || $key == 9 || $key == 12 || $key == 15):
															$userDataTelemovel .= ' '.$value;
															continue;
														endif;
														$userDataTelemovel .= $value;
													endforeach; 
													eco($userDataTelemovel);
												else:
													eco('n/a');
												endif;
											?>
											</span>
										</p>
										<p class="mb-0 d-flex align-items-center">
											<i class="las la-transgender-alt red-text" style="font-size: 1.45em;"></i> 
											<span style="font-size: .9em; font-weight: 500;" 
											class="ml-1"><?= $userDataRegistered[0]['genero'] != '' ? $userDataRegistered[0]['genero'] : 'n/a'; ?></span>
										</p>

									</div>
								</div>
								
							</div>
						</div>
						<?php
						endif;
					}

					?>

				</div>

				<input type="hidden" name="descricao" id="descricao">
				<?php
					if (isset($_GET['edit'])){
						?><input type="hidden" name="editImovel"><?php
					}else{
						?><input type="hidden" name="setImovel"><?php
					}
				?>
				<div id="imoveis-text"><?php if($edit){ echo $data[0]->descricao; } ?></div>

			</div>	
		</form>

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

<!-- Visualizar video -->
<div class="modal fade" id="youtube-test" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="lab la-youtube"></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <iframe id="youtube-frame" width="100%" height="315" src="https://www.youtube.com/embed/" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Add video -->
<div data-backdrop="static" data-keyboard="false" class="modal fade" id="add-video-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

	    <div class="modal-header">
	        <h5 class="modal-title"><i class="las la-video"></i></h5>
	    </div>
		  
		<div class="modal-body">

			<div class="add-video-comfirm">
				<p class="lead">Pretende mesmo adicionar o vídeo selecionado!</p>
				<div class="text-right">
					<button class="btn btn-danger" data-dismiss="modal">Não</button>
					<button class="btn btn-primary add-video">Adicionar</button>			
				</div>
			</div>
			<div class="add-video-upload">
				<div class="progress mb-2">
				<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<p><small>Evite atualizar a paginar ou efetuar qualquer alteração.</small></p>
			</div>

		</div>
	
    </div>
  </div>
</div>

<!-- Add image -->
<div  data-backdrop="static" class="modal fade" id="add-image-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

	    <div class="modal-header">
	        <h5 class="modal-title"><i class="las la-image"></i></h5>
	    </div>
		  
		<div class="modal-body">
			<p class="lead">Pretende mesmo adicionar a imagem selecionada!</p>
			<div class="text-right">
				<button class="btn btn-danger" data-dismiss="modal">Não</button>
				<button class="btn btn-primary add-image">Adicionar</button>			
			</div>
		</div>
	
    </div>
  </div>
</div>

<!-- Altera imagem principal -->
<div class="modal fade" id="change-init-image" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="las la-image"></i></h5>
      </div>
	  
		<div class="modal-body">
			<input type="hidden" name="old_image" id="old_image">
			<p class="lead">Pretente mesmo definir esta imagem como padrão!</p>
			<div class="text-right">
				<button class="btn btn-danger" data-dismiss="modal">Fechar</button>
				<button class="btn btn-primary change-image">Definir</button>			
			</div>
		</div>
	
    </div>
  </div>
</div>

<!-- Upload pogress -->
<div data-backdrop="static" data-keyboard="false" class="modal fade" id="progress" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="las la-upload"></i></h5>
      </div>
	  
	  <div class="modal-body">
	  	<div class="progress mb-2">
			<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
		<p><small>Estamos publicando o seu imóvel evite atualizar a paginar ou efetuar qualquer alteração.</small></p>
	  </div>

    </div>
  </div>
</div>

<script src="assets/ckeditor4/ckeditor.js"></script>
<script src="assets/js/imoveis/new.js"></script>