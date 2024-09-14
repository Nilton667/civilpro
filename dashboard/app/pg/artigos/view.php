<div class="row justify-content-center">
	<div class="col-12 mt-2 p-0">
	<?php
		$data = new Util();
		$data = $data->getArtigo();
		$data = json_decode($data);

		$post = isset($data[0]->id) ? true : false;

		if ($post) {
			?>
				<div class="m-auto" style="width: 900px; max-width: 100%;">
					<div class="text-right">
						<button class="btn btn-danger" data-toggle="modal" data-target="#modal-new-delete">
							<i class="las la-trash"></i>
						</button>
						<button class="btn btn-primary" onclick="location.href='./?artigos&edit=<?php echo $data[0]->id; ?>'">
							<i class="las la-edit"></i>
						</button>
					</div>
					<div class="row mt-2">
						<div class="col-12 col-md-6">
							<img class="img-fluid w-100" src="../publico/img/artigos/<?php echo isset($data[0]->imagem) && is_file('../publico/img/artigos/'.$data[0]->imagem) ? $data[0]->imagem : 'default.png'; ?>">
						</div>
						<div class="col-12 col-md-6">
							<hr>
							<h2><?php echo $data[0]->nome; ?></h2>
							<hr>
							<i><h5>Publicado aos: <small><?php echo $data[0]->registo; ?></small></h5></i>
							<hr>
							<div class="lead"><?php echo $data[0]->descricao; ?></div>
							<ul class="list-group">
								<li class="list-group-item"><span style="font-weight: 500;">Quantidade:</span> <?php echo $data[0]->quantidade; ?></li>
								<li class="list-group-item"><span style="font-weight: 500;">Preço:</span> <?php echo get_moeda($data[0]->preco); ?></li>
							</ul>
						</div>
					</div>
				</div>

				<!-- Eliminar artigo -->
				<div class="modal fade" id="modal-new-delete" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>

						<div class="modal-body text-center lead" id="modal-new-delete-content">
							Pretende mesmo eliminar este artigo?
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
							<button type="button" class="btn btn-danger" id="remove-artigo" artigo-id="<?php echo $data[0]->id; ?>">Eliminar</button>
						</div>
						</div>
					</div>
				</div>

				<script src="./assets/js/artigo/view.js"></script>
			<?php
		}else{
			echo '<p class="text-center lead">O seu artigo não foi encontrado neste servidor!</p>';
		}
	?>
	</div>
</div>