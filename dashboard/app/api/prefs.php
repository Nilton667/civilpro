<?php

include_once 'conexao.php';

Class Prefs{

    //Sistema
    private $systemNome, $systemFont, $systemLang, $systemHttp, $systemTheme, $systemImage;

    //Email
    private $hostName, $hostEmail, $hostPort, $hostPassword, $emissorName, $emissorEmail, $receptorName, $receptorEmail;

    function __construct()
    {
        //Sistema
        $this->systemNome  = post('systemNome', false)
        ? filterVar(post('systemNome'))  
        : '';

        $this->systemFont  = post('systemFont', false)
        ? filterInt(post('systemFont'))  
        : 0;

        $this->systemLang  = post('systemLang', false)
        ? filterVar(post('systemLang'))  
        : 'pt-pt';

        $this->systemHttp  = post('systemHttp', false)
        ? filterInt(post('systemHttp'))  
        : 0;

        $this->systemTheme  = post('systemTheme', false)
        ? filterVar(post('systemTheme'))  
        : "light";

        $this->systemImage  = post('systemImage', false)
        ? filterVar(post('systemImage'))  
        : "01.jpg";

        //Email
        $this->hostName  = post('hostName', false)
        ? filterVar(post('hostName'))  
        : '';

        $this->hostEmail  = post('hostEmail', false)
        ? filterVar(post('hostEmail'))  
        : '';

        $this->hostPort  = is_numeric(post('hostPort', false))
        ? filterInt(post('hostPort'))  
        : '587';

        $this->hostPassword = post('hostPassword', false)
        ? filterVar(post('hostPassword'))  
        : '';

        /////////Emissor / Receptor
        
        $this->emissorName = post('emissorName', false)
        ? filterVar(post('emissorName'))  
        : '';

        $this->emissorEmail = post('emissorEmail', false)
        ? filterVar(post('emissorEmail'))  
        : '';

        $this->receptorName = post('receptorName', false)
        ? filterVar(post('receptorName'))  
        : '';

        $this->receptorEmail = post('receptorEmail', false)
        ? filterVar(post('receptorEmail'))  
        : '';
    }

    function setSystem()
    {
        try {
            $data = array(
                'nome'  => $this->systemNome, 
                'font'  => $this->systemFont, 
                'lang'  => $this->systemLang, 
                'http'  => $this->systemHttp, 
                'theme' => $this->systemTheme, 
                'image' => $this->systemImage
            );

            $data    = json_encode($data);

            $compare = file_get_contents('../prefs/system.json');

            if($compare == $data){
                return 'Nenhuma alteração efectuada!';
            }

            file_put_contents('../prefs/system.json', $data);
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    function setEmail()
    {
        try {
            $data = array(
                'hostName'      => $this->hostName, 
                'hostEmail'     => $this->hostEmail, 
                'hostPort'      => $this->hostPort, 
                'hostPassword'  => $this->hostPassword,
                'emissorName'   => $this->emissorName,
                'emissorEmail'  => $this->emissorEmail,
                'receptorName'  => $this->receptorName,
                'receptorEmail' => $this->receptorEmail
            );

            $data    = json_encode($data);

            $compare = file_get_contents('../prefs/email.json');

            if($compare == $data){
                return 'Nenhuma alteração efectuada!';
            }

            file_put_contents('../prefs/email.json', $data);
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    
    function testEmail()
    {
        include_once '../envoyer.php';
        $sendEmail = emailSend(
            'Dashboard', 
            $this->hostEmail, 
            'Dashboard', 
            'Teste de envio!'
        );
        if($sendEmail != true):
            return 0;
        endif;
        return 1;
    }

}

if(post('setSystemPrefs')):

    $data = new Prefs();
    eco($data->setSystem(), true);
    exit();

elseif(post('setEmailPrefs')):

    $data = new Prefs();
    eco($data->setEmail(), true);
    exit();

elseif(post('test_email')):

    $data = new Prefs();
    eco($data->testEmail(), true);
    exit();

endif;

?>