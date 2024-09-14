<?php

include_once 'conexao.php';

Class Anuncio{

    private $id, $anuncio_id, $anuncio, $url, $orientacao, $registo, $estado;

    //PASTA
    private $folder   = '../../../publico/img/anuncios/';

    function __construct()
    {
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->anuncio_id  = post('anuncio_id', false) 
        ? filterVar(post('anuncio_id'))
        : DEFAULT_INT;
            
        $this->anuncio     = post('anuncio', false)
        ? filterVar(post('anuncio'))  
        : DEFAULT_STRING;

        $this->url         = post('url', false)
        ? filterVar(post('url'))  
        : '';

        $this->orientacao  = post('orientacao', false)
        ? filterVar(post('orientacao'))  
        : '';

        $this->registo     = date('d/m/Y');

        $this->file        = _file('img');

        $this->estado      = is_numeric(post('estado', false)) 
        ? filterInt(post('estado'), 0)  
        : DEFAULT_INT;

        $this->folder .= strtolower($this->orientacao).'/';

    }

    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM anuncio WHERE anuncio = :anuncio AND estado = :estado AND url = :url AND orientacao = :orientacao',
            [
                'anuncio'    => $this->anuncio, 
                'url'        => $this->url, 
                'orientacao' => $this->orientacao, 
                'estado'     => $this->estado
            ]
        ))){ return 'Este anúncio já se encontra registado!'; }else{ return 0; }
    }

    function setAnuncio() //Criar anuncio
    {
        
        if ($this->file === false):
            return "Selecione no mínimo uma imagem para o diretório!";
        endif;

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $upload = Components\uploadFile::upload(
            $this->file, 
            $this->folder, 
            ['image/png', 'image/jpg', 'image/jpeg'], 
            (1024 * 1024 * 2) // 2MB
        );

        if(is_array($upload) && isset($upload[0])){
            $insert = DB\Mysql::insert(
                'INSERT anuncio (anuncio, imagem, url, orientacao, registo, estado) VALUES (:anuncio, :imagem, :url, :orientacao, :registo, :estado)',
                [
                    'anuncio'    => $this->anuncio, 
                    'imagem'     => $upload[0]['name'],
                    'url'        => $this->url,
                    'orientacao' => $this->orientacao,
                    'estado'     => $this->estado,
                    'registo'    => $this->registo
                ]
            );

            return $insert;

        }else{
            return $upload;
        }
    }

    function editAnuncio() //Editar anúncio
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $select = DB\Mysql::select(
            'SELECT id, orientacao, imagem FROM anuncio WHERE id = :id',
            [
                'id' => $this->anuncio_id,
            ]
        );

        $update = DB\Mysql::update(
            'UPDATE anuncio SET anuncio = :anuncio, estado = :estado, url = :url, orientacao = :orientacao WHERE id = :id',
            [
                'id'         => $this->anuncio_id, 
                'url'        => $this->url,
                'anuncio'    => $this->anuncio,
                'orientacao' => $this->orientacao,
                'estado'     => $this->estado
            ]
        );

        if(is_numeric($update) && $update > 0){
            if(is_array($select) && $select[0]['orientacao'] != $this->orientacao){
                @rename(
                    '../../../publico/img/anuncios/'.$select[0]['orientacao'].'/'.$select[0]['imagem'],
                    '../../../publico/img/anuncios/'.strtolower($this->orientacao).'/'.$select[0]['imagem']
                );
            }
            return 1;
        }else{
            return $update;
        }

    }

    function removeData() //Remover anúncio
    {

        $this->anuncio_id = array_map('intval' ,explode(',', $this->anuncio_id));
        $key = array_search('', $this->anuncio_id);

        if($key!==false){
            unset($this->anuncio_id[$key]);
        }

        $status = 1; 

        foreach ($this->anuncio_id as $key => $value) {
            $select = DB\Mysql::select(
                'SELECT id, imagem, orientacao FROM anuncio WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM anuncio WHERE id = :id",
                    ['id' => $value]
                );
                if (is_numeric($delete) && $delete > 0){
                    @unlink($this->folder.strtolower($select[0]['orientacao']).'/'.$select[0]['imagem']);
                }else{
                    $status++;
                    break;
                }
            }else{
                $status++;
                break;
            }

        }

        return $status;
        
    }

}

if(post('add_anuncio')):

    $data = new Anuncio();
    eco($data->setAnuncio(), true);
    exit();

elseif(post('edit_nuncio')):

    $data = new Anuncio();
    eco($data->editAnuncio(), true);
    exit();

elseif(post('remove_anuncio')):

    $data = new Anuncio();
    eco($data->removeData(), true);
    exit();

endif;

?>