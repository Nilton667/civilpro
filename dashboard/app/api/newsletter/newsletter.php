<?php
    
include_once '../conexao.php';

class Newsletter
{
    private $id, $email, $registo, $user_id;
    
    function __construct()
    {

        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->email    = post('email', false)
        ? filterEmail(post('email'))  
        : DEFAULT_STRING;

        $this->user_id  = post('user_id', false) 
        ? filterVar(post('user_id'))  
        : DEFAULT_INT;

        $this->registo  = date('d/m/Y');

        $this->emissor  = post('emissor', false)
        ? filterVar(post('emissor'))  
        : DEFAULT_STRING;

        $this->assunto  = post('assunto', false) 
        ? filterVar(post('assunto'))  
        : DEFAULT_STRING;

        $this->mensagem = post('mensagem', false) 
        ? filterVar(post('mensagem'))  
        : DEFAULT_STRING;
    }

    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM newsletter WHERE email = :email',
            ['email' => $this->email]
        ))){ return 'Esta conta de email ja se encontra registada!'; }else{ return 0; }
    }

    function add() //Adicionar email
    {

        if ($this->email == ''):
            return 'Insira um e-mail valido!';
        endif;

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT INTO newsletter (email, registo) VALUES (:email, :registo)',
            ['email' => $this->email, 'registo' => $this->registo]
        );
        return $insert;

    }

    function removeData() //Remover email
    {
        $this->user_id = array_map('intval' ,explode(',', $this->user_id));
        $key = array_search('', $this->user_id);

        if($key!==false){
            unset($this->user_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM newsletter WHERE id IN(".implode(',', $this->user_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

    //Enviar email
    function sendMail(){
        $select = DB\Mysql::select(
            'SELECT email FROM newsletter ORDER BY id DESC LIMIT 500',
            []
        );

        if(is_array($select) && count($select) > 0){
            //Declara função
            function emailSend($destinarios = array(), $de, $assunto, $mensagem){

                //Get Definições de email
                $getSystemEmail = Components\jsonReader((__DIR__.'/../../prefs/email.json'), true);
                try {
                    $send = Components\fusionMail(
                        isset($getSystemEmail['hostName'])     ? $getSystemEmail['hostName']     : '', 
                        isset($getSystemEmail['hostEmail'])    ? $getSystemEmail['hostEmail']    : '',
                        isset($getSystemEmail['hostPassword']) ? $getSystemEmail['hostPassword'] : '',
                        array($getSystemEmail['emissorEmail'] => $de),
                        $destinarios,
                        array('assunto' => $assunto, 'mensagem' => $mensagem)
                    );
                    if($send == true){
                        return true;
                    }else{
                        return false;
                    }
                }catch (\Throwable $th){
                    return false;
                }
        
            }

            $sendList = [];
            foreach ($select as $key => $value) {
                array_push($sendList, $value['email']);
            }

            //Ativar envio
            $sendEmail = emailSend($sendList, $this->emissor, $this->assunto, $this->mensagem);

            if($sendEmail != true):
                return 'Falha de envio verifique as suas definições!';
            endif;

            return 1;
        }else{
            return 'Nenhuma conta de email registada!';
        }

    }

}

if(post('add_newsletter')):

    $data = new Newsletter();
    eco($data->add(), true);
    exit();

elseif(post('remove_newsletter')):

    $data = new Newsletter();
    eco($data->removeData(), true);
    exit();

elseif(post('send')):

    $data = new Newsletter();
    eco($data->sendMail(), true);
    exit();

endif;

?>