<script src="publico/js/jqueryForm.js"></script>
<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Newsletter</h3>
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
                          <li><a data-toggle="modal" data-target="#modal-new-email" class="mr-2"><i class="las la-sms"></i></a></li>
                          <li><a data-toggle="modal" data-target="#modal-new-user" class="mr-2"><i class="ft-plus"></i></a></li>
                          <li><a id="modal-delete" class="mr-2"><i class="ft-trash"></i></a></li>
                          <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                	</div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">                        

                      <div class="container">
                        <div class="row m-0 justify-content-center">
                          <div class="col-12 col-md-5">
                            <form method="GET">
                              <div class="input-group mb-2 card-body-search">
                                <input type="hidden" name="newsletter">
                                <input type="text" class="form-control" name="filtro" value="<?php if(isset($_GET['filtro'])): echo trim($_GET['filtro']); endif; ?>" placeholder="Procure aqui...">
                                <div class="input-group-append">
                                  <button type="submit" class="input-group-text pointer"><i class="las la-search"></i></button>
                                </div>											
                              </div>
                            </form>
                          </div>
                        </div>
                        <div class="row justify-content-center">
                          <div class="col-12 mt-2 p-0">
                          <?php
                            //Definindo a paginação
                            if(isset($_GET['pg']) && is_numeric(trim($_GET['pg'])) && trim($_GET['pg']) > 0):           
                              $pg = trim($_GET['pg']); 
                            else: 
                              $pg = 1; 
                            endif;

                            //Quantidade a mostrar
                            @$quantidade = 30;
                            @$inicio     = ($pg * $quantidade) - $quantidade;
                            @$limit      = ' LIMIT :inicio, :quantidade';

                            try{

                            if (isset($_GET['filtro']) && $_GET['filtro'] !=''):
                              $busca  = filter_var(trim(strip_tags($_GET['filtro'])), FILTER_SANITIZE_STRING);
                              $select = "SELECT * from newsletter WHERE email LIKE '%$busca%'";
                              $select.= " OR registo LIKE '%$busca%' ORDER BY id DESC";
                            else:
                              $select = 'SELECT * from newsletter ORDER BY id DESC';
                            endif;

                            $result = $conexao->getCon(1)->prepare($select.$limit);
                            $result->bindParam(':inicio', $inicio, PDO::PARAM_INT);
                            $result->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
                            $result ->execute();
                            $contar = $result->rowCount();
                            if($contar > 0){
                              $table        = '<div class="table-responsive">';
                              $table       .= '<table class="table table-striped table-hover">';
                              $table       .= '<thead>';
                              $table       .= '<tr>';
                              
                              $tableCheck   = trim(
                              '<div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="newsletter-id">
                                <label class="custom-control-label" for="newsletter-id"></label>
                              </div>'
                              );

                              $table.= trim('
                                  <th scope="col">#</th>
                                  <th scope="col">Email</th>
                                  <th scope="col">Registo</th>
                                  <th scope="col">'.$tableCheck.'</th>
                              ');

                              $table       .= '</tr>';
                              $table       .= '</thead>';
                              $table       .= '<tbody>';
                              $tableClose   = '<tbody></table></div>';
                              echo $table;
                              while($mostra = $result->FETCH(PDO::FETCH_OBJ)){
                              ?>
                              <tr>
                                <th scope="row"><?php echo $mostra->id; ?></th>
                                <td id="newsletter-nome-<?php echo $mostra->id; ?>">
                                    <?php echo $mostra->email;  ?>
                                </td>
                                <td><?php echo $mostra->registo;  ?></td>
                                <th>
                                  <div class="d-flex w-100 align-items-center">
                                    <div class="custom-control custom-checkbox d-inline-block">
                                      <input 
                                      type="checkbox" 
                                      class="custom-control-input"
                                      newsletter-select="<?php echo $mostra->id; ?>"
                                      id="newsletter-<?php echo $mostra->id; ?>">
                                      
                                      <label 
                                      class="custom-control-label" 
                                      for="newsletter-<?php echo $mostra->id; ?>"></label>
                                    </div>
                                                                            		
                                  </div>
                                </th>
                              </tr>
                              <?php
                              }
                              
                              echo $tableClose;

                              //Paginação
                              $paginacao = new Paginacao();
                              $paginacao->queryString = 'newsletter';
                              $paginacao->select      = $select;
                              $paginacao->quantidade  = $quantidade;
                              $paginacao->pg          = $pg;
                              $paginacao->getPaginacao();

                            }else{
                              if (isset($pg) && $pg > 1):
                                echo '<script type="text/javascript">location.href = "./?newsletter";</script>';
                                exit(); 
                              endif;
                              echo '<p class="text-center lead">Nenhum resultado encontrado!</p>';
                            }

                            }catch(Exception $error){
                              echo '<p class="text-center lead">'.$error.'!</p>';
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

<!-- Modal -->
<div class="modal fade" id="modal-new-email" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 700px!important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nova mensagem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-6">
    				<div class="form-group">
    					<input style="outline: none!important; box-shadow: unset!important;" type="text" class="form-control border-top-0 border-left-0 border-right-0 rounded-0 shadow-none" id="mail-emissor" placeholder="De:" value="<?= isset($getSystem['nome']) ? $getSystem['nome'] : ''; ?>">
    				</div>      			
      		</div>
      		<div class="col-6">
    				<div class="form-group">
    					<input style="outline: none!important; box-shadow: unset!important;" type="text" class="form-control border-top-0 border-left-0 border-right-0 rounded-0 shadow-none" id="mail-assunto" placeholder="Assunto: ">
    				</div>      			
      		</div>
      	</div>

        <textarea id="message-text"></textarea>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="newsletter-send-email">Enviar</button>
      </div>
    </div>
  </div>
</div>

<!-- Registar email -->
<div class="modal fade" id="modal-new-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Subscrever usuário</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="subscrever-email">Endereço de email</label>
          <input type="email" class="form-control" id="subscrever-email" aria-describedby="emailHelp" placeholder="Ex: geral@mulemba.com">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="add-user">Subscrever</button>
      </div>
    </div>
  </div>
</div>

<!-- Remover email -->
<div class="modal fade" id="modal-remove-item" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
  
		<div class="modal-body text-center lead" id="remove-content"></div>
  
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
		  <button type="button" class="btn btn-danger" id="remove-item">Remover</button>
		</div>
	  </div>
	</div>
</div>

<script src="assets/ckeditor/ckeditor.js"></script>
<script src="assets/ckeditor/translations/pt.js"></script>
<script src="assets/js/newsletter/newsletter.js"></script>