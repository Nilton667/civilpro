<script src="assets/js/jqueryForm.js"></script>

<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Notificações</h3>
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
                          <li>
                            <a data-action="expand"><i class="ft-maximize"></i></a>
                          </li>
                        </ul>
                    </div>
                	</div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">                        

                    <div class="container">

                      <div class="row justify-content-center">
                        <div class="col-12 mt-2 p-0">
                          <?php
                            $select = DB\Mysql::select(
                            "SELECT * FROM agenda WHERE data = :data AND checked = 0",
                              ['data' => date('Y-m-d')]
                            );
                            if(is_array($select)){
                              eco('<div class="accordion" id="accordionNotify">');
                              foreach ($select as $key => $value) {
                                ?>
                                <div class="card m-0" style="border: #c9c9c9 1px solid;">
                                  <div style="background-color: #ebe9ee;" class="card-header p-1" id="heading<?= $value['id'] ?>">
                                    <h5 class="mb-0">
                                      <button class="btn btn-link text-primary" type="button" data-toggle="collapse" data-target="#collapse<?= $value['id'] ?>" aria-expanded="true" aria-controls="collapse<?= $value['id'] ?>">
                                        <?= $value['titulo']; ?>
                                      </button>
                                    </h5>
                                  </div>

                                  <div id="collapse<?= $value['id'] ?>" class="collapse" aria-labelledby="heading<?= $value['id'] ?>" data-parent="#accordionNotify">
                                    <div class="card-body lead">
                                      <?= $value['descricao']; ?>
                                    </div>
                                  </div>
                                </div>
                                <?php
                              }
                              eco('</div>');
                            }else{
                              echo '<p class="text-center lead">Sem notificações para mostrar!</p>';
                            }
                          ?>
                        </div>
                      </div>

                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>