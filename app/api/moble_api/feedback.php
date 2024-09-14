<?php

include_once 'conexao.php';

class Feedback
{
    private $nome, $email, $mensagem, $registo;

    function __construct(){

        $this->nome     = post('nome', false)
        ? filterVar(post('nome')) 
        : DEFAULT_STRING;

        $this->email    = post('email', false)
        ? filterEmail(post('email')) 
        : DEFAULT_STRING;

        $this->mensagem = post('mensagem', false)
        ? filterVar(post('mensagem')) 
        : DEFAULT_STRING;

        $this->registo  = date('d/m/Y');

    }

    function setData(){

        $insert = DB\Mysql::insert(
            'INSERT INTO feedback (nome, email, mensagem, registo) VALUES (:nome, :email, :mensagem, :registo)',
            [
               'nome' => $this->nome,
               'email' => $this->email,
               'mensagem' => $this->mensagem,
               'registo' => $this->registo
            ]
        );

        if(is_numeric($insert) && $insert > 0){
            return $insert;
        }else{
            return 0;
        }
    }

}

if (post('setData')):

    $data = new Feedback();
    eco($data->setData(), true);
    exit();

endif;
    
?>