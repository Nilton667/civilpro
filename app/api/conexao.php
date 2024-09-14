<?php

session_start();
require_once 'vendor/autoload.php';

define('__EMAIL__', __DIR__.'/../prefs/email.json');

class Conexao //Conexão com a base de dados
{
  static function getCon($db_select) //Pegar conexão
  {
    
    if($db_select == 1):
      $array = [
        'host'     => 'localhost', 
        'database' => 'maestro', 
        'user'     => 'root', 
        'password' => ''
      ];
      $conexao = DB\Mysql::Connect($array['host'], $array['database'], $array['user'], $array['password']);
    endif;
    
    if(isset($conexao) && DB\Mysql::Check($conexao)):
      return $conexao;
    else:
      eco(isset($conexao) ? $conexao->getMessage() : 'Não foi possível estabelecer uma ligação com a base de dados!');
    endif;
    exit();

  }
  
  function isAccessible($id, $token)
  {
    $select = DB\Mysql::select(
      'SELECT id_adm FROM acesso WHERE id_adm = :id AND token = :token',
      ['id' => $id, 'token' =>  $token]
    );

    if(is_array($select)){
      return $this->getUser($select[0]['id_adm']);
    }else{
      return json_encode('Acesso negado!');
    }
  }

  function getUser($id)
  {
    $select = DB\Mysql::select(
      'SELECT * FROM usuarios WHERE id = :id',
      ['id' => $id]
    );

    if(is_array($select)){
      return json_encode($select);
    }else{
      return json_encode('Nenhum usuário encontrado!');
    }
  }

}
?>