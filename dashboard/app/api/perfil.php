<?php

include_once 'conexao.php';

class Perfil
{

    private $id, $nome, $email, $sobrenome, $identificacao, $nacionalidade, $morada, $genero, $telemovel, $senha, $file;

    //PASTA
    private $folder = '../../assets/img/perfil/';

    function __construct()
    {

        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->nome          = post('nome', false)
        ? filterVar(post('nome'))
        : DEFAULT_STRING;

        $this->email          = post('email', false)
        ? filterEmail(post('email'))
        : DEFAULT_STRING;

        $this->sobrenome     = post('sobrenome', false)
        ? filterVar(post('sobrenome'))
        : DEFAULT_STRING;

        $this->identificacao = post('identificacao', false) 
        ? filterVar(post('identificacao'))  
        : DEFAULT_STRING;

        $this->nacionalidade = post('nacionalidade', false)
        ? filterVar(post('nacionalidade'))  
        : DEFAULT_STRING;

        $this->morada        = post('morada', false)
        ? filterVar(post('morada'))  
        : DEFAULT_STRING;

        $this->genero        = post('genero', false)
        ? filterVar(post('genero'))  
        : 'M';

        $this->telemovel     = post('telemovel', false) 
        ? filterVar(post('telemovel'))  
        : DEFAULT_STRING;

        $this->senha        = post('senha', false)
        ? filterVar(post('senha'))  
        : '';

        $this->file          = _file('img');
        
    }

    function updateAdm()
    {
        $update = DB\Mysql::update(
            'UPDATE adm SET nome = :nome, sobrenome = :sobrenome, identificacao = :identificacao, nacionalidade = :nacionalidade, morada = :morada, genero = :genero, telemovel = :telemovel WHERE id = :id',
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
            return 1;
        }else{
            return 'Nenhuma alteração efetuada!';
        }
    }

    function setFoto() //Altera foto de perfil
    {
        //FILE INFO
        if ($this->file === false):
            return "Selecione pelo menos uma imagem para o diretorio!";
        elseif(!isset($this->file) || $this->id === null):
            return "Usuário não encontrado!";
        endif;

        $upload = Components\uploadFile::upload(
            $this->file, 
            $this->folder, 
            ['image/png', 'image/jpg', 'image/jpeg'], 
            (1024 * 1024 * 2) // 2MB
        );

        if(is_array($upload) && isset($upload[0])){
            $select = DB\Mysql::select(
                'SELECT imagem from adm WHERE id = :id',
                ['id' => $this->id]
            );
            if(is_array($select)){
                $foto_anterior = $select[0]['imagem'];
            }else{
                return 'Você não tem permição para alterar a foto de perfil!';
            }

            $update = DB\Mysql::update(
                'UPDATE adm SET imagem = :img WHERE id = :id',
                [
                    'id'  => $this->id,
                    'img' => $upload[0]['name'],
                ]
            );
            if(is_numeric($update) && $update > 0){
                if (isset($foto_anterior)){
                    if (!empty($foto_anterior)):
                        @unlink("../../assets/img/perfil/".$foto_anterior);
                    endif;
                }

                $return = array('status'=> 1, 'imagem' => $upload[0]['name']);
                return $return;
            }else{
                return 'Não foi possível alterar a sua foto de perfil!';
            }

        }else{
            return $upload;
        }
    }

    //Altera senha
    function updatePass($new){

        $senha = password_hash($new, PASSWORD_DEFAULT);

        $select = DB\Mysql::select(
            'SELECT id, senha FROM adm WHERE email = :email',
            ['email' => $this->email]
        );

        if(is_array($select)){

            if(password_verify($this->senha, $select[0]['senha'])):

                if(password_verify($new, $select[0]['senha'])):
                    return 'Não é possível utilizar a mesma senha!';
                endif;
                
                $update = DB\Mysql::update(
                    'UPDATE adm SET senha = :senha WHERE email = :email',
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

if(post('edit_adm')):

    $data = new Perfil();
    eco($data->updateAdm(), true);
    exit();

elseif(post('image')):

    $data = new Perfil();
    eco($data->setFoto(), true);
    exit();

elseif(post('update_password')):
    
    $data = new Perfil();
    eco($data->updatePass(filterVar(post('senha_new'))), true);
    exit();
        
endif;

?>