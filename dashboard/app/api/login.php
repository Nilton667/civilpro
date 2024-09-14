<?php

include_once 'conexao.php';

class Login
{
    private $id, $email, $emailRecover, $senha, $checkbox, $dispositivo, $cashe, $data;

    //Cadatro
    private $nome, $sobrenome, $nacionalidade, $genero;

    function __construct(){

        $this->email        = post('email', false)
        ? filterEmail(post('email')) 
        : DEFAULT_STRING;

        $this->emailRecover = post('email', false)
        ? filterEmail(base64_decode(post('email')))
        : DEFAULT_STRING;

        $this->senha        = post('senha', false)
        ? filterVar(post('senha'))  
        : '';

        $this->checkbox     = is_numeric(post('checkbox', false))
        ? filterInt(post('checkbox'), 0)
        : DEFAULT_INT;

        $this->dispositivo  = post('dispositivo', false) 
        ? filterVar(post('dispositivo'))  
        : DEFAULT_STRING;

        $this->cashe        = is_numeric(post('cashe', false)) || post('cashe', false) && post('recoverpass', false)
        ? filterVar(post('cashe'))  
        : DEFAULT_INT;

        $this->data          = date('d/m/Y');

        //Cadastro
        $this->nome          = post('nome', false)
        ? filterVar(post('nome'))  
        : DEFAULT_STRING;

        $this->sobrenome     = post('sobrenome', false) 
        ? filterVar(post('sobrenome'))
        : DEFAULT_STRING;

        $this->nacionalidade = post('nacionalidade', false)
        ? filterVar(post('nacionalidade'))  
        : DEFAULT_STRING;

        $this->genero        = post('genero', false)
        ? filterVar(post('genero'))  
        : DEFAULT_STRING;

    }

    function setLogin(){
        
        if(post('recoverpass')):
            $this->email = $this->emailRecover;
        endif;

        $select = DB\Mysql::select(
            "SELECT id, email, senha, registo FROM adm WHERE BINARY email = :email LIMIT 1",
            [
               'email' => $this->email
            ]
        );

        if(is_array($select)){
            if (password_verify($this->senha, $select[0]['senha'])) {

                $token = sha1(date('d/m/Y-h-i-s').$select[0]['id']);
                $tempo = date('d/m/Y');

                if ($this->checkbox == 1) {
                    $tempo = date('d/m/Y', time() + (365 * 24 * 60 * 60));   
                }

                return $this->setToken(
                    $select[0]['id'], 
                    $token,
                    $this->dispositivo,
                    $tempo,
                    $this->data
                );

            }else{
                return 'Email ou senha errada!';    
            }
        }else{
            return 'Usuário não encontrado!';
        }
    }

    //Gerando token de acesso
    function setToken($id, $token, $dispositivo, $tempo, $registo){
        $data = array(
            'id'          => $id, 
            'token'       => $token, 
            'dispositivo' => $dispositivo, 
            'tempo'       => $tempo, 
            'registo'     => $registo
        );
        $insert = DB\Mysql::insert(
            "INSERT INTO acesso (id_adm, token, dispositivo, tempo, registo) VALUES (:id, :token, :dispositivo, :tempo, :registo)",
            $data
        );
        if(is_numeric($insert) && $insert > 0):
            if(Components\setSession('maestro_adm', $data)){
                return 1;
            }else{
                return 'Não foi possível iníciar sessão!';
            }
        else:
            return 'Ocorreu um problema de rede, tente novamente mais tarde!';
        endif;
    }

    //Cadastrar usuario
    function setCadastro()
    {
        try {
            $SELECT = "SELECT id FROM adm WHERE BINARY email = :email";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar >= 1) {
                return 'Esta conta de e-mail ja se encontra registada!';
            }
        } catch (\Throwable $th) {
            return $th;
        }

        $senha          = password_hash($this->senha, PASSWORD_DEFAULT);
        $cashe          = mt_rand(100000,900000);
        $authorization  = sha1(uniqid($this->email,true));

        include_once '../envoyer.php';
        $sendEmail = emailSend(
            $this->nome, 
            $this->email, 
            'Confirmação de email', 
            'Olá '.$this->nome.' este é o seu código de confirmação '.$cashe
        );

        if($sendEmail != true):
            return 'Não foi possível enviar o seu email de confirmação!';
        endif;

        try {
            $INSERT = "INSERT INTO adm (nome, sobrenome, email, nacionalidade, genero, senha, registo, cashe, authorization) VALUES (:nome, :sobrenome, :email, :nacionalidade, :genero, :senha, :registo, :cashe, :authorization)";
            $result = Conexao::getCon(1)->prepare($INSERT);
            $result->bindParam(':nome', $this->nome, PDO::PARAM_STR);
            $result->bindParam(':sobrenome', $this->sobrenome, PDO::PARAM_STR);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->bindParam(':nacionalidade', $this->nacionalidade, PDO::PARAM_STR);
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
                return 'Serviço indisponível!';
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }

    //Reenvia email
    function referencingEmail(){

        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        try {
            $SELECT = "SELECT id, nome, email FROM adm WHERE id = :id";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':id', $this->id, PDO::PARAM_INT);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $userData    = $result->fetchAll();
                $this->nome  = $userData[0]['nome'];
                $this->email = $userData[0]['email'];
                $cashe     = mt_rand(100000,900000);

                include_once '../envoyer.php';
                $sendEmail = emailSend(
                    $this->nome, 
                    $this->email, 
                    'Confirmação de email', 
                    'Olá '.$this->nome.' este é o seu código de confirmação '.$cashe
                );
        
                if($sendEmail != true){
                    return 'Não foi possível enviar o seu email de confirmação!';
                }else{
                    
                    $UPDATE = "UPDATE adm SET cashe = :cashe WHERE id = :id";
                    $result = Conexao::getCon(1)->prepare($UPDATE);
                    $result->bindParam(':id', $this->id, PDO::PARAM_INT);
                    $result->bindParam(':cashe', $cashe, PDO::PARAM_INT);
                    $result->execute();
                    $contar = $result->rowCount();
                    if($contar > 0){
                        return 1;
                    }else{
                        return 'Serviço indisponível!';
                    }

                }

            }else{
                return 'Usuário não encontrado!';
            }

        } catch (\Throwable $th) {
            return $th;
        }

    }

    //Recover check->email
    function verifyEmail()
    {
        try {
            $SELECT = "SELECT id, nome, sobrenome, email, cashe, checkCashe FROM adm WHERE BINARY email = :email LIMIT 1";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $userData = $result->fetchAll();
                $cashe    = mt_rand(100000,999999);

                $userData[0]['user'] = base64_encode($userData[0]['email']);

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
                    return 'Não foi possível enviar o seu email de recuperação!';
                endif;

                $UPDATE = "UPDATE adm SET cashe = :cashe, checkCashe = :checkCashe WHERE email = :email";
                $result = Conexao::getCon(1)->prepare($UPDATE);
                $result->bindParam(':email', $this->email, PDO::PARAM_STR);
                $result->bindParam(':cashe', $cashe, PDO::PARAM_INT);
                $result->bindParam(':checkCashe', $checkCashe, PDO::PARAM_INT);
                $result->execute();
                $contar = $result->rowCount();
                if($contar > 0){
                    return $userData;
                }else{
                    return 'Serviço indisponível!';
                }

            }else{
                return 'Usuário não encontrado!';
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }

    //Verificar email
    function emailCheck(){

        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        try {
            $SELECT = "SELECT id FROM adm WHERE id = :id AND cashe = :cashe LIMIT 1";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':id', $this->id, PDO::PARAM_INT);
            $result->bindParam(':cashe', $this->cashe, PDO::PARAM_INT);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $UPDATE = "UPDATE adm SET checkCashe = :cashe WHERE id = :id AND cashe = :cashe";
                $result = Conexao::getCon(1)->prepare($UPDATE);
                $result->bindParam(':id', $this->id, PDO::PARAM_INT);
                $result->bindParam(':cashe', $this->cashe, PDO::PARAM_INT);
                $result->execute();
                $contar = $result->rowCount();
                if($contar > 0){
                    return 1;
                }else{
                    return 'Não foi possível verificar a sua conta de email!';
                }
            }else{
                return 'Código inválido!';
            }
        }catch(\Throwable $th){
            return $th;
        }
    }

    //Recover check->key
    function verifyKey()
    {
        try {
            $SELECT = "SELECT id, nome, sobrenome, email, cashe FROM adm WHERE BINARY email = :email AND cashe = :cashe LIMIT 1";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->emailRecover, PDO::PARAM_STR);
            $result->bindParam(':cashe', $this->cashe, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $userData = $result->fetchAll();
                $userData[0]['user'] = base64_encode($userData[0]['email']);
                $userData[0]['key']  = base64_encode($userData[0]['cashe']);
                return $userData;

            }else{
                return 'Código inválido!';
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    
    //Recupera senha 
    function verifyPass(){
        $cashe = base64_decode($this->cashe);
        $senha = password_hash($this->senha, PASSWORD_DEFAULT);
        try {
            $SELECT = "SELECT id, senha FROM adm WHERE email = :email AND cashe = :cashe";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->emailRecover, PDO::PARAM_STR);
            $result->bindParam(':cashe', $cashe, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar > 0) {

                $userData = $result->fetchAll();
                if(password_verify($this->senha, $userData[0]['senha'])):
                    return 'Não é possível utilizar a mesma senha!';
                endif;

                $UPDATE = "UPDATE adm SET senha = :senha WHERE email = :email AND cashe = :cashe";
                $result = Conexao::getCon(1)->prepare($UPDATE);
                $result->bindParam(':email', $this->emailRecover, PDO::PARAM_STR);
                $result->bindParam(':cashe', $cashe, PDO::PARAM_STR);
                $result->bindParam(':senha', $senha, PDO::PARAM_STR);
                $result->execute();
                $contar = $result->rowCount();
                if($contar > 0){
                    return $this->setLogin();
                }else{
                    return 'Serviço indisponível!';
                }

            }else{
                return 'Usuário não encontrado!';
            }
        } catch (\Throwable $th) {
            return $th;
        } 
    }

}

if (post('login')):

    $data = new Login();
    eco($data->setLogin(), true);
    exit();

elseif(post('cadastro')):

    $data = new Login();
    eco($data->setCadastro(), true);
    exit();

elseif(post('recover')):

    $data = new Login();
    eco($data->verifyEmail(), true);
    exit();

elseif(post('recoverkey')):

    $data = new Login();
    eco($data->verifyKey(), true);
    exit();

elseif(post('recoverpass')):

    $data = new Login();
    eco($data->verifyPass(), true);
    exit();

elseif(post('emailConfirm')):

    $data = new Login();
    eco($data->emailCheck(), true);
    exit();

elseif(post('referencing')):

    $data = new Login();
    eco($data->referencingEmail(), true);
    exit();

endif;
    
?>