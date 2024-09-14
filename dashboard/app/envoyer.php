<?php

    if(!class_exists('Conexao')):
        include_once __DIR__.'/api/conexao.php';
    endif;

    //Enviando email
    function emailSend($nome, $email, $assunto, $mensagem){

        //Get Definições de email
        $getSystemEmail = Components\jsonReader(( __DIR__.'/prefs/email.json'), true);

        try {
            $send = Components\fusionMail(
                isset($getSystemEmail['hostName'])     ? $getSystemEmail['hostName']     : '', 
                isset($getSystemEmail['hostEmail'])    ? $getSystemEmail['hostEmail']    : '',
                isset($getSystemEmail['hostPassword']) ? $getSystemEmail['hostPassword'] : '',
                array($getSystemEmail['emissorEmail'] => $getSystemEmail['emissorName']),
                array($email => $nome),
                array('assunto' => $assunto, 'mensagem' => $mensagem)
            );
            if($send == true){
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }

    }

    //Recebendo email
    if(post('send_email', false)){

        $nome     = post('nome', false)     ? filterVar(post('nome'))      : 'n/a';
        $email    = post('email', false)    ? filterVar(post('email'))     : 'n/a';
        $assunto  = post('assunto', false)  ? filterVar(post('assunto'))   : 'n/a';
        $mensagem = post('mensagem', false) ? post('mensagem')             : 'n/a';

        //Get Definições de email
        $getSystemEmail = Components\jsonReader(( __DIR__.'/prefs/email.json'), true);
        
        try {
            $send = Components\fusionMail(
                isset($getSystemEmail['hostName'])     ? $getSystemEmail['hostName']     : '', 
                isset($getSystemEmail['hostEmail'])    ? $getSystemEmail['hostEmail']    : '',
                isset($getSystemEmail['hostPassword']) ? $getSystemEmail['hostPassword'] : '',
                array($email => $nome),
                array($getSystemEmail['receptorEmail'] => $getSystemEmail['receptorName']),
                array('assunto' => $assunto, 'mensagem' => $mensagem)
            );
            if($send == true){
                return eco(1);
            }else{
                return eco(0);
            }
        } catch (\Throwable $th) {
            return eco(0);
        }
    }
        
?>