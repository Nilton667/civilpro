<script src="assets/js/jqueryForm.js"></script>
<?php
	if(isset($_GET['edit'])){
		$data = new Util();
		$data = $data->getData();
		$data = json_decode($data);
	}
?>
<div class="row justify-content-center">
	<div class="col-12 mt-2 p-0">
		<?php
			if(isset($_GET['edit'])):
				if(is_file('../publico/img/posts/'.$data[0]->imagem)){
					?>
					<p class="lead"><b>Imagem</b></p>
					<div class="row edit-img">
						<div class="col-6 col-sm-3 col-md-2">
							<div>
								<a class="image-link" href="../publico/img/posts/<?= $data[0]->imagem ?>">
									<img src="../publico/img/posts/<?= $data[0]->imagem ?>">
								</a>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<p class="lead"><b>Vídeo</b></p>
				<form method="POST" id="video-form" enctype="multipart/form-data">
					<input type="hidden" name="blog_id" id="blog_id" value="<?= $data[0]->id ?>">
					<input type="hidden" name="old_img" value="<?= $data[0]->video; ?>">
					<input type="hidden" name="setVideo" value="true">
					<div class="row edit-img">
						<div class="col-12 col-sm-3 col-md-2">
							<div class="edit-img-add d-flex align-items-center justify-content-center">
								<input type="file" name="video[]" id="add-video">
								<?= 
									is_file('../publico/video/posts/'.$data[0]->video) 
									? '<i class="las la-sync-alt"></i>' 
									: '<i class="las la-plus"></i>'; 
								?>
							</div>
						</div>
						<?php
							if(is_file('../publico/video/posts/'.$data[0]->video)):
								?>
								<div class="col-12 col-sm-4 col-md-3">
									<div>
										<i class="artigo-remove-image las la-times" data-video="<?= $data[0]->video ?>"></i>
										<video style="width: 100%; height: 100%;"
										controls="" 
										class="w-100 h-100" 
										src="../publico/video/posts/<?= $data[0]->video ?>"></video>
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
		<form method="POST" enctype="multipart/form-data" id="blog-form">

			<div class="row pb-2">
				<div class="col-12 text-right">
					<?php
						if (isset($_GET['edit'])){
							?>
								<button type="button" 
								onclick="location.href='./?feed&post=<?php echo trim($_GET['edit']); ?>'"  
								class="btn btn-warning"><i class="las la-eye"></i></button>

								<button type="button" id="edit-post" class="btn btn-primary"><i class="las la-edit"></i></button>
							<?php
						}else{
							?>
								<button type="reset" id="reset-post" class="btn btn-danger">
									<i class="las la-redo-alt"></i>
								</button>
								<button type="button" id="news-post" class="btn btn-primary">
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
					if (isset($_GET['edit'])){
						?><input type="hidden" name="editPost"><?php
					}else{
						?><input type="hidden" name="setPost"><?php
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
<script src="assets/js/feed/new.js"></script>