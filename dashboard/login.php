<?php
    include_once 'app/control.php';
    $control = new Control('login');
    $control->permission();
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
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
                                <p class="m-0 p-1 text-secondary">Faça login para continuar</p>
                            </center>
                        </div>

                        <div class="form-group">
                            <label for="email">Endereço de email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <i class="input-group-text las la-envelope"></i>
                                </div>
                                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Seu email">
                            </div>
                            <small id="emailHelp" class="form-text text-muted">Verifique se não tem alguem a observa-lo.</small>
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

                        <a href="./recover">Esqueceu a senha?</a>
                        <?= $getDef['modulo']['signUp'] ? '<p><a href="./cadastro">Criar conta</a></p>' : '' ?>
                        <div class="d-flex justify-content-end">
                            <button style="padding: 12px;" type="button" class="btn btn-primary" id="entrar">Entrar</button>  
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
    <script src="assets/js/login.js"></script>
    <script src="assets/js/init.js"></script>
</body>
</html>