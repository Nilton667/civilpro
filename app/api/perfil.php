<?php

include_once 'conexao.php';

class Perfil extends Conexao
{

    private $id, $nome, $sobrenome,  $email, $identificacao, $nacionalidade, $morada, $genero, $telemovel, $senha, $file;

    //PASTA
    private $folder = '../../publico/img/perfil/';

    function __construct()
    {

        $this->id = Components\getSession('maestro', 'id', 1,  true);

        $this->nome          = post('nome', false)
        ? filterVar(post('nome'))
        : '';

        $this->sobrenome     = post('sobrenome', false)
        ? filterVar(post('sobrenome'))
        : '';

        $this->email          = post('email', false)
        ? filterEmail(post('email'))
        : DEFAULT_STRING;

        $this->identificacao = post('identificacao', false) 
        ? filterVar(post('identificacao'))  
        : '';

        $this->nacionalidade = post('nacionalidade', false)
        ? filterVar(post('nacionalidade'))  
        : '';

        $this->morada        = post('morada', false)
        ? filterVar(post('morada'))  
        : '';

        $this->genero        = post('genero', false)
        ? filterVar(post('genero'))  
        : 'M';

        $this->telemovel     = post('telemovel', false) 
        ? filterVar(post('telemovel'))  
        : '';
    
        $this->senha        = post('senha', false)
        ? filterVar(post('senha'))  
        : '';

        $this->file          = _file('img');
        
    }

    function updateUser()
    {
        $update = DB\Mysql::update(
            'UPDATE usuarios SET nome = :nome, sobrenome = :sobrenome, identificacao = :identificacao, nacionalidade = :nacionalidade, morada = :morada, genero = :genero, telemovel = :telemovel WHERE id = :id',
            [
                'id'            => $this->id,
                'nome'          => $this->nome,
                'sobrenome'     => $this->sobrenome,
                'identificacao' => $this->identificacao,
                'nacionalidade' => $this->nacionalidade,
                'morada'        => $this->morada,
                'genero'        => $this->genero,
                'telemovel'     => $this->telemovel
            ]
        );
        if(is_numeric($update) && $update > 0){
            return json_encode(1);
        }else{
            return json_encode('Nenhuma alteração efetuada!');
        }
    }

    function setFoto() //Altera foto de perfil
    {
        //FILE INFO
        if ($this->file === false):
            return json_encode("Selecione pelo menos uma imagem para o diretorio!");
        elseif(!isset($this->file) || $this->id === null):
            return json_encode("Usuário não encontrado!");
        endif;

        $numFile    = count(array_filter($this->file['name']));

        //PASTA
        if(!is_dir($this->folder)){
            @mkdir($this->folder);
        }

        //REQUISITOS
        $permite    = array('image/png', 'image/jpeg', 'image.jpg');
        $maxSize    = 1024 * 1024 * 2;
        
        //MENSAGENS
        $msg        = array();
        $errorMsg   = array(
            1 => 'A imagem no upload é maior do que o limite definido!',
            2 => 'A imagem ultrapassa o limite de tamanho permitido!',
            3 => 'O upload da imagem foi feito parcialmente!',
            4 => 'Não foi possível terminar o upload!'
        );
        
        if($numFile <= 0){
            return json_encode("Selecione pelo menos uma imagem para o diretório!");
        }else{
            for($i = 0; $i < $numFile; $i++){
                $name   = $this->file['name'][$i];
                $type   = $this->file['type'][$i];
                $size   = $this->file['size'][$i];
                $error  = $this->file['error'][$i];
                $tmp    = $this->file['tmp_name'][$i];
                
                $extensao = @end(explode('.', $name));
                $novoNome = date('dmyHis').mt_rand(10,500).".".$extensao;
                
                if($error != 0){
                    return json_encode($errorMsg[$error]);
                }
                else if(!in_array($type, $permite)){
                    return json_encode("Imagem não suportada!");
                }
                else if($size > $maxSize){
                    return json_encode("Esta imagem ultrapassa o limite de upload suportado!");
                }
                else{
                    
                    if(move_uploaded_file($tmp, $this->folder.'/'.$novoNome)):

                        $SELECT = "SELECT imagem from usuarios WHERE id = :id";
                        try{
                            $result = Conexao::getCon(1)->prepare($SELECT);
                            $result->bindParam(':id', $this->id, PDO::PARAM_INT);
                            $result->execute();
                            $contar = $result->rowCount();
                            if ($contar > 0):
                                while ($mostra     = $result->FETCH(PDO::FETCH_OBJ)) {
                                    $foto_anterior = $mostra->imagem;
                                }
                            else:
                                return json_encode('Você não tem permição para alterar a foto de perfil!');
                            endif;
                        
                            $UPDATE = "UPDATE usuarios SET imagem = :img WHERE id = :id";

                            $result = Conexao::getCon(1)->prepare($UPDATE);
                            $result->bindParam(':id', $this->id, PDO::PARAM_INT);
                            $result->bindParam(':img', $novoNome, PDO::PARAM_STR);
                            $result->execute();
                            $contar = $result->rowCount();
                            if ($contar>0):

                                if (isset($foto_anterior)){
                                    if (!empty($foto_anterior)):
                                        @unlink("../../assets/img/perfil/".$foto_anterior);
                                    endif;
                                }

                                $return = array('status'=> 1, 'imagem'=> $novoNome);
                                return json_encode($return);

                            else:
                                return json_encode('Não foi possível alterar a sua foto de perfil!');
                            endif;
                        } catch (PDOException $e){
                            return json_encode($e);
                        }

                    else:
                        return json_encode(0);
                    endif;
                }
            }
        }
    }

    //Altera senha
    function updatePass($new){

        $senha = password_hash($new, PASSWORD_DEFAULT);

        $select = DB\Mysql::select(
            'SELECT id, senha FROM usuarios WHERE email = :email',
            ['email' => $this->email]
        );

        if(is_array($select)){

            if(password_verify($this->senha, $select[0]['senha'])):

                if(password_verify($new, $select[0]['senha'])):
                    return 'Não é possível utilizar a mesma senha!';
                endif;
                
                $update = DB\Mysql::update(
                    'UPDATE usuarios SET senha = :senha WHERE email = :email',
                    [
                        'email' => $this->email,
                        'senha' => $senha,
                    ]
                );
                if(is_numeric($update) && $update > 0){
                    return 1;
                }else{
                    return 'Não foi possível alterar a sua senha!';
                }
                
            else:
                return 'A sua senha esta incorreta!';
            endif;
        }else{
            return 'Usuário não encontrado!';
        }

    }

}

if(post('edit_usuarios')):

    $data = new Perfil();
    eco($data->updateUser());
    exit();

elseif(post('image')):

    $data = new Perfil();
    eco($data->setFoto());
    exit();

elseif(post('update_password')):

    $data = new Perfil();
    eco($data->updatePass(filterVar(post('senha_new'))), true);
    exit();

endif;

?>