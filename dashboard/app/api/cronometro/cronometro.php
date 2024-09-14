<?php

include_once '../conexao.php';

Class Cronometro{

    private $id, $file, $titulo, $data, $hora, $cronometro_id, $registo;

    //PASTA
    private $folder   = '../../../../publico/cronometro/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->cronometro_id   = post('cronometro_id', false)
        ? filterVar(post('cronometro_id'))  
        : DEFAULT_INT;

        $this->file            = _file('file');

        $this->titulo          = post('titulo', false)
        ? filterVar(post('titulo'))  
        : DEFAULT_STRING;

        $this->data  = post('data', false)
        ? filterVar(post('data'))  
        : DEFAULT_STRING;

        $this->hora  = post('hora', false)
        ? filterVar(post('hora'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}

    function setCronometro() //Criar cronometro
    {

        //FILE INFO
        if ($this->file === false):
            return "Selecione no mínimo uma imagem ou vídeo para o diretório!";
        endif;

        $upload = Components\uploadFile::upload(
            $this->file, 
            $this->folder, 
            ['image/png', 'image/jpg', 'image/jpeg', 'video/mp4', 'video/mkv'], 
            (1024 * 1024 * 1024 * 1) // 1GB
        );

        if(is_array($upload) && isset($upload[0])){
            $insert = DB\Mysql::insert(
                'INSERT cronometro (titulo, file, data, hora, registo) VALUES (:titulo, :file, :data, :hora, :registo)',
                [
                    'titulo'  => $this->titulo,
                    'file'    => $upload[0]['name'],
                    'data'    => $this->data,
                    'hora'    => $this->hora, 
                    'registo' => $this->registo
                ]
            );

            return $insert;      

        }else{
            return $upload;
        }      
    }

    //Remover cronometro
    function removeData(){
        $this->cronometro_id = array_map('intval' ,explode(',', $this->cronometro_id));
        $key = array_search('', $this->cronometro_id);

        if($key!==false){
            unset($this->cronometro_id[$key]);
        }

        $status = 1; 

        foreach ($this->cronometro_id as $key => $value){
            $select = DB\Mysql::select(
                'SELECT id, file FROM cronometro WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM cronometro WHERE id = :id",
                    ['id' => $value]
                );
                if($delete <= 0){
                    $status = 0;
                    break;
                }else{
                    @unlink($this->folder.$select[0]['file']);
                }
            }else{
                $status++;
                break;
            }
        }

        return $status;

    }

}

if(post('add_cronometro')):

    $data = new Cronometro();
    eco($data->setCronometro(), true);
    exit();

elseif(post('remove_Cronometro')):

    $data = new Cronometro();
    eco($data->removeData(), true);
    exit();

endif;

?>