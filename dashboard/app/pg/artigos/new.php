<script src="assets/js/jqueryForm.js"></script>
<?php
	if(isset($_GET['edit'])){
		$data = new Util();
		$data = $data->getArtigo();
		$data = json_decode($data);
	}
?>

<style type="text/css">
	.cores, .tamanhos{
		display: flex;
	}
	.cores > div, .tamanhos > div{
		border: #c9c9c9 1px solid;
		padding: 8px;
		border-radius: 5px;
		box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.1);
		margin: 5px;
		cursor: pointer;
		position: relative;
		font-size: 1em;
		font-weight: 500;
	}
	.cores > div i, .tamanhos > div i{
		background-color: red;
		padding: 5px;
		border-radius: 50%;
		color: #fff;
		height: 20px;
		width: 20px;
		font-size: .8em;
		text-align: center;
		transition: all 0.3s;
		display: inline-flex;
		justify-content: center;
		align-items: center;
	}
	.cores > div i:hover, .tamanhos > div i:hover{
		background-color: #444444;
	}
</style>

<div class="row justify-content-center">
	<div class="col-12 mt-2 p-0">
		<?php
			if(isset($_GET['edit'])):
				?>
				<p class="lead"><b>Imagens</b></p>
				<form method="POST" id="image-form" enctype="multipart/form-data">
					<input type="hidden" name="artigo_id" id="artigo_id" value="<?= $data[0]->id ?>">
					<input type="hidden" name="add_image" value="true">
					<div class="row edit-img">
						<div class="col-6 col-sm-3 col-md-2">
							<div class="edit-img-add d-flex align-items-center justify-content-center">
								<input multiple type="file" name="img[]" id="add-image">
								<i class="las la-plus"></i>
							</div>
						</div>
						<?php
							if(is_file('../publico/img/artigos/'.$data[0]->imagem)):
								?>
								<div class="col-6 col-sm-3 col-md-2">
									<div>
										<a class="image-link" href="../publico/img/artigos/<?= $data[0]->imagem ?>">
											<img src="../publico/img/artigos/<?= $data[0]->imagem ?>">
										</a>
									</div>
								</div>									
								<?php
							endif;
							$imagens = new Util();
							$imagens = $imagens->getImageArtigos($data[0]->id);
							$imagens = json_decode($imagens, true);
							if(is_array($imagens)):
								foreach ($imagens as $key => $value){
									if(is_file('../publico/img/artigos/'.$value['imagem'])):
										?>
										<div class="col-6 col-sm-3 col-md-2">
											<div>
											<i class="artigo-remove-image las la-times" data-image="<?= $value['imagem'] ?>"></i>
											<i class="artigo-update-image las la-sync-alt" data-image="<?= $value['imagem'] ?>"></i>
												<a class="image-link" href="../publico/img/artigos/<?= $value['imagem'] ?>">
													<img src="../publico/img/artigos/<?= $value['imagem'] ?>">
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
		<form method="POST" enctype="multipart/form-data" id="blog-form">

			<div class="row pb-2">
				<div class="col-12 text-right">
					<?php
						if (isset($_GET['edit'])){
							?>
								<button type="button" 
								onclick="location.href='./?artigos&view=<?php echo trim($_GET['edit']); ?>'"  
								class="btn btn-warning"><i class="las la-eye"></i></button>

								<button type="button" id="edit-artigo" class="btn btn-primary"><i class="las la-edit"></i></button>
							<?php
						}else{
							?>
								<button type="reset" id="reset-artigo" class="btn btn-danger">
									<i class="las la-redo-alt"></i>
								</button>
								<button type="button" id="news-artigo" class="btn btn-primary">
									<i class="las la-paper-plane"></i>
								</button>
							<?php
						}
						$edit = isset($data[0]->id) ? true : false;
					?>
				</div>
			</div>

			<div>
				<input type="hidden" name="artigo_id" id="artigo_id" value="<?php if($edit): echo $data[0]->id; endif; ?>">
				<div class="row">

					<div class="col-12">
						<div class="form-group mb-3">
							<input style="outline: none!important; box-shadow: unset!important;" type="text" class="form-control border-top-0 border-left-0 border-right-0 rounded-0 shadow-none" id="nome" placeholder="Nome" name="nome" value="<?php if($edit){ echo $data[0]->nome; } ?>">
						</div>
					</div>

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
									<button id="visualizar-imagem" class="btn btn-outline-secondary" type="button"><i class="las la-eye"></i></button>
								</div>
								</div>
							</div>					
							<?php
						}
					?>

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
							<label>Cor</label>
							<select class="custom-select" id="cores" name="cores">
							<option value="">-- Selecione uma cor --</option>
							<?php
								$cor = new Util();
								$cor = $cor->getCor();
								$cor = json_decode($cor);

								if (is_array($cor)){
									foreach ($cor as $key => $value){
										?>
											<option value="<?= $value->cor ?>"><?= $value->cor; ?></option>
										<?php
									}
								}
							?>
							</select>

							<!-- Cores existentes -->
							<div class="cores">
								<?php
									if(isset($data[0]->cor)){
										$cores = explode(',', $data[0]->cor);
										$key   = array_search('', $cores);
										if($key!==false){
											unset($cores[$key]);
										}
										foreach ($cores as $key => $value) {
											?>
												<div cor="<?= $value; ?>"><?= $value; ?> <i class="remove-cor las la-times"></i></div>
											<?php
										}
									}
								?>
								<input id="cor" name="cor" type="hidden" value="">
							</div>

						</div>
					</div>
	
					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Tamanho</label>
							<select class="custom-select" id="tamanhos" name="tamanhos">
							<option value="">-- Selecione um tamanho --</option>
							<?php
								$tamanho = new Util();
								$tamanho = $tamanho->getTamanho();
								$tamanho = json_decode($tamanho);

								if (is_array($tamanho)) {
									foreach ($tamanho as $key => $value) {
										?>
											<option value="<?= $value->tamanho ?>"><?= $value->tamanho; ?></option>
										<?php
									}
								}
							?>
							</select>

							<!-- tamanhos existentes -->
							<div class="tamanhos">
								<?php
									if(isset($data[0]->tamanho)){
										$tamanho = explode(',', $data[0]->tamanho);
										$key     = array_search('', $tamanho);
										if($key!==false){
											unset($tamanho[$key]);
										}
										foreach ($tamanho as $key => $value) {
											?>
												<div tamanho="<?= $value; ?>"><?= $value; ?> <i class="remove-tamanho las la-times"></i></div>
											<?php
										}
									}
								?>
								<input id="tamanho" name="tamanho" type="hidden" value="">
							</div>

						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Marca</label>
							<select class="custom-select" id="marca" name="marca">
							<option value="">-- Selecione uma marca --</option>
							<?php
								$marca = new Util();
								$marca = $marca->getMarca();
								$marca = json_decode($marca);

								if (is_array($marca)) {
									foreach ($marca as $key => $value) {
										?>
											<option 
												value="<?php echo $value->marca ?>"
												<?php 
													if($edit){ 
														if($data[0]->marca == $value->marca){ 
															echo "selected"; 
														} 
													} 
												?>
												>
												<?php echo $value->marca; ?>
											</option>
										<?php
									}
								}
							?>
							</select>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<label>Quantidade</label>
						<div class="input-group mb-3">
						<input type="number" class="form-control" id="quantidade" name="quantidade" value="<?php if($edit): echo $data[0]->quantidade; else: echo 0; endif; ?>">
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

				</div>

				<input type="hidden" name="descricao" id="descricao">
				<?php
					if (isset($_GET['edit'])) {
						?><input type="hidden" name="edit_artigo"><?php
					}else{
						?><input type="hidden" name="set_artigo"><?php
					}
				?>
				<div id="artigo-text"><?php if($edit){ echo $data[0]->descricao; } ?></div>

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
<div  data-backdrop="static" data-keyboard="false" class="modal fade" id="progress" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="las la-upload"></i></h5>
      </div>
	  
	  <div class="modal-body">
	  	<div class="progress mb-2">
			<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
		<p><small>Estamos publicando o seu artigo evite atualizar a paginar ou efetuar qualquer alteração.</small></p>
	  </div>
	
    </div>
  </div>
</div>

<script src="assets/ckeditor4/ckeditor.js"></script>
<script src="assets/js/artigo/new.js"></script>
<script>
$(document).ready(function(){
	//Selecionar cores
 	$('#cores').change(function(e){

		let compare = this.value.trim();
		var nulo    = 0;
		$('.cores > div').each(function(e) {
            if($(this).attr('cor').trim() == compare){
                nulo = 1;
            }
		})
		if(nulo == 1){ 
			document.querySelector('#cores').value = '';
			return; 
		}
		document.querySelector('.cores').insertAdjacentHTML('afterbegin', '<div cor='+this.value.trim()+'>'+this.value.trim()+' <i class="remove-cor las la-times"></i></div>');
		document.querySelector('#cores').value = '';

	});
	$('body').delegate('.cores > div i', 'click', function(){
		var element = $(this).parent();
		element.remove();
	});

	//Selecionar tamanhos
	$('#tamanhos').change(function(e){

	let compare = this.value.trim();
	var nulo    = 0;
	$('.tamanhos > div').each(function(e) {
		if($(this).attr('tamanho').trim() == compare){
			nulo = 1;
		}
	})
	if(nulo == 1){ 
		document.querySelector('#tamanhos').value = '';
		return; 
	}
	document.querySelector('.tamanhos').insertAdjacentHTML('afterbegin', '<div tamanho='+this.value.trim()+'>'+this.value.trim()+' <i class="remove-tamanho las la-times"></i></div>');
	document.querySelector('#tamanhos').value = '';

	});
	$('body').delegate('.tamanhos > div i', 'click', function(){
	var element = $(this).parent();
	element.remove();
	});

});
</script>