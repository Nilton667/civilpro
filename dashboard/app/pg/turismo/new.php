<script src="assets/js/jqueryForm.js"></script>
<link rel="stylesheet" type="text/css" href="assets/css/mdtimepicker.css">
<link rel="stylesheet" type="text/css" href="assets/css/mdtimepicker-theme.css">

<style type="text/css">
	.horas{
		display: flex;
	}
	.horas > div{
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
	.horas > div i{
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
		align-items: ce;
	}
	.horas > div i:hover{
		background-color: #444444;
	}
</style>
<?php
if(isset($_GET['edit'])){
	$data = new Util();
	$data = $data->getCity();
	$data = json_decode($data);
}
?>
<div class="row justify-content-center">
	<div class="col-12 mt-2 p-0">
		<?php
			if(isset($_GET['edit'])):
				if(is_file('../publico/img/city/'.$data[0]->imagem)){
					?>
					<p class="lead"><b>Imagem</b></p>
					<div class="row edit-img">
						<div class="col-6 col-sm-3 col-md-2">
							<div>
								<a class="image-link" href="../publico/img/city/<?= $data[0]->imagem ?>">
									<img src="../publico/img/city/<?= $data[0]->imagem ?>">
								</a>
							</div>
						</div>
					</div>
					<?php
				}
			endif;
		?>
		<form method="POST" enctype="multipart/form-data" id="city-form">

			<div class="row pb-2">
				<div class="col-12 text-right">
					<?php
						if (isset($_GET['edit'])) {
							?>
								<button type="button" 
								onclick="location.href='./?city&view=<?php echo trim($_GET['edit']); ?>'"  
								class="btn btn-warning"><i class="las la-eye"></i></button>

								<button type="button" id="edit-city" class="btn btn-primary"><i class="las la-edit"></i></button>
							<?php
						}else{
							?>
								<button type="reset" id="reset-city" class="btn btn-danger">
									<i class="las la-redo-alt"></i>
								</button>
								<button type="button" name="add_city" id="news-city" class="btn btn-primary">
									<i class="las la-paper-plane"></i>
								</button>
							<?php
						}
						$edit = isset($data[0]->id) ? true : false;
					?>
				</div>
			</div>

			<div>
				<input type="hidden" name="city_id" id="city-id" value="<?php if($edit){ echo $data[0]->id; } ?>">
				<div class="row">

					<div class="col-12">
						<div class="form-group mb-3">
							<input style="outline: none!important; box-shadow: unset!important;" type="text" class="form-control border-top-0 border-left-0 border-right-0 rounded-0 shadow-none" id="nome" placeholder="Nome" name="city" value="<?php if($edit){ echo $data[0]->city; } ?>">
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
						<label>Preço</label>
						<div class="input-group mb-3">
						<input type="number" class="form-control" id="preco" name="preco" value="<?php if($edit): echo $data[0]->preco; else: echo 0; endif; ?>">
						</div>
					</div>

					<div class="col-12 col-md-6 mb-3">
						<label>Limite</label>
						<div class="input-group">
						<input minlength="0" type="number" class="form-control" id="limite" name="limite" value="<?php if($edit): echo $data[0]->limite; else: echo 0; endif; ?>">
						</div>
						<small>Limite diário de pessoas por pacote.</small>
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

					<div class="col-12 col-md-6 mb-3">
						<div>
							<label>Horários</label>
							<div class="input-group">
								<input type="text" class="form-control" id="timepicker" name="horaAdd">
							</div>
						</div>
						<div class="horas">
							<?php
								if(isset($data[0]->hora)){
									$horas = explode(',', $data[0]->hora);
									$key = array_search('', $horas);
									if($key!==false){
										unset($horas[$key]);
									}
									foreach ($horas as $key => $value) {
										?>
											<div time="<?= $value; ?>"><?= $value; ?> <i class="remove-hora las la-times"></i></div>
										<?php
									}
								}
							?>
							<!--<div>10:00 <i class="remove-hora las la-times"></i></div>-->
							<input id="horas" name="hora" type="hidden" value="">
						</div>
					</div>

					<!-- Dias funcionais -->
					<div class="row m-0 w-100">
						<input class="w-100" type="hidden" name="funcionamento" id="funcionamento" 
						value="<?= $edit ? $data[0]->funcionamento : ''; ?>">
					</div>

					<div class="row m-0 justify-content-center w-100">
						<div class="col-12 col-sm-6 col-md-6">
							<p class="text-center lead"><b>Dias de funcionamento</b></p>
							<div class="mb-3">
								<?php calendario(0, isset($data[0]->funcionamento) ? $data[0]->funcionamento : '', 'static'); ?>
							</div>
						</div>
					</div>

				</div>

				<input type="hidden" name="descricao" id="descricao">
				<?php
					if (isset($_GET['edit'])){
						?><input type="hidden" name="edit_city"><?php
					}else{
						?><input type="hidden" name="add_city"><?php
					}
				?>
				<div id="city-text"><?php if($edit){ echo $data[0]->descricao; } ?></div>

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
<div  data-backdrop="static" data-keyboard="false" class="modal fade" id="progress" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="las la-upload"></i></h5>
      </div>
	  
	  <div class="modal-body">
	  	<div class="progress mb-2">
			<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
		<p><small>Estamos crianda a sua routa evite atualizar a paginar ou efetuar qualquer alteração.</small></p>
	  </div>

    </div>
  </div>
</div>

<script src="assets/ckeditor4/ckeditor.js"></script>
<script src="assets/js/turismo/new.js"></script>
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
		document.querySelector('#timepicker').value = '';
		document.querySelector('.horas').insertAdjacentHTML('afterbegin', '<div time='+e.time+'>'+e.time+' <i class="remove-hora las la-times"></i></div>');
	});
	$('body').delegate('.horas > div i', 'click', function(){
		var element = $(this).parent();
		element.remove();
	});

});
</script>