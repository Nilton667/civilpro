<div class="content-wrapper-before"></div>
<div class="content-header row"></div>
<div class="content-body">

    <!-- Chart -->
    <div class="row match-height">
        <div class="col-12">
            <div class="">
                <div id="gradient-line-chart1" class="height-250 GradientlineShadow1"></div>
            </div>
        </div>
    </div>
    <!-- /Chart -->

    <!-- eCommerce statistic -->
    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="danger position-relative card pull-up ecom-card-1 bg-white" style="border-left: 5px solid;">
                <div class="card-content ecom-card2 height-180">
                    <h5 class="text-muted danger position-absolute p-1 font-large-1" style="right: 0;">
                        <?php 
                            /*Contador de visitas*/ 
                            $contador_de_visitas = ( new Visitas() )->checkUser(false, false);
                            eco($contador_de_visitas); 
                        ?> 
                    </h5>
                    <div>
                        <i class="ft-user danger font-large-2 float-left p-1"></i>
                    </div>
                    <div class="display-4 d-flex justify-content-end align-items-end p-1" 
                    style="position: absolute; top: 0; left: 0; bottom: 0; right: 0;">
                          <h5 class="font-large-1 danger">Visitas</h5>                         
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card pull-up ecom-card-1 bg-white">
                <div class="card-content ecom-card2 height-180">
                    <h5 class="text-muted info position-absolute p-1">Actividade</h5>
                    <div>
                        <i class="ft-activity info font-large-1 float-right p-1"></i>
                    </div>
                    <div class="progress-stats-container ct-golden-section height-75 position-relative pt-3">
                        <div id="progress-stats-bar-chart1"></div>
                        <div id="progress-stats-line-chart1" class="progress-stats-shadow"></div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            if($getDef['modulo']['loja']){
                ?>
                <div class="col-xl-4 col-lg-12">
                    <div class="card pull-up ecom-card-1 bg-white">
                        <div class="card-content ecom-card2 height-180">
                            <h5 class="text-muted warning position-absolute p-1">Vendas</h5>
                            <div>
                                <i class="ft-shopping-cart warning font-large-1 float-right p-1"></i>
                            </div>
                            <div class="progress-stats-container ct-golden-section height-75 position-relative pt-3">
                                <div id="progress-stats-bar-chart2"></div>
                                <div id="progress-stats-line-chart2" class="progress-stats-shadow"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }else{
                ?>
                <div class="col-xl-4 col-lg-12">
                    <div class="card pull-up ecom-card-1 bg-white">
                        <div class="card-content ecom-card2 height-180">
                            <h5 class="text-muted warning position-absolute p-1">Tráfego</h5>
                            <div>
                                <i class="las la-thermometer-half warning font-large-1 float-right p-1"></i>
                            </div>
                            <div class="progress-stats-container ct-golden-section height-75 position-relative pt-3">
                                <div id="progress-stats-bar-chart"></div>
                                <div id="progress-stats-line-chart" class="progress-stats-shadow"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        ?>

    </div>
    <!--/ eCommerce statistic -->

    <!-- Conteudo a adiconar -->
    <div class="row match-height">
    <div class="col-12"></div>
    </div>

</div>

<script src="theme-assets/vendors/js/charts/chartist.min.js" type="text/javascript"></script>

<script>
    var chartist_contador = <?= json_encode(DB\Mysql::select("SELECT * FROM visitas")); ?>;
    var chartist_visitas  = parseInt("<?= count(DB\Mysql::select("SELECT id FROM visitas")); ?>");
    var chartist_vendas   = <?= json_encode(DB\Mysql::select("SELECT * FROM fatura")); ?>;
</script>
<script src="theme-assets/js/scripts/pages/dashboard-lite.js" type="text/javascript"></script>