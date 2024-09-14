<?php
    
include_once '../conexao.php';

class Chat
{
    private $id_usuario, $mensagem, $file, $registo;
    private $folder       = '../../../../publico/img/chat/';
    private $localFolder  = '../publico/img/chat/';

    function __construct()
    {
        $this->id_usuario    = post('id_usuario')
        ? filterInt(post('id_usuario'))  
        : DEFAULT_INT;

        $this->mensagem      = post('mensagem')
        ? filterVar(post('mensagem'))  
        : DEFAULT_STRING;

        $this->registo       = date('d/m/Y').' ás '.date('H:i');

        $this->file          = _file('file');
    }

    //Enviar mensagem
    function send()
    {
        $insert = DB\Mysql::insert(
            'INSERT INTO chat (id_para, mensagem, registo) VALUES (:id_usuario, :mensagem, :registo)',
            [
               'id_usuario' => $this->id_usuario,
               'mensagem'   => $this->mensagem,
               'registo'    => $this->registo,
            ]
        );

        if(is_numeric($insert) && $insert > 0){
            eco("<div class='campo_de'><p>$this->mensagem</p><span>$this->registo</span></div>");
        }else{
            return 0;
        }
    }

    //Enviar arquivo
    function sendFile(){
        if ($this->file === false):
            return "Selecione no mínimo um arquivo para o diretório!";
        endif;

        $upload = Components\uploadFile::upload(
            $this->file, 
            $this->folder, 
            ['image/png', 'image/jpg', 'image/jpeg', 'application/pdf'], 
            (1024 * 1024 * 2) // 2MB
        );

        if(is_array($upload)){
            $data = "";
            foreach ($upload as $key => $value) {
                $insert = DB\Mysql::insert(
                    'INSERT INTO chat (id_para, file, registo) VALUES (:id_usuario, :file, :registo)',
                    [
                        'id_usuario' => $this->id_usuario, 
                        'file'       => $value['name'],
                        'registo'    => $this->registo
                    ]
                );

                if(is_numeric($insert) && $insert > 0){

                    if($value['name']    != '' && is_file($this->folder.$value['name'])){
                        $extensao    = @end(explode('.', $value['name']));
                        if($extensao == 'png' || $extensao == 'jpg' || $extensao == 'jpeg'){
                            $data  .= '<li class="campo_de" id="chat-user'.$this->id_usuario.'-msg"><p><a class="image-link" href="'.$this->localFolder.$value['name'].'"><img style="max-width: 100%; border-radius: 8px;" src="'.$this->localFolder.$value['name'].'"></a></p><span>'.$this->registo.'</span></li>';
                        }else{
                            $data  .= '<li class="campo_de" id="chat-user'.$this->id_usuario.'-msg"><p><a style="color: #fff!important;" href="'.$this->localFolder.$value['name'].'" target="_blank"><i class="las la-file" style="font-size: 1em;"></i> '.$value['name'].'</a></p><span>'.$this->registo.'</span></li>';
                        }
                    }
    
                }else{
                    return 0;
                }
            }

            return $data;

        }else{

            return $upload;

        }
    }

    //Carregar mensagens
    function load()
    {
        $retorno = '';

        $selecionar = Conexao::getCon(1)->prepare("SELECT * FROM chat WHERE id_de = ? AND id_para = 0 OR id_para = ? AND id_de = 0");
        $selecionar->execute(array($this->id_usuario, $this->id_usuario)
        );

        $numero_de_mensagens = $selecionar->rowCount();
        $mensagem      = '';
        if ($numero_de_mensagens > 0) {
            while ($ft = $selecionar->fetchObject()) {
                $nome  = Conexao::getCon(1)->prepare("SELECT nome FROM usuarios WHERE id = ?");
                $nome->execute(array($ft->id_de));
                $name  = $nome->fetchObject();
                
                if($ft->file != '' && is_file($this->folder.$ft->file) && $ft->id_de == 0){
                    $extensao = @end(explode('.', $ft->file)); 
                    if($extensao == 'png' || $extensao == 'jpg' || $extensao == 'jpeg'){
                        $mensagem .= '<div class="campo_de" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p><a class="image-link" href="'.$this->localFolder.$ft->file.'"><img style="max-width: 100%; border-radius: 8px;" src="'.$this->localFolder.$ft->file.'"></a></p><span>'.$ft->registo.'</span></div>';
                    }else{
                        $mensagem .= '<div class="campo_de" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p><a style="color: #fff!important;" href="'.$this->localFolder.$ft->file.'" target="_blank"><i class="las la-file" style="font-size: 1em;"></i> '.$ft->file.'</a></p><span>'.$ft->registo.'</span></div>';
                    }

                }else if($ft->file != '' && is_file($this->folder.$ft->file)){
                    $extensao = @end(explode('.', $ft->file)); 
                    if($extensao == 'png' || $extensao == 'jpg' || $extensao == 'jpeg'){
                        $mensagem .= '<div class="campo_para" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p><a class="image-link" href="'.$this->localFolder.$ft->file.'"><img style="max-width: 100%; border-radius: 8px;" src="'.$this->localFolder.$ft->file.'"></a></p><span>'.$ft->registo.'</span></div>';
                    }else{
                        $mensagem .= '<div class="campo_para" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p><a style="color: #444!important;" href="'.$this->localFolder.$ft->file.'" target="_blank"><i class="las la-file" style="font-size: 1em;"></i> '.$ft->file.'</a></p><span>'.$ft->registo.'</span></div>';
                    }
                }else if($ft->id_de == 0){
                    $mensagem .= '<div class="campo_de" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p>'.$ft->mensagem.'</p><span>'.$ft->registo.'</span></div>';
                }else{
                    $mensagem .= '<div class="campo_para" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p>'.$ft->mensagem.'</p><span>'.$ft->registo.'</span></div>';
                }
            }
        }else{
            $mensagem .= '<div class="campo_chat lead"><p>Seja o primeiro a inicia conversa!</p></div>';
        }

        $retorno = $mensagem;
   
        return $retorno;
    }

    //Carregar mensagens não lidas
    function loadSms()
    {
        $retorno = '';

        $selecionar = Conexao::getCon(1)->prepare("SELECT * FROM chat WHERE id_de = ? AND lido = 0");
        $selecionar->execute(array(
            $this->id_usuario
            )
        );

        $numero_de_mensagens = $selecionar->rowCount();
        $mensagem      = '';

        if ($numero_de_mensagens > 0) {
            while ($ft = $selecionar->fetchObject()) {
                $nome  = Conexao::getCon(1)->prepare("SELECT nome FROM usuarios WHERE id = ?");
                $nome->execute(array($ft->id_de));
                $name  = $nome->fetchObject();
                
                if($ft->file != '' && is_file($this->folder.$ft->file) && $ft->id_de == 0){
                    $extensao = @end(explode('.', $ft->file)); 
                    if($extensao == 'png' || $extensao == 'jpg' || $extensao == 'jpeg'){
                        $mensagem .= '<div class="campo_de" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p><a class="image-link" href="'.$this->localFolder.$ft->file.'"><img style="max-width: 100%; border-radius: 8px;" src="'.$this->localFolder.$ft->file.'"></a></p><span>'.$ft->registo.'</span></div>';
                    }else{
                        $mensagem .= '<div class="campo_de" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p><a style="color: #fff!important;" href="'.$this->localFolder.$ft->file.'" target="_blank"><i class="las la-file" style="font-size: 1em;"></i> '.$ft->file.'</a></p><span>'.$ft->registo.'</span></div>';
                    }

                }else if($ft->file != '' && is_file($this->folder.$ft->file)){
                    $extensao = @end(explode('.', $ft->file)); 
                    if($extensao == 'png' || $extensao == 'jpg' || $extensao == 'jpeg'){
                        $mensagem .= '<div class="campo_para" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p><a class="image-link" href="'.$this->localFolder.$ft->file.'"><img style="max-width: 100%; border-radius: 8px;" src="'.$this->localFolder.$ft->file.'"></a></p><span>'.$ft->registo.'</span></div>';
                    }else{
                        $mensagem .= '<div class="campo_para" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p><a style="color: #444!important;" href="'.$this->localFolder.$ft->file.'" target="_blank"><i class="las la-file" style="font-size: 1em;"></i> '.$ft->file.'</a></p><span>'.$ft->registo.'</span></div>';
                    }
                }else if($ft->id_de == 0){
                    $mensagem .= '<div class="campo_de" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p>'.$ft->mensagem.'</p><span>'.$ft->registo.'</span></div>';
                }else{
                    $mensagem .= '<div class="campo_para" id="chat-user'.$ft->id_de.'-msg'.$ft->id.'"><p>'.$ft->mensagem.'</p><span>'.$ft->registo.'</span></div>';
                }
            }
        }

        $retorno = $mensagem;
        $this->updateState();
        return $retorno;
    }

    //Actualizar status
    function updateState()
    {
        $update = Conexao::getCon(1)->prepare("UPDATE chat SET lido = '1' WHERE id_de = ?");
        $update->execute(array($this->id_usuario));
    }
}

if(post('send')):

    $data = new Chat();
    eco($data->send());
    exit();

elseif(post('sendFile')):

    $data = new Chat();
    eco($data->sendFile(), true);
    exit();

elseif(post('load')):

    $data = new Chat();
    eco($data->load(), true);
    exit();

elseif(post('loadSms')):

    $data = new Chat();
    eco($data->loadSms(), true);
    exit();

elseif(post('updateState')):

    $data = new Chat();
    eco($data->updateState(), true);
    exit();

endif;

?>