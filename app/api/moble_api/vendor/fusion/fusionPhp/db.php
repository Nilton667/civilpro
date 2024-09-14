<?php
  namespace DB
  {
    use Conexao;
    class Mysql extends Conexao
    {
      //$host: Nome do host
      //$name: Nome da base de dados
      //$user: Nome de usuario
      //$password: Palavra passe
      static function Connect($host = 'localhost', $db_name = 'fusion', $user = 'root', $password = '')
      {
        try {
          $conexao = new \PDO('mysql:host='.$host.';dbname='.$db_name, $user, $password);
          $conexao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
          return $conexao;
        } catch (\Throwable $th){
          return $th;
        }
      }
      
      static function Check($connection)
      {
        try {
          $connection->getAttribute(constant("PDO::ATTR_DRIVER_NAME"));
          return true;
        } catch (\Throwable $th) {
          return false;
        }
      }

      //$query: Consulta sql
      //$data: Dados de input
      static function insert($query, $data = array(), $db_select = 1)
      {
        try{
          $result = Conexao::getCon($db_select)->prepare($query);
          if(@count($data) > 0){
            foreach ($data as $key => $value){
              $result->bindParam(":$key", $data[$key], is_numeric($data[$key]) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
          }
          $result->execute(); 
          if (($result->rowCount()) > 0): return 1; else: return 0; endif;
        }catch(\Throwable $th){
          return $th;
        }
      }

      //$query: Consulta sql
      //$data: Dados de input
      static function select($query, $data = array(), $db_select = 1)
      {
        try{
          $result = Conexao::getCon($db_select)->prepare($query);
          if(@count($data) > 0){
            foreach ($data as $key => $value){
              $result->bindParam(":$key", $data[$key], is_numeric($data[$key]) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
          }
          $result->execute(); 
          if (($result->rowCount()) > 0): return $result->fetchAll(); else: return 0; endif;
        }catch(\Throwable $th) {
          return $th;
        }
      }

      //$query: Consulta sql
      //$data: Dados de input
      static function update($query, $data = array(), $db_select = 1)
      {
        try{
          $result = Conexao::getCon($db_select)->prepare($query);
          if(@count($data) > 0){
            foreach ($data as $key => $value){
              $result->bindParam(":$key", $data[$key], is_numeric($data[$key]) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
          }
          $result->execute(); 
          if (($result->rowCount()) > 0): return $result->rowCount(); else: return 0; endif;
        }catch(\Throwable $th) {
          return $th;
        }
      }

      //$query: Consulta sql
      //$data: Dados de input
      static function delete($query, $data = array(), $db_select = 1)
      {
        try{
          $result = Conexao::getCon($db_select)->prepare($query);
          if(@count($data) > 0){
            foreach ($data as $key => $value){
              $result->bindParam(":$key", $data[$key], is_numeric($data[$key]) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
          }
          $result->execute(); 
          if (($result->rowCount()) > 0): return $result->rowCount(); else: return 0; endif;
        }catch(\Throwable $th) {
          return $th;
        }
      }

      //Consulta personalizada
      static function get($db_name, $return = '*', $where = '', $limit = PHP_INT_MAX, $db_select = 1)
      {
        try{
          $SELECT  = "SELECT $return FROM $db_name";
          if(trim($where) != ''): $SELECT .= " WHERE $where"; endif;
          $SELECT .= " LIMIT $limit";
          $result = Conexao::getCon($db_select)->prepare($SELECT);
          $result->execute(); 
          if (($result->rowCount()) > 0): return $result->fetchAll(); else: return 'Nenhum resultado encontrado!'; endif;
        }catch(\Throwable $th) {
          return $th;
        }
      } 

    }

  }
?>