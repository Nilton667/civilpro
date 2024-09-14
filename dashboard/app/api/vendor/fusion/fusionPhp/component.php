<?php
  //Componentes
  namespace Components{
    
    //Enviar email
    function fusionMail($smtp, $email, $password, $emissor = array(), $receptor = array(), $data = array()){
      require_once __DIR__.'/email/vendor/autoload.php';
      $transport = (new \Swift_SmtpTransport($smtp, 25)) //Ex: mail.mulembatechnology.com
        ->setUsername($email)
        ->setPassword($password)
      ;

      // Create the Mailer using your created Transport
      $mailer = new \Swift_Mailer($transport);

      // Create a message
      $message = (new \Swift_Message($data['assunto'])) //Assunto
      ->setFrom($emissor) //Email & nome do emissor
      ->setTo($receptor)
        //Ex: ->setFrom([$email => 'Mulemba Technology']) //Email & nome do emissor
        //Ex: ->setTo(['nilton667@gmail.com', 'other@domain.org' => 'A name']) //Array email e nome do receptor
        ->setBody($data['mensagem'], 'text/html') //Mensagem
        ;

      // Send the message
      if(!$mailer->send($message, $error)){
        return $error;
      }else{
        return true;
      }
    }

    //$sessionName: Nome da sessão
    //$data: Dados a armazenar
    function setSession($sessionName, $data)
    {
      $session = serialize($data);
      if(($_SESSION[$sessionName] = $session)):
        return true;
      else:
        return false;
      endif;
    }

    //$sessionName: Nome da sessão
    //$index: Operador de retorno
    //$action: Acção ao executar 0 -> false; 1 -> exit() // Caso contrario retorna o valor
    //$json: metodo de retorno false ->normal; true -> json
    function getSession($sessionName, $index = '', $action = 0, $json = false){
      if(isset($_SESSION[$sessionName])){
        $session = unserialize($_SESSION[$sessionName]);

        if(isset($session[$index]) && $index != ''):
          return $session[$index];
        elseif($index != ''):
          if($action == 0){
            return false;
          }
          eco($json ? json_encode( 'Índice não encontrado!') : 'Índice não encontrado!');
          exit();
        else:
          return $session;
        endif;

      }else{
        if($action == 0){
          return false;
        }
        eco($json ? json_encode('Sem sessão iniciada!') : 'Sem sessão iniciada!');
        exit();
      }
    }

    //$jsonFile: Arquivo json
    //$assoc: Quando TRUE, o object retornado será convertido em array associativo
    function jsonReader($jsonFile, $assoc = false)
    {
      if(is_file($jsonFile)){
        return json_decode(file_get_contents($jsonFile), $assoc);
      }else{
        return false;
      }
    }

    //LocalStorage
    class Storage{

      //Storage em php são nada + nada menos do que uma manipulação de cookies 
      //Sendo estes completamente inacessíveis pelo javascript caso preferir
      //$name: Nome do cookie
      static function setItem($name, $value, $https = false, $httponly = true)
      {
        setcookie($name, serialize($value), time() + 60 * 60 * 24 * 30, '/', $_SERVER['SERVER_NAME'], $https, $httponly);
      }

      //$name: Nome do cookie
      static function getItem($name)
      {
        if(isset($_COOKIE[$name])){
          return unserialize($_COOKIE[$name]);
        }
        return false;
      }

      //$name: Nome do cookie
      static function removeItem($name)
      {
        if(isset($_COOKIE[$name])){
          unset($_COOKIE[$name]);
        }
        return false;
      }

    }

    class uploadFile{

      //Upload de arquivo
      static function upload($file, $dir, $format, $size){
        $numFile    = count(array_filter($file['name']));
        
        //Requisitos
        $permite    = $format;
        $maxSize    = $size;
        
        //Pasta
        if(!is_dir($dir)):
          @mkdir($dir, '0777', true);
        endif;

        //Mensagens de erro
        $msg        = array();
        $errorMsg   = array(
          1 => 'O arquivo no upload é maior do que o limite definido!',
          2 => 'O arquivo ultrapassa o limite de tamanho definido!',
          3 => 'O upload foi feito parcialmente!',
          4 => 'Não foi possível terminar o upload!',
          5 => 'Formato não suportado!'
        );
        
        if($numFile <= 0){
          return 'Selecione no mínimo um arquivo para o diretório!';
        }else{

          $dataImage = [];

          for($i = 0; $i < $numFile; $i++):
            
            $name   = $file['name'][$i];
            $type   = $file['type'][$i];
            $size   = $file['size'][$i];
            $error  = $file['error'][$i];
            $tmp    = $file['tmp_name'][$i];
            
            $extensao = @end(explode('.', $name));
            $novoNome = date('dmyHis').mt_rand(0, 99).'.'.$extensao;
            
            if($error != 0):
              return $errorMsg[$error];
            
            elseif(!in_array($type, $permite)):
              return $errorMsg[5];
            
            elseif($size > $maxSize):
              return $errorMsg[2];
            
            else:
              
              if(move_uploaded_file($tmp, $dir.'/'.$novoNome)){
                $dataImage[$i] = ['status' => 1, 'name' => $novoNome];
                if(($i+1) >= $numFile){
                  return $dataImage;
                }
              }else{
                return 0;
              }

            endif;

          endfor;

        }
      }

    }

  }
?>