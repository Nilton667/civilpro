<?php
    
include_once '../conexao.php';

class Newsletter extends Conexao
{
    private $email;
    private $registo;
    
    function __construct()
    {
        $this->email    = post('email')
        ? filterEmail(post('email'))  
        : DEFAULT_STRING;

        $this->registo  = date('d/m/Y');
    }

    //Adicionar email
    function add()
    {

        if ($this->email == ''):
            return json_encode('Insira um e-mail valido!');
        endif;

        try {
            $SELECT = "SELECT id FROM newsletter WHERE email = :email";
            $result = Conexao::getCon(1)->prepare($SELECT);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if ($contar >= 1) {
                return json_encode('Esta conta de email ja se encontra registada!');
            }
        } catch (\Throwable $th) {
            return json_encode($th);
        }

        //Continue

        try {
            $INSERT = "INSERT INTO newsletter (email, registo) VALUES (:email, :registo)";
            $result = Conexao::getCon(1)->prepare($INSERT);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->bindParam(':registo', $this->registo, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if($contar > 0):
                return json_encode(1);
            else:
                return json_encode(0);
            endif;
        } catch (\Throwable $th) {
            return json_encode($th);
        }

    }

    //Remover email
    function remove()
    {

        if ($this->email == ''):
            return json_encode('Insira um e-mail valido!');
        endif;

        try {
            $DELETE = "DELETE FROM newsletter WHERE email = :email LIMIT 1";
            $result = Conexao::getCon(1)->prepare($DELETE);
            $result->bindParam(':email', $this->email, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if($contar > 0):
                return json_encode(1);
            else:
                return json_encode(0);
            endif;
        } catch (\Throwable $th) {
            return json_encode($th);
        }

    }
}

if(post('add_newsletter')):

    $data = new Newsletter();
    eco($data->add());
    exit();

elseif(post('remove_newsletter')):

    $data = new Newsletter();
    eco($data->remove());
    exit();

endif;

?>