<?php

include_once '../conexao.php';

class Perfil extends Conexao
{

    private $id, $nome, $sobrenome, $email, $morada, $genero, $telemovel, $senha, $file;

    //PASTA
    private $folder = '../../publico/img/perfil/';

    function __construct()
    {

        $this->id = isAccessible(
            post('id', false)    ? filterInt(post('id'))    : 0, 
            post('token', false) ? filterVar(post('token')) : ''
        );

        $this->nome          = post('nome', false)
        ? filterVar(post('nome'))
        : DEFAULT_STRING;

        $this->sobrenome     = post('sobrenome', false)
        ? filterVar(post('sobrenome'))
        : DEFAULT_STRING;

        $this->email        = post('email', false)
        ? filterEmail(post('email'))
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

        $this->senha         = post('senha', false) 
        ? filterVar(post('senha'))  
        : DEFAULT_STRING;

        $this->file          = _file('img');
        
    }

    function updateUser()
    {
        $update = DB\Mysql::update(
            "UPDATE adm SET nome = :nome, sobrenome = :sobrenome, morada = :morada, telemovel = :telemovel WHERE id = :id AND email = :email",
            [
                'id'        => $this->id,
                'email'     => $this->email,
                'nome'      => $this->nome,
                'sobrenome' => $this->sobrenome,
                'morada'    => $this->morada,
                'telemovel' => $this->telemovel,
            ]
        );
        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return 'Não foi possível editar o seu perfil!';
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

if(post('edit_usuario')):

    $data = new Perfil();
    eco($data->updateUser(), true);
    exit();

elseif(post('update_password')):

    $data = new Perfil();
    eco($data->updatePass(filterVar(post('senha_new'))), true);
    exit();

endif;

?>