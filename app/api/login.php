<?php

include_once 'conexao.php';

class Login extends Conexao
{
    private $id, $email, $emailRecover, $senha, $checkbox, $dispositivo, $cashe, $data;

    //Cadatro
    private $nome, $sobrenome, $nacionalidade, $contacto, $morada, $genero;

    function __construct(){

        $this->email        = post('email')
        ? filterEmail(post('email')) 
        : DEFAULT_STRING;

        $this->emailRecover = post('email')
        ? filterEmail(base64_decode(post('email')))
        : DEFAULT_STRING;

        $this->senha        = post('senha')
        ? filterVar(post('senha'))  
        : '';

        $this->checkbox     = is_numeric(post('checkbox'))
        ? filterInt(post('checkbox'), 0)
        : DEFAULT_INT;

        $this->dispositivo  = post('dispositivo') 
        ? filterVar(post('dispositivo'))  
        : DEFAULT_STRING;

        $this->cashe        = is_numeric(post('cashe')) || post('cashe') && post('recoverpass')
        ? filterVar(post('cashe'))  
        : DEFAULT_INT;

        $this->data          = date('d/m/Y');

        //Cadastro
        $this->nome          = post('nome')
        ? filterVar(post('nome'))  
        : DEFAULT_STRING;

        $this->sobrenome     = post('sobrenome') 
        ? filterVar(post('sobrenome'))
        : DEFAULT_STRING;

        $this->nacionalidade = post('nacionalidade')
        ? filterVar(post('nacionalidade'))  
        : DEFAULT_STRING;

        $this->contacto     = post('contacto')
        ? filterVar(post('contacto'))  
        : DEFAULT_STRING;

        $this->morada       = post('morada')
        ? filterVar(post('morada'))  
        : DEFAULT_STRING;

        $this->genero        = post('genero')
        ? filterVar(post('genero'))  
        : 'M';

    }

    function setLogin(){
        
        if(post('recoverpass')):
            $this->email = $this->emailRecover;
        endif;

        try {
            $SELECT = "SELECT id, email, senha, registo FROM usuarios WHERE email = :email LIMIT 1";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $userData = $result->fetchAll();

                if (password_verify($this->senha, $userData[0]['senha'])) {

                    $token = sha1(date('d/m/Y-h-i-s').$userData[0]['id']);
                    $tempo = date('d/m/Y');

                    if ($this->checkbox == 1) {
                        $tempo = date('d/m/Y', time() + (365 * 24 * 60 * 60));   
                    }

                    return $this->setToken(
                        $userData[0]['id'], 
                        $token,
                        $this->dispositivo,
                        $tempo,
                        $this->data
                    );

                }else{
                    return json_encode('Email ou senha errada!');    
                }
            }else{
                return json_encode('Usuário não encontrado!');
            }
        } catch (\Throwable $th) {
            return json_encode($th);
        }
    }

    //Gerando token de acesso
    function setToken($id, $token, $dispositivo, $tempo, $registo){
        try {
            $INSERT = "INSERT INTO acesso (id_adm, token, dispositivo, tempo, registo) VALUES (:id_adm, :token, :dispositivo, :tempo, :registo)";
            $result = Conexao::getCon(1)->prepare($INSERT);
            $result->bindParam(':id_adm', $id, PDO::PARAM_INT);
            $result->bindParam(':token', $token, PDO::PARAM_STR);
            $result->bindParam(':dispositivo', $dispositivo, PDO::PARAM_STR);
            $result->bindParam(':tempo', $tempo, PDO::PARAM_STR);
            $result->bindParam(':registo', $registo, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if($contar > 0){

                $data = array(
                    'id'          => $id, 
                    'token'       => $token, 
                    'dispositivo' => $dispositivo, 
                    'tempo'       => $tempo, 
                    'registo'     => $registo
                );

                if(Components\setSession('maestro', $data)){
                    return json_encode(1);
                }else{
                    return json_encode('Não foi possível iníciar sessão!');
                }

            }else{
                return json_encode('Ocorreu um problema de rede, tente novamente mais tarde!');
            }
        } catch (\Throwable $th) {
            return json_encode($th);
        }
    }

    //Cadastrar usuario
    function setCadastro()
    {
        try {
            $SELECT = "SELECT id FROM usuarios WHERE email = :email";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar >= 1) {
                return json_encode('Esta conta de e-mail ja se encontra registada!');
            }
        } catch (\Throwable $th) {
            return json_encode($th);
        }

        $senha          = password_hash($this->senha, PASSWORD_DEFAULT);
        $cashe          = mt_rand(100000, 900000);
        $authorization  = sha1(uniqid($this->email,true));

        include_once '../envoyer.php';
        $sendEmail = emailSend(
            $this->nome, 
            $this->email, 
            'Confirmação de email', 
            'Olá '.$this->nome.' este é o seu código de confirmação '.$cashe
        );

        if($sendEmail != true):
            return json_encode('Não foi possível enviar o seu email de confirmação!');
        endif;

        try {
            $INSERT = "INSERT INTO usuarios (nome, sobrenome, email, nacionalidade, morada, telemovel, genero, senha, registo, cashe, authorization) VALUES (:nome, :sobrenome, :email, :nacionalidade, :morada, :telemovel, :genero, :senha, :registo, :cashe, :authorization)";
            $result = Conexao::getCon(1)->prepare($INSERT);
            $result->bindParam(':nome', $this->nome, PDO::PARAM_STR);
            $result->bindParam(':sobrenome', $this->sobrenome, PDO::PARAM_STR);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->bindParam(':nacionalidade', $this->nacionalidade, PDO::PARAM_STR);
            $result->bindParam(':morada', $this->morada, PDO::PARAM_STR);
            $result->bindParam(':telemovel', $this->contacto, PDO::PARAM_STR);
            $result->bindParam(':genero', $this->genero, PDO::PARAM_STR);
            $result->bindParam(':senha', $senha, PDO::PARAM_STR);
            $result->bindParam(':registo', $this->data, PDO::PARAM_STR);
            $result->bindParam(':cashe', $cashe, PDO::PARAM_INT);
            $result->bindParam(':authorization', $authorization, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {
                return $this->setLogin();
            }else{
                return json_encode('Serviço indisponível!');
            }
        } catch (\Throwable $th) {
            return json_encode($th);
        }
    }

    //Reenvia email
    function referencingEmail(){

        $this->id = Components\getSession('maestro', 'id', 1,  true);

        try {
            $SELECT = "SELECT id, nome, email FROM usuarios WHERE id = :id";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':id', $this->id, PDO::PARAM_INT);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $userData    = $result->fetchAll();
                $this->nome  = $userData[0]['nome'];
                $this->email = $userData[0]['email'];

                $cashe     = mt_rand(100000, 900000);
                
                include_once '../envoyer.php';
                $sendEmail = emailSend(
                    $this->nome, 
                    $this->email, 
                    'Confirmação de email', 
                    'Olá '.$this->nome.' este é o seu código de confirmação '.$cashe
                );
        
                if($sendEmail != true){
                    return json_encode('Não foi possível enviar o seu email de confirmação!');
                }else{
                    
                    $UPDATE = "UPDATE usuarios SET cashe = :cashe WHERE id = :id";
                    $result = Conexao::getCon(1)->prepare($UPDATE);
                    $result->bindParam(':id', $this->id, PDO::PARAM_INT);
                    $result->bindParam(':cashe', $cashe, PDO::PARAM_INT);
                    $result->execute();
                    $contar = $result->rowCount();
                    if($contar > 0){
                        return json_encode(1);
                    }else{
                        return json_encode('Serviço indisponível!');
                    }

                }

            }else{
                return json_encode('Usuário não encontrado!');
            }

        } catch (\Throwable $th) {
            return json_encode($th);
        }

    }

    //Recover check->email
    function verifyEmail()
    {
        try {
            $SELECT = "SELECT id, nome, sobrenome, email, cashe, checkCashe FROM usuarios WHERE email = :email LIMIT 1";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $userData = $result->fetchAll();
                $cashe    = mt_rand(100000, 999999);

                $userData[0]['user']     = base64_encode($userData[0]['email']);

                if($userData[0]['cashe'] == $userData[0]['checkCashe']){
                    $checkCashe = $cashe;
                }else{
                    $checkCashe = 0;
                }

                include_once '../envoyer.php';
                $sendEmail = emailSend(
                    $userData[0]['nome'], 
                    $userData[0]['email'], 
                    'Recuperação da conta', 
                    'Olá '.$userData[0]['nome'].' este é o seu código de recuperação '.$cashe
                );

                if($sendEmail != true):
                    return json_encode('Não foi possível enviar o seu email de recuperação!');
                endif;

                $UPDATE = "UPDATE usuarios SET cashe = :cashe, checkCashe = :checkCashe WHERE email = :email";
                $result = Conexao::getCon(1)->prepare($UPDATE);
                $result->bindParam(':email', $this->email, PDO::PARAM_STR);
                $result->bindParam(':cashe', $cashe, PDO::PARAM_INT);
                $result->bindParam(':checkCashe', $checkCashe, PDO::PARAM_INT);
                $result->execute();
                $contar = $result->rowCount();
                if($contar > 0){
                    return json_encode($userData);
                }else{
                    return json_encode('Serviço indisponível!');
                }

            }else{
                return json_encode('Usuário não encontrado!');
            }
        } catch (\Throwable $th) {
            return json_encode($th);
        }
    }

    //Verificar email
    function emailCheck(){

        $this->id = Components\getSession('maestro', 'id', 1,  true);

        try {
            $SELECT = "SELECT id FROM usuarios WHERE id = :id AND cashe = :cashe LIMIT 1";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':id', $this->id, PDO::PARAM_INT);
            $result->bindParam(':cashe', $this->cashe, PDO::PARAM_INT);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $UPDATE = "UPDATE usuarios SET checkCashe = :cashe WHERE id = :id AND cashe = :cashe";
                $result = Conexao::getCon(1)->prepare($UPDATE);
                $result->bindParam(':id', $this->id, PDO::PARAM_INT);
                $result->bindParam(':cashe', $this->cashe, PDO::PARAM_INT);
                $result->execute();
                $contar = $result->rowCount();
                if($contar > 0){
                    return json_encode(1);
                }else{
                    return json_encode('Não foi possível verificar a sua conta de email!');
                }
            }else{
                return json_encode('Código inválido!');
            }
        }catch(\Throwable $th){
            return json_encode($th);
        }
    }

    //Recover check->key
    function verifyKey()
    {
        try {
            $SELECT = "SELECT id, nome, sobrenome, email, cashe FROM usuarios WHERE email = :email AND cashe = :cashe LIMIT 1";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->emailRecover, PDO::PARAM_STR);
            $result->bindParam(':cashe', $this->cashe, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $userData = $result->fetchAll();
                $userData[0]['user'] = base64_encode($userData[0]['email']);
                $userData[0]['key']  = base64_encode($userData[0]['cashe']);
                return json_encode($userData);

            }else{
                return json_encode('Código inválido!');
            }
        } catch (\Throwable $th) {
            return json_encode($th);
        }
    }
    
    //Altera senha
    function verifyPass(){
        $cashe = base64_decode($this->cashe);
        $senha = password_hash($this->senha, PASSWORD_DEFAULT);
        try {
            $SELECT = "SELECT id, senha FROM usuarios WHERE email = :email AND cashe = :cashe";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->emailRecover, PDO::PARAM_STR);
            $result->bindParam(':cashe', $cashe, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $userData = $result->fetchAll();
                if(password_verify($this->senha, $userData[0]['senha'])):
                    return json_encode('Não é possível utilizar a mesma senha!');
                endif;

                $UPDATE = "UPDATE usuarios SET senha = :senha WHERE email = :email AND cashe = :cashe";
                $result = Conexao::getCon(1)->prepare($UPDATE);
                $result->bindParam(':email', $this->emailRecover, PDO::PARAM_STR);
                $result->bindParam(':cashe', $cashe, PDO::PARAM_STR);
                $result->bindParam(':senha', $senha, PDO::PARAM_STR);
                $result->execute();
                $contar = $result->rowCount();
                if($contar > 0){
                    return $this->setLogin();
                }else{
                    return json_encode('Serviço indisponível!');
                }

            }else{
                return json_encode('Usuário não encontrado!');
            }
        } catch (\Throwable $th) {
            return json_encode($th);
        } 
    }

}

if (post('login')):

    $data = new Login();
    eco($data->setLogin());
    exit();

elseif(post('cadastro')):

    $data = new Login();
    eco($data->setCadastro());
    exit();

elseif(post('recover')):

    $data = new Login();
    eco($data->verifyEmail());
    exit();

elseif(post('recoverkey')):

    $data = new Login();
    eco($data->verifyKey());
    exit();

elseif(post('recoverpass')):

    $data = new Login();
    eco($data->verifyPass());
    exit();

elseif(post('emailConfirm')):

    $data = new Login();
    eco($data->emailCheck());
    exit();

elseif(post('referencing')):

    $data = new Login();
    eco($data->referencingEmail());
    exit();

endif;
    
?>