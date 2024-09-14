<?php

include_once '../conexao.php';

class Login
{
    private $email, $senha, $morada, $dispositivo, $cashe, $data;

    //Cadatro
    private $nome, $sobrenome, $nacionalidade, $genero;

    function __construct(){

        $this->email        = post('email')
        ? filterEmail(post('email')) 
        : DEFAULT_STRING;

        $this->senha        = post('senha')
        ? filterVar(post('senha'))  
        : '';

        $this->morada        = post('morada')
        ? filterVar(post('morada'))  
        : '';

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
        : 'Angola';

        $this->genero        = post('genero')
        ? filterVar(post('genero'))  
        : 'M';

    }

    function setLogin(){

        $select = DB\Mysql::select(
            "SELECT * FROM usuarios WHERE email = :email LIMIT 1",
            [
               'email' => $this->email
            ]
        );

        if(is_array($select)){
            if (password_verify($this->senha, $select[0]['senha'])) {

                $token = sha1(date('d/m/Y-h-i-s').$select[0]['id']);
                $tempo = date('d/m/Y', time() + (90 * 24 * 60 * 60));   

                $return = $this->setToken(
                    $select[0]['id'], $token, $this->dispositivo, $tempo, $this->data
                );

                array_push($select, ['token' => $token]);

                if($return == 1): return json_encode($select); else: return json_encode($return); endif;

            }else{
                return json_encode('Email ou senha errada!');    
            }
        }else{
            return json_encode('Usuário não encontrado!');
        }
    }

    //Gerando token de acesso
    function setToken($id, $token, $dispositivo, $tempo, $registo){

        $insert = DB\Mysql::insert(
            "INSERT INTO acesso (id_adm, token, dispositivo, tempo, registo) VALUES (:id_adm, :token, :dispositivo, :tempo, :registo)",
            [
                'id_adm'      => $id,
                'token'       => $token,
                'dispositivo' => $dispositivo,
                'tempo'       => $tempo,
                'registo'     => $registo
            ]
        );

        if(is_numeric($insert) && $insert > 0):
            return json_encode(1);
        else:
            return json_encode('Ocorreu um problema de rede, tente novamente mais tarde!');
        endif;

    }

    //Cadastrar usuario
    function setCadastro()
    {
        $select = DB\Mysql::select(
            "SELECT id FROM usuarios WHERE email = :email",
            [
                'email' => $this->email
            ]
        );

        if(is_array($select)){
            return json_encode('Esta conta de email ja se encontra registada!');
        }else{
            $senha = password_hash($this->senha, PASSWORD_DEFAULT);
            $insert = DB\Mysql::insert(
                "INSERT INTO usuarios (nome, sobrenome, email, morada, nacionalidade, genero, senha, registo, cashe, checkCashe) VALUES (:nome, :sobrenome, :email, :morada, :nacionalidade, :genero, :senha, :registo, :cashe, :checkCashe)",
                [
                    'nome'          =>  $this->nome,
                    'sobrenome'     =>  $this->sobrenome,
                    'email'         =>  $this->email,
                    'morada'        =>  $this->morada,
                    'nacionalidade' =>  $this->nacionalidade,
                    'genero'        =>  $this->genero,
                    'senha'         =>  $senha,
                    'registo'       =>  $this->data,
                    'cashe'         =>  $this->cashe,
                    'checkCashe'    =>  $this->cashe,
                ]
            );
            if (is_numeric($insert) && $insert > 0) {
                return $this->setLogin();
            }else{
                return json_encode('Serviço indisponível!');
            }

        }

    }

    //Reenvia email
    function referencingEmail(){

        /*try {
            $SELECT = "SELECT id, nome, email FROM usuarios WHERE id = :id";
            $result = Conexao::getCon()->prepare($SELECT);
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
                    $result = Conexao::getCon()->prepare($UPDATE);
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
        }*/

    }

    //Verificar email
    function emailCheck(){
        $select = DB\Mysql::select(
            "SELECT email FROM usuarios WHERE email = :email",
            [
                'email' => $this->email
            ]
        );

        if(is_array($select)){
            return json_encode(1);
        }else{

            $cashe     = mt_rand(100000, 900000);
            
            include_once './../../envoyer.php';
            $sendEmail = emailSend(
                $this->nome, 
                $this->email, 
                'Confirmação de email', 
                "Olá {$this->nome} este é o seu código de confirmação $cashe"
            );
    
            if($sendEmail != true){
                return json_encode('Não foi possível enviar o seu email de confirmação!');
            }else{

                $return = array(0 => array('cashe' => $cashe));
                return json_encode($return);

            }
        
        }
    }

    //Recover check email
    function verifyEmail()
    {
        $select = DB\Mysql::select(
            "SELECT id, nome, sobrenome, email, cashe, checkCashe FROM usuarios WHERE email = :email LIMIT 1",
            [
                'email' => $this->email
            ]
        );

        if(is_array($select)){

            $cashe = mt_rand(100000, 999999);

            //Verificar se a conta ja foi confirmada
            if($select[0]['cashe'] == $select[0]['checkCashe']):
                $checkCashe = $cashe;
            else:
                $checkCashe = 0;
            endif;

            include_once './../../envoyer.php';
            $sendEmail = emailSend(
                $select[0]['nome'], 
                $select[0]['email'], 
                'Recuperação da conta', 
                "Olá {$select[0]['nome']} este é o seu código de recuperação $cashe"
            );

            if($sendEmail != true):
                return json_encode('Não foi possível enviar o seu email de recuperação!');
            endif;

            $update = DB\Mysql::update(
                "UPDATE usuarios SET cashe = :cashe, checkCashe = :checkCashe WHERE email = :email",
                [
                    'email' => $this->email,
                    'cashe' => $cashe,
                    'checkCashe' => $checkCashe
                ]
            );

            if(is_numeric($update) && $update > 0):
                return json_encode($select);
            else:
                return json_encode('Serviço indisponível!');
            endif;
        }else{
            return json_encode('Usuário não encontrado!');
        }

    }

    //Recover check key
    function verifyKey()
    {
        $select = DB\Mysql::select(
            "SELECT email, cashe FROM usuarios WHERE email = :email AND cashe = :cashe LIMIT 1",
            [
                'email' => $this->email,
                'cashe' => $this->cashe
            ]
        );

        if(is_array($select)):
            return json_encode($select);
        else:
            return json_encode(0);
        endif;

    }
    
    //Altera senha
    function verifyPass(){
        
        $senha = password_hash($this->senha, PASSWORD_DEFAULT);

        $select = DB\Mysql::select(
            "SELECT id, senha FROM usuarios WHERE email = :email AND cashe = :cashe",
            [
                'email' => $this->email,
                'cashe' => $this->cashe
            ]
        );

        if(is_array($select)){
            if(password_verify($this->senha, $select[0]['senha'])):
                return json_encode('Não é possível utilizar a mesma senha!');
            endif;
            
            $update = DB\Mysql::update(
                "UPDATE usuarios SET senha = :senha WHERE email = :email AND cashe = :cashe",
                [
                    'email' => $this->email,
                    'cashe' => $this->cashe,
                    'senha' => $senha
                ]
            );

            if(is_numeric($update) && $update > 0){
                return $this->setLogin();
            }else{
                return json_encode('Serviço indisponível!');
            }

        }else{
            return json_encode(0);
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