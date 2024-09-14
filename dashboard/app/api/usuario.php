<?php

include_once 'conexao.php';

Class Usuario{

    private $id, $usuario_id, $email, $senha, $data;

    //Cadatro
    private $nome, $sobrenome, $nacionalidade, $genero;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->usuario_id  = post('usuario_id', false)
        ? filterVar(post('usuario_id'))  
        : DEFAULT_INT;

        $this->email        = post('email', false)
        ? filterEmail(post('email')) 
        : DEFAULT_STRING;

        $this->senha        = post('senha', false)
        ? filterVar(post('senha'))  
        : '';

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

    function removeData() //Remover usuario
    {
        $this->usuario_id = array_map('intval' ,explode(',', $this->usuario_id));
        $key = array_search('', $this->usuario_id);

        if($key!==false){
            unset($this->usuario_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM usuarios WHERE id IN(".implode(',', $this->usuario_id).")",
            []
        );
        if (is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

    //Cadastrar usuario
    function setCadastro()
    {
        try {
            $SELECT = "SELECT id FROM usuarios WHERE BINARY email = :email";
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

        try {
            $INSERT = "INSERT INTO usuarios (nome, sobrenome, email, nacionalidade, genero, senha, registo, cashe, authorization) VALUES (:nome, :sobrenome, :email, :nacionalidade, :genero, :senha, :registo, :cashe, :authorization)";
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
                return 1;
            }else{
                return 'Serviço indisponível!';
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }

}

if(post('remove_usuario')):

    $data = new Usuario();
    eco($data->removeData(), true);
    exit();

elseif(post('cadastro')):

    $data = new Usuario();
    eco($data->setCadastro(), true);
    exit();

endif;

?>