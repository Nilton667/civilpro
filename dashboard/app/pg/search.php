<?php
    $busca = isset($_GET['s']) ? trim(strip_tags($_GET['s'])) : 'n/a';
?>
<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">
            Resultados para: <?php echo $busca; ?>
        </h3>
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
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                	</div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">                        

                        <div class="container">
							<div class="row justify-content-center">
								<div class="col-12 mt-2 p-0">
                                    <p class="lead text-center">Nenhum resultado encontrado!</p>
								</div>
							</div>
						</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>