<div class="row justify-content-center">
	<div class="col-12 mt-2 p-0">
	<?php
		$data = new Util();
		$data = $data->getCity();
		$data = json_decode($data);

		$post = isset($data[0]->id) ? true : false;

		if ($post) {
			?>
				<div class="m-auto" style="width: 750px; max-width: 100%;">
					
					<div class="text-right">
						<button class="btn btn-danger" data-toggle="modal" data-target="#modal-new-delete">
							<i class="las la-trash"></i>
						</button>
						<button class="btn btn-primary" onclick="location.href='./?city&edit=<?php echo $data[0]->id; ?>'">
							<i class="las la-edit"></i>
						</button>
					</div>

					<hr>
						<h2><?php echo $data[0]->city; ?></h2>
					<hr>

					<div class="row">

						<div class="row m-0 w-100">
							<div class="col-12">
								<p class="text-center lead"><b>Dias de funcionamento</b></p>
								<div class="mb-1">
									<?php calendario(0, $data[0]->funcionamento, 'fixed'); ?>
								</div>							
							</div>
						</div>

						<div class="col-12 col-md-6">
							<img class="img-fluid w-100" src="../publico/img/city/<?php echo isset($data[0]->imagem) && is_file('../publico/img/city/'.$data[0]->imagem) ? $data[0]->imagem : 'default.png'; ?>">
						</div>

						<div class="col-12 col-md-6">
							<hr class="mt-0">
							<i><h5>Publicado aos: <small><?php echo $data[0]->registo; ?></small></h5></i>
							<hr>
							<div class="lead">
								<h2>Descrição</h2>
								<?php echo $data[0]->descricao; ?>
							</div>
							<ul class="list-group">
								<li class="list-group-item mt-1">
									<span style="font-weight: 500;">Limite diário de pessoas:</span> <?php echo $data[0]->limite; ?>
								</li>
								<li class="list-group-item">
									<span style="font-weight: 500;">Preço:</span> <?php echo get_moeda($data[0]->preco); ?>
								</li>
							</ul>
						</div>

					</div>

				</div>

				<!-- Eliminar routa -->
				<div class="modal fade" id="modal-new-delete" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>

						<div class="modal-body text-center lead" id="modal-new-delete-content">
							Pretende mesmo eliminar esta cidade/província?
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
							<button type="button" class="btn btn-danger" id="remove-city" city_id="<?php echo $data[0]->id; ?>">Eliminar</button>
						</div>
						</div>
					</div>
				</div>

				<script src="./assets/js/turismo/view.js"></script>

			<?php
		}else{
			echo '<p class="text-center lead">A sua routa não foi encontrada neste servidor!</p>';

		}
	?>
	</div>
</div>