<script src="assets/js/jqueryForm.js"></script>

<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0"><?php if(isset($_GET['edit'])){ echo "Editar perfil"; }else{ echo "Perfil"; } ?></h3>
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
                                <center>
                                    <a style="cursor: default!important;" 
                                    <?php if(isset($_GET['edit'])){ echo 'data-toggle="modal" data-target="#photo-modal"'; } ?>>
                                        <img id="userData-image"
                                        class="perfil-image rounded-circle <?php if(isset($_GET['edit'])): echo "pointer"; endif; ?>"
                                        src="assets/img/perfil/<?php echo isset($userData->imagem) && is_file('assets/img/perfil/'.$userData->imagem) ? $userData->imagem : 'user.png'; ?>"
                                        <?php 
                                            if(isset($_GET['edit'])):
                                                echo 'data-toggle="tooltip" data-placement="top" title="Toque para alterar"'; 
                                            endif;
                                        ?>
                                    >
                                    </a>
                                    <p class="lead mt-1"><b><?php echo $userData->nome.' '.$userData->sobrenome; ?></b></p>
                                </center>
                                <hr>
                                <div class="row">
                                    <?php
                                        if (isset($_GET['edit'])) {
                                            ?>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="perfil-edit-nome">Nome</label>
                                                        <input type="text" class="form-control" id="perfil-edit-nome" value="<?php echo $userData->nome; ?>">
                                                        <small class="form-text text-muted">O seu nome deve conter no máximo 9 caracteres sem espaços.</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="perfil-edit-sobrenome">Sobrenome</label>
                                                        <input type="text" class="form-control" id="perfil-edit-sobrenome" value="<?php echo $userData->sobrenome; ?>">
                                                        <small class="form-text text-muted">O seu Sobrenome deve conter no máximo 9 caracteres sem espaços.</small>
                                                    </div>
                                                </div>                
                                            <?php
                                        }
                                    ?>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="perfil-edit-email">Email</label>
                                            <input readonly type="email" class="form-control" id="perfil-edit-email" value="<?php echo $userData->email; ?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="perfil-edit-identificacao">Identificacao</label>
                                            <input <?php if(!isset($_GET['edit'])){ echo "readonly"; } ?> type="text" class="form-control" id="perfil-edit-identificacao" value="<?php echo $userData->identificacao; ?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="perfil-edit-nacionalidade">Nacionalidade</label>
                                            <select id="perfil-edit-nacionalidade" <?php if(!isset($_GET['edit'])){ echo "disabled"; } ?> id="nacionalidade" class="custom-select">
                                                <option selected value="">-- Nacionalidade --</option>
                                                <option value="Angola" <?php if($userData->nacionalidade == 'Angola'){ echo 'selected'; } ?>>Angola</option>
                                                <option value="Brasil" <?php if($userData->nacionalidade == 'Brasil'){ echo 'selected'; } ?>>Brasil</option>
                                                <option value="Moçambique" <?php if($userData->nacionalidade == 'Moçambique'){ echo 'selected'; } ?>>Moçambique</option>
                                                <option value="Portugal" <?php if($userData->nacionalidade == 'Portugal'){ echo 'selected'; } ?>>Portugal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="perfil-edit-morada">Morada</label>
                                            <input <?php if(!isset($_GET['edit'])){ echo "readonly"; } ?> type="text" class="form-control" id="perfil-edit-morada" value="<?php echo $userData->morada; ?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="perfil-edit-genero">Gênero</label>
                                            <select id="perfil-edit-genero" <?php if(!isset($_GET['edit'])){ echo "disabled"; } ?> id="genero" class="custom-select">
                                                <option value="">-- Gênero --</option>
                                                <option value="M" <?php if($userData->genero == 'M'){ echo 'selected'; } ?> >Masculino</option>
                                                <option value="F" <?php if($userData->genero == 'F'){ echo 'selected'; } ?> >Feminino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="perfil-edit-telemovel">Telemóvel</label>
                                            <input <?php if(!isset($_GET['edit'])){ echo "readonly"; } ?> type="number" class="form-control" id="perfil-edit-telemovel" value="<?php echo $userData->telemovel; ?>">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <a data-toggle="modal" data-target="#update-senha" href="javascript:void('senha')">Altera senha?</a>
                                    </div>
                                    <div class="col-12 mt-1">
                                        <div class="text-right">
                                            <?php 
                                                if(!isset($_GET['edit'])): 
                                                    ?><button type="button" class="btn btn-danger" onclick="location.href='./?perfil&edit'"><i class="las la-pencil-alt"></i> Editar perfil</button><?php
                                                else:
                                                    ?>
                                                        <button type="button" class="btn btn-secondary" onclick="location.href='./?perfil'"><i class="las la-eye"></i> Visualizar</button>
                                                        <button type="button" class="btn btn-primary" id="perfil-save"><i class="las la-save"></i> Salvar</button>
                                                    <?php
                                                endif; 
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
    </div>
</div>

<!-- Modal -->
<!-- Altera senha -->
<div class="modal fade" id="update-senha" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Altera senha</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="perfil-edit-telemovel">Senha atual</label>
                    <input type="password" class="form-control" id="update-password-atual" placeholder="******">
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="perfil-edit-telemovel">Nova senha</label>
                    <input type="password" class="form-control" id="update-password-new" placeholder="******">
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="perfil-edit-telemovel">Confirme a senha</label>
                    <input type="password" class="form-control" id="update-password-confirm" placeholder="******">
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="update-confirm">Alterar</button>
      </div>
    </div>
  </div>
</div>

<!-- Altera foto de perfil -->
<div class="modal fade" id="photo-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" enctype="multipart/form-data" id="photo_form">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Olá <?php echo $userData->nome; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="lead">Altera foto de perfil</p>
                <input type="hidden" name="image" value="true">
                <input type="hidden" name="header" value="application/json">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" onchange="fileName(this.value)" id="input-file" name="img[]">
                    <label for="input-file" id="input-file-label" class="custom-file-label" for="input-file">Escolher imagem</label>
                </div>
                <small>Suporte: png, jpeg.</small>
                <div class="progress mt-2" id="upload-image-progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" id="upload">Alterar</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script src="assets/js/perfil.js"></script>