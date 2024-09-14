<script src="assets/js/jqueryForm.js"></script>
<?php
	if(isset($_GET['edit'])){
		$data = new Util();
		$data = $data->getDataServicos();
		$data = json_decode($data);
	}
?>
<div class="row justify-content-center">
	<div class="col-12 mt-2 p-0">
		<?php
			if(isset($_GET['edit'])):
				if(is_file('../publico/img/servicos/'.$data[0]->imagem)){
					?>
					<p class="lead"><b>Imagem</b></p>
					<div class="row edit-img">
						<div class="col-6 col-sm-3 col-md-2">
							<div>
								<a class="image-link" href="../publico/img/servicos/<?= $data[0]->imagem ?>">
									<img src="../publico/img/servicos/<?= $data[0]->imagem ?>">
								</a>
							</div>
						</div>
					</div>
					<?php
				}
			endif;
		?>
		<form method="POST" enctype="multipart/form-data" id="service-form">

			<div class="row pb-2">
				<div class="col-12 text-right">
					<?php
						if (isset($_GET['edit'])){
							?>
								<button type="button" 
								onclick="location.href='./?servicos_list&view=<?php echo trim($_GET['edit']); ?>'"  
								class="btn btn-warning"><i class="las la-eye"></i></button>

								<button type="button" id="edit-service" class="btn btn-primary"><i class="las la-edit"></i></button>
							<?php
						}else{
							?>
								<button type="reset" id="reset-post" class="btn btn-danger">
									<i class="las la-redo-alt"></i>
								</button>
								<button type="button" id="news-service" class="btn btn-primary">
									<i class="las la-paper-plane"></i>
								</button>
							<?php
						}
						$edit = isset($data[0]->id) ? true : false;
					?>
				</div>
			</div>

			<div>
				<input type="hidden" name="blog_id" id="post-id" value="<?php if($edit){ echo $data[0]->id; } ?>">
				<div class="row">

					<div class="col-12">
						<div class="form-group mb-3">
							<input style="outline: none!important; box-shadow: unset!important;" type="text" class="form-control border-top-0 border-left-0 border-right-0 rounded-0 shadow-none" id="nome" placeholder="Nome" name="nome" value="<?php if($edit){ echo $data[0]->nome; } ?>">
						</div>
					</div>

					<?php 
						if(isset($_GET['edit'])){
							?>
							<input type="hidden" name="old_img" value="<?= $data[0]->imagem; ?>">
							<?php
						}
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

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label>Categoria</label>
							<select class="custom-select" id="categoria" name="categoria">
							<option value="">-- Selecione uma categoria --</option>
							<?php
								$categoria = new Util();
								$categoria = $categoria->getCategoriaServicos();
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

					<div class="col-12 col-md-6">
						<label>Preço</label>
							<div class="input-group mb-3">
							<input type="number" class="form-control" id="preco" name="preco" value="<?php if($edit): echo $data[0]->preco; else: echo 0; endif; ?>">
						</div>
					</div>

				</div>

				<input type="hidden" name="descricao" id="descricao">
				<?php
					if (isset($_GET['edit'])){
						?><input type="hidden" name="editService"><?php
					}else{
						?><input type="hidden" name="setService"><?php
					}
				?>
				<div id="post-text"><?php if($edit){ echo $data[0]->descricao; } ?></div>

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
		<p><small>Estamos postando a sua publicação evite atualizar a paginar ou efetuar qualquer alteração.</small></p>
	  </div>

    </div>
  </div>
</div>

<script src="assets/ckeditor4/ckeditor.js"></script>
<script src="assets/js/servicos/new.js"></script>