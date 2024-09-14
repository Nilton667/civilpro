<?php

  //Easy print
  function eco($data, $json = false){
    if(is_array($data) || is_object($data)){
      if($json): print_r(json_encode($data));  else: print_r($data);  endif;
    }else if(is_bool($data)){
      if($json): var_dump(json_encode($data)); else: var_dump($data); endif;
    }else{
      if($json): echo json_encode($data);      else: echo $data;      endif;
    }
  }

  //Filtro de dados
  //filtrar texto
  function filterVar($var){
    return filter_var(trim(strip_tags($var)), FILTER_SANITIZE_STRING);
  }

  //Validar email
  function filterEmail($email){
    return filter_var(trim(strip_tags($email)), FILTER_SANITIZE_EMAIL);
  }
  
  //filtrar tipo inteiro
  function filterInt($int, $minValue = -1000000){
    if(is_numeric(trim($int))){
      if($minValue > trim($int)):
        return $minValue;
      endif;
      return filter_var(trim(strip_tags($int)), FILTER_SANITIZE_NUMBER_INT);
    }
    return false;
  }

  //filtrar tipo float
  function filterFloat($float){
    return filter_var(trim(strip_tags($float)), FILTER_SANITIZE_NUMBER_FLOAT);
  }

  //filtrar tipo booleano
  function filterBool($float){
    return filter_var(trim(strip_tags($float)), FILTER_VALIDATE_BOOLEAN);
  }

  //Variaveis globais
  //Verificando um get
  function get($get, $null = true){
    if(isset($_GET[$get]) && trim($_GET[$get]) != ''){
      return trim($_GET[$get]);
    }else{
      if(isset($_GET[$get]) && $null == true):
        return true;
      endif;
      return false;
    }
  }

  //Verificando um post
  function post($post, $null = true){
    if(isset($_POST[$post]) && trim($_POST[$post]) != ''){
      return trim($_POST[$post]);
    }else{
      if(isset($_POST[$post]) && $null == true):
        return true;
      endif;
      return false;
    }
  }

  //Verificando entrada de arquivos
  function _file($file){
    if(isset($_FILES[$file]) && count($_FILES[$file]) > 0){
      return $_FILES[$file];
    }else{
      return false;
    } 
  }

  //Verificação global
  function request($request, $null = true)
  {
    if(isset($_REQUEST[$request]) && trim($_REQUEST[$request]) != ''){
      return trim($_REQUEST[$request]);
    }else{
      if(isset($_REQUEST[$request]) && $null == true):
        return true;
      endif;
      return false;
    } 
  }

  //Tools
  //Limitar texto
  //$text: Texto a limitar
  //$limit: Quantidade de caracteres a apresentar
  function limitarTexto($text, $limit = 100){
    $count     = strlen($text);
    if ($count >= $limit) {      
      $text    = substr($text, 0, strrpos(substr($text, 0, $limit), ' ')) . '...';
      return $text;
    }
    else{
      return $text;
    }
  }

?>