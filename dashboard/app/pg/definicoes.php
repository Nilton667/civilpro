<?php
    $busca = isset($_GET['s']) ? trim(strip_tags($_GET['s'])) : 'n/a';
?>
<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Definições</h3>
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


                    <div class="container p-0">
                            <div class="limited-area">

                                <div>
                                    <p class="h2">Preferências do sistema</p>
                                    <hr>
                                    <p class="lead text-primary" style="font-weight: 500;">Geral</p>
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="system-nome">Nome da organização</label>
                                                <input type="text" class="form-control" id="system-nome" placeholder="Ex: Mulemba" value="<?= isset($getSystem['nome']) ? $getSystem['nome'] : ''; ?>">
                                                <small class="form-text text-muted">Evite alterações frequentes.</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="system-font-size">Tamanho do texto</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="system-font"><i class="las la-font"></i></label>
                                                    </div>
                                                    <select class="custom-select" id="system-font">
                                                        <option value="0" <?php if(isset($getSystem['font']) && $getSystem['font'] == 0): echo 'selected'; endif; ?>>Normal</option>
                                                        <option value="1" <?php if(isset($getSystem['font']) && $getSystem['font'] == 1): echo 'selected'; endif; ?>>Médio</option>
                                                        <option value="2" <?php if(isset($getSystem['font']) && $getSystem['font'] == 2): echo 'selected'; endif; ?>>Grande</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="system-lang">Idioma</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="system-lang"><i class="las la-language"></i></label>
                                                    </div>
                                                    <select class="custom-select" id="system-lang">
                                                        <option value="pt-pt" <?php if(isset($getSystem['lang']) && $getSystem['lang'] == 'pt-pt'): echo 'selected'; endif; ?>>Portugês</option>
                                                        <option value="en" disabled <?php if(isset($getSystem['lang']) && $getSystem['lang'] == 'en'): echo 'selected'; endif; ?>>Englês</option>
                                                        <option value="fr" disabled <?php if(isset($getSystem['lang']) && $getSystem['lang'] == 'fr'): echo 'selected'; endif; ?>>Francês</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="system-theme">Tema</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="system-theme"><i class="las la-leaf"></i></label>
                                                    </div>
                                                    <select class="custom-select" id="system-theme">
                                                        <option value="light" <?php if(isset($getSystem['theme']) && $getSystem['theme'] == 'light'): echo 'selected'; endif; ?>>Light</option>
                                                        <option value="dark" <?php if(isset($getSystem['theme']) && $getSystem['theme'] == 'dark'): echo 'selected'; endif; ?>>Dark</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="system-image">Imagem</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="system-image"><i class="las la-image"></i></label>
                                                    </div>
                                                    <select class="custom-select" id="system-image">
                                                        <option value="01.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '01.jpg'): echo 'selected'; endif; ?>>01</option>
                                                        <option value="02.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '02.jpg'): echo 'selected'; endif; ?>>02</option>
                                                        <option value="03.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '03.jpg'): echo 'selected'; endif; ?>>03</option>
                                                        <option value="04.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '04.jpg'): echo 'selected'; endif; ?>>04</option>
                                                        <option value="05.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '05.jpg'): echo 'selected'; endif; ?>>05</option>
                                                        <option value="06.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '06.jpg'): echo 'selected'; endif; ?>>06</option>
                                                        <option value="07.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '07.jpg'): echo 'selected'; endif; ?>>07</option>
                                                        <option value="08.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '08.jpg'): echo 'selected'; endif; ?>>08</option>
                                                        <option value="09.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '09.jpg'): echo 'selected'; endif; ?>>09</option>
                                                        <option value="10.jpg" <?php if(isset($getSystem['image']) && $getSystem['image'] == '10.jpg'): echo 'selected'; endif; ?>>10</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="lead text-primary" style="font-weight: 500;">Segurança</p>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="system-http" <?php if($getSystem['http'] == 1): echo 'checked'; endif; ?>>
                                                <label class="custom-control-label" for="system-http">Forçar conexão segura</label>
                                            </div>
                                            <hr class="mb-0">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 p-1">
                                            <div class="text-right"> 
                                                <button type="button" class="p-1 btn btn-danger" data-toggle="modal" data-target="#modal-reset">Resetar</button>            
                                                <button type="button" class="p-1 btn btn-primary" id="systemSave">Salvar</button>  
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <p class="h2">Definições de email</p>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="mail-hostName">Host</label>
                                                <input type="text" class="form-control" id="mail-hostName" placeholder="Ex: mail.seudomínio.com" value="<?= isset($getSystemEmail['hostName']) ? $getSystemEmail['hostName'] : ''; ?>">
                                                <small class="form-text text-muted">Seu domínio de email.</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="mail-hostEmail">Username</label>
                                                <input type="email" class="form-control" id="mail-hostEmail" placeholder="Ex: geral@seudomínio.com" value="<?= isset($getSystemEmail['hostEmail']) ? $getSystemEmail['hostEmail'] : ''; ?>">
                                                <small class="form-text text-muted">Nome de usuário ou endereço de email.</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="mail-hostPort">Port</label>
                                                <input type="number" class="form-control" id="mail-hostPort" placeholder="Ex: 8080" value="<?= isset($getSystemEmail['hostPort']) ? $getSystemEmail['hostPort'] : ''; ?>">
                                                <small class="form-text text-muted">Sua porta de comunicação.</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="mail-hostPassword">Password</label>
                                                <input type="password" class="form-control" id="mail-hostPassword" aria-describedby="passwordHelp" placeholder="******" value="<?= isset($getSystemEmail['hostPassword']) ? $getSystemEmail['hostPassword'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <p class="lead text-primary" style="font-weight: 500;">Emissor</p>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="mail-emissorName">Nome</label>
                                                <input type="text" class="form-control" id="mail-emissorName" placeholder="Seu nome / organização" value="<?= isset($getSystemEmail['emissorName']) ? $getSystemEmail['emissorName'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="mail-emissorEmail">Email</label>
                                                <input type="email" class="form-control" id="mail-emissorEmail" placeholder="Ex: name@seudomínio.com" value="<?= isset($getSystemEmail['emissorEmail']) ? $getSystemEmail['emissorEmail'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <p class="lead text-primary" style="font-weight: 500;">Receptor</p>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="mail-receptorName">Nome</label>
                                                <input type="text" class="form-control" id="mail-receptorName" placeholder="Seu nome / organização" value="<?= isset($getSystemEmail['receptorName']) ? $getSystemEmail['receptorName'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="mail-receptorEmail">Email</label>
                                                <input type="email" class="form-control" id="mail-receptorEmail" placeholder="Ex: name@seudomínio.com" value="<?= isset($getSystemEmail['receptorEmail']) ? $getSystemEmail['receptorEmail'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-12 pt-1">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                <span class="input-group-text">Status</span>
                                                </div>
                                                <textarea style="min-height: 130px" class="form-control align-middle" aria-label="textarea" id="mail-status-area" readonly=""></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 pt-1">
                                            <div class="text-right">              
                                                <button type="button" class="p-1 btn btn-secondary" id="email-test">Testar</button>
                                                <button type="button" class="p-1 btn btn-danger" data-toggle="modal" data-target="#modal-reset-email">Resetar</button> 
                                                <button type="button" class="p-1 btn btn-primary" id="email-save">Salvar</button>
                                            </div>
                                        </div>
                                    </div>
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
<div class="modal fade" id="modal-reset" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Olá <?php echo $userData->nome.' '.$userData->sobrenome; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="lead">Pretende mesmo resetar as suas preferências do sistema?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button id="systemReset" type="button" class="btn btn-danger">Resetar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-reset-email" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Olá <?php echo $userData->nome.' '.$userData->sobrenome; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="lead">Pretende mesmo resetar as suas definições de email?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button id="emailReset" type="button" class="btn btn-danger">Resetar</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/js/def.js"></script>