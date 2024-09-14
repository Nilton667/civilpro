<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">
            <?php 
                if(isset($_GET['edit'])): 
                    echo 'Editar cidade/província';

                elseif(isset($_GET['view'])):
                    //null

                elseif(isset($_GET['new'])):
                    echo 'Nova cidade/província';

                else: 
                    echo 'Cidades/Províncias'; 
                endif; 
            ?>
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
                            <?php 
                                if(isset($_GET['edit']) || isset($_GET['view']) || isset($_GET['new'])):
                                    ?>
                                        <li>
                                            <a href="./?city" style="font-size: 1.4em;" class="mr-2">
                                                <i class="las la-bus"></i>
                                            </a>
                                        </li>
                                    <?php
                                else:
                                    ?>
                                    <li>
                                        <a href="./?city&new" class="mr-2">
                                            <i class="ft-plus"></i>
                                        </a>
                                    </li>
                                    <li><a id="modal-delete" class="mr-2"><i class="ft-trash"></i></a></li>
                                    <?php
                                endif;
                            ?>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                	</div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">                        

                        <div class="container">
                            <?php
                                if (isset($_GET['new']) || isset($_GET['edit'])):
                                    include_once 'app/pg/turismo/new.php';

                                elseif(isset($_GET['view'])):
                                    include_once 'app/pg/turismo/view.php';

                                else:
                                    include_once 'app/pg/turismo/city.php';

                                endif;
                            ?>
						</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>