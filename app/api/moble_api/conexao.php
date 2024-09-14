<?php

header("Access-Control-Allow-Origin: *");

require_once 'vendor/autoload.php';
if(!post('permission', false) || post('permission') != 'oratoriam'){
  eco(json_encode('Acesso negado!'));
  exit();
}

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
      eco($conexao->getMessage() ?? 'Não foi possível estabelecer uma ligação com a base de dados!');
    endif;
    exit();
  }
}

function isAccessible($id, $token)
{
  $select = DB\Mysql::select(
    "SELECT id_adm FROM acesso WHERE id_adm = :id AND token = :token",
    [
      'id'    => $id,
      'token' => $token
    ]
  );

  if(is_array($select)):
    return $select[0]['id_adm'];
  else:
    eco('Sem sessão iniciada!', true);
    exit;
  endif;

}
?>