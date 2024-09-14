<?php

session_start();
require_once 'vendor/autoload.php';

class Conexao //Conexão com a base de dados
{ 
  static function getCon($db_select)
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
      eco('
        <!DOCTYPE html>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Dashboard</title>
        <link rel="shortcut icon" type="image/x-icon" href="theme-assets/images/ico/favicon.png">
        <link rel="stylesheet" type="text/css" href="theme-assets/css/vendors.css">
        <style>
          .content{
            background: #1c1c1c;
            color: #fafafa;
            z-index: 1024;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 12px;
          }
        </style>
        <div class="content">
          <div>
            <p style="font-size: 1.4em;">Não foi possível estabelecer uma ligação com a base de dados!</p>
            <p style="font-size: 1.4em;">Erro: '.$conexao->getMessage().'</p>
            <a class="btn btn-primary" href="./">Actualizar</a>
          </div>
        </div>
      ');
      endif;
    exit();
  }
}
  
?>