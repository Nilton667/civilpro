<style type="text/css">
	.gallery-image{
		cursor: zoom-in;
		width: 100%;
		object-fit: cover;
		object-position: center;
		border-radius: 5px;
		border: #c9c9c9 1px solid;
	}
</style>
<div class="header-container-wallpaper d-flex align-items-center" 
style="background: url(publico/img/galeria/img.jpg); background-size: cover; background-position: center;">
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="red-text display-4"><b>Galeria</b></h1>
            </div>
        </div>
    </div>
</div>

<br>

<div class="container-fluid">
	<div class="row">
		<?php
			if(isset($get_current_page[1]) && !empty($get_current_page[1])){
				?>
				<div class="col-12">
					<a href="./galeria" style="color: #fff!important;" class="btn btn-primary"><b><i class="las la-chevron-left"></i> Voltar para a seleção</b></a>
					<br>
					<br>
					<h4 class="mb-2" style="color: #666;"><b><?= $get_current_page[1]; ?></b></h4>
					<hr>
				</div>
				<div class="col-12">
					<?php

					try{
		
						$select_galeria   = DB\Mysql::select('SELECT * from galeria WHERE pasta = :pasta ORDER BY id DESC', ['pasta' => $get_current_page[1]]);

						if(is_array($select_galeria)){

							$imagens      = DB\Mysql::select('SELECT * FROM galeria_imagem WHERE id_galeria = :id', ['id' => $select_galeria[0]['id']]); 

							if(is_array($imagens)){

								$visible_location = [];
								foreach ($imagens as $key => $value) {
									$visible_location[$key] = $value['localizacao_id'];
								}

								$localizacoes = DB\Mysql::select("SELECT * FROM galeria_localizacao WHERE id IN(".implode(',', $visible_location).")");

								foreach ($localizacoes as $key => $value) {

									?>
									<div class="row p-0">
										<div class="col-12">
											<br>
											<h4 class="mb-2" style="color: #666;"><b><?= $value['nome']; ?></b></h4>
										</div>
							
										<?php
											$listar = DB\Mysql::select('SELECT * FROM galeria_imagem WHERE localizacao_id = :id AND id_galeria = :id_galeria', 
											[
												'id' => $value['id'],
												'id_galeria' => $select_galeria[0]['id']
											]
											);
											
											if(is_array($listar)){
												foreach ($listar as $ref => $data) {
													if(is_file('publico/img/galeria/'.$data['imagem'])):
														?>
														<div class="col-12 col-sm-2 col-md-2 p-1">
															<a class="image-popup" href="<?php echo "./publico/img/galeria/".$data['imagem']; ?>">
																<img style="max-width: 100%; height: 250px;" 
																class="gallery-image" 
																src="<?php echo "./publico/img/galeria/".$data['imagem']; ?>">
															</a>
														</div>									
														<?php
													endif;
												}
											}
										?>                                          
										<br><br>
											
									</div>
									<?php
								}

							}else{
								echo '<p class="text-center lead">Nenhuma imagem registada!</p>';
							}

						}else{
							echo '<p class="text-center lead">Nenhum resultado encontrado!</p>';
						}
		
					}catch(Exception $error){
						echo '<p class="text-center lead">'.$error.'!</p>';
					}
					?>
				</div>
			<?php
			}else{
				$topic = DB\Mysql::select(
					"SELECT * FROM galeria",
					[]
				);
				if(is_array($topic)):
				foreach ($topic as $key => $value) {
					?>
						<div class="col-12 col-sm-6 col-md-3">
							<div class="card topic-card mb-4 pointer" onclick="location.href='<?= './galeria/'.$value['pasta']; ?>'">
								
								<img 
								src="publico/img/galeria.png" style="object-fit: cover; height: 120px; object-position: top;" 
								class="card-img-top topic-card-img" alt="Imagem de tópico">
	
								<div class="card-body">
									<div class="d-flex w-100 align-items-center mb-2">
										<img class="mr-3" src="favicon.png" style="width: 30px;"> 
										<h4 class="card-title m-0"><?= $value['pasta']; ?></h4>                        
									</div>
									
									<small class="d-block">Criado por: Administrador</small>
	
									<p class="card-text mt-2 mb-2 display-block small">
										Clique para visualizar o album completo!
									</p>
									<br>
	
									<div class="pt-2 pb-3 w-100 d-flex justify-content-between" 
									style="position: absolute; bottom: 0; left: 0; right: 0; padding-left: 1.25rem; padding-right:  1.25rem;">
										<div>
											<i class="las la-image mr-2" style="font-size: 1.2em;"></i>
											<small class="red-text">
												<?php 
													$_img_count = DB\Mysql::select(
														"SELECT id FROM galeria_imagem WHERE id_galeria = :id",
														[
															'id' => $value['id']
														]
													);
													if(is_array($_img_count)):
														eco(number_format(count($_img_count), 0, ',', '.'));
													else:
														eco(0);
													endif; 
												?> Imagem(s)
											</small>                        
										</div>
										<div style="text-align: right;">
											<i class="las la-info-circle" style="font-size: 1.2em;" data-toggle="tooltip" title="Clique para visualizar todas as imagens de <?= $value['pasta'] ?>"></i>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php
				}
				endif;
			}
		?>
	</div>
</div>
<br>