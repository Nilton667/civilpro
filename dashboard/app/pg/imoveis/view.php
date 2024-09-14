<div class="row justify-content-center">
	<div class="col-12 mt-2 p-0">
	<?php
		$data = new Util();
		$data = $data->getDataImoveis();
		$data = json_decode($data);

		$post = isset($data[0]->id) ? true : false;

		if ($post) {
			?>
				<div class="m-auto" style="width: 750px; max-width: 100%;">
					<div class="text-right">
						<button class="btn btn-danger" data-toggle="modal" data-target="#modal-new-delete">
							<i class="las la-trash"></i>
						</button>
						<button class="btn btn-primary" onclick="location.href='./?imoveis&edit=<?php echo $data[0]->id; ?>'">
							<i class="las la-edit"></i>
						</button>
					</div>
					<hr>
					<h2><?php echo $data[0]->titulo; ?></h2>
					<hr>
					<i><h5><?php echo $data[0]->subtitulo; ?></h5></i>
					<hr class="mb-0">
						<img class="img-fluid w-100" src="../publico/img/imoveis/<?php echo isset($data[0]->imagem) && is_file('../publico/img/imoveis/'.$data[0]->imagem) ? $data[0]->imagem : 'default.png'; ?>">
					<hr class="mt-0">
						<i><h5>Publicado aos: <small><?php echo $data[0]->registo; ?></small></h5></i>
					<hr>
					<?php
						if ($data[0]->youtube != '') {
							?>
							<div class="mb-2">
								<iframe id="youtube-frame" width="100%" height="360" src="<?php echo $data[0]->youtube; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
							</div>
							<?php
						}
					?>
					<div class="lead"><?php echo $data[0]->descricao; ?></div>
					
					<?php

						$publicador = DB\Mysql::select(
							'SELECT nome, sobrenome FROM adm WHERE id = :id',
							[
								'id' => $data[0]->id_adm
							]
						);

						if(is_array($publicador)){
							?>
								<div style="font-size: 1.1em;">
									<b>Publicado por:</b> <?= $publicador[0]['nome'].' '.$publicador[0]['sobrenome']; ?>
								</div>
							<?php
						}
						
					?>

				</div>

				<!-- Eliminar imovel -->
				<div class="modal fade" id="modal-new-delete" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>

						<div class="modal-body text-center lead" id="modal-new-delete-content">
							Pretende mesmo eliminar esta postagem?
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
							<button type="button" class="btn btn-danger" id="remove-imoveis" imoveis-id="<?php echo $data[0]->id; ?>">Eliminar</button>
						</div>
						</div>
					</div>
				</div>

				<script src="./assets/js/imoveis/view.js"></script>
			<?php
		}else{
			echo '<p class="text-center lead">O seu imóvel não foi encontrado neste servidor!</p>';

		}
	?>
	</div>
</div>