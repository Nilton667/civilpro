<?php
    include_once 'app/control.php';
    $control = new Control('login');
    $control->permission();

    if($getDef['modulo']['signUp'] == false):
        header('location: ./');
        exit();
    endif;
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cadastro</title>
    <link rel="shortcut icon" type="image/x-icon" href="theme-assets/images/ico/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="theme-assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/login.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/jquery.toast.css"/>
    <link href="assets/line-awesome/1.3.0/css/line-awesome.min.css" rel="stylesheet">
    <script src="theme-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
</head>
<body>

	<div class="preload">
		<div class="w-100">
			<center>
				<img src="./assets/img/preloader.gif">
			</center>			
		</div>
    </div>
    
    <div class="onload">

        <div class="d-flex justify-content-center align-items-center login-area">
            <div>
                <div class="login-card">
                    <div class="bg-white p-1">
                        <div class="mt-1 mb-1">
                            <center>
                                <img src="assets/img/logo.png" width="100">
                                <p class="m-0 p-1 text-secondary">Criar conta</p>
                            </center>
                        </div>
                        <div class="row m-0">
                            <div class="col-12 col-sm-6 p-0 pr-sm-1">
                                <div class="form-group">
                                    <label for="email">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" aria-describedby="emailHelp" placeholder="Nome">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 p-0 pl-sm-1">
                                <div class="form-group">
                                    <label for="senha">Sobrenome</label>
                                    <input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Sobrenome">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="senha">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text las la-envelope"></i>
                                </div>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Seu email">
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-12 col-sm-6 p-0 pr-sm-1">
                                <div class="form-group">
                                    <label>Nacionalidade</label>
                                    <select id="nacionalidade" class="custom-select">
                                        <option selected value="">-- Nacionalidade --</option>
                                        <option value="Angola">Angola</option>
                                        <option value="Brasil">Brasil</option>
                                        <option value="Moçambique">Moçambique</option>
                                        <option value="Portugal">Portugal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 p-0 pl-sm-1">
                                <div class="form-group">
                                    <label>Gênero</label>
                                    <select id="genero" class="custom-select">
                                        <option selected value="">-- Gênero --</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Feminino</option>>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text las la-key"></i>
                                </div>
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
                            </div>
                        </div>

                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input" id="sessionCashe">
                            <label class="custom-control-label" for="sessionCashe">Manter sessão iníciada</label>
                        </div>

                        <a href="./login">Ja tem uma conta? iniciar sessão</a>
                        <div class="d-flex justify-content-end mt-1">
                            <button type="button" style="padding: 12px;" class="btn btn-primary" id="cadastrar">Cadastrar</button>  
                        </div>
                    </div>                  
                </div>

                <footer class="footer-login d-flex justify-content-end align-items-center p-1">
                    <div>
                        <a href="https://rubro.ao/termos" target="_blank">Termos de serviços & privacidade</a>
                        <small>•</small>
                        <a href="https://rubro.ao/sobre" target="_blank">Sobre</a>
                    </div>
                </footer>

            </div>
        </div>

    </div>

    <script src="assets/js/jquery.toast.js"></script>
    <script src="assets/js/cadastro.js"></script>
    <script src="assets/js/init.js"></script>
</body>
</html>