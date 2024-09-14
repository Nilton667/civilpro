<?php

include_once '../conexao.php';

Class Transmicao{

    private $id, $id_album, $id_artista, $titulo, $descricao, $podcast_id, $file, $old_image, $origem, $video, $data_lancamento, $registo;

    //PASTA
    private $folder       = '../../../../publico/img/podcast/';
    private $foldervideo  = '../../../../publico/transmicao/podcast/video/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->podcast_id   = post('podcast_id', false)
        ? filterVar(post('podcast_id'))  
        : DEFAULT_INT;

        $this->id_artista   = post('id_artista', false)
        ? filterVar(post('id_artista'))  
        : DEFAULT_INT;

        $this->id_album     = post('id_album', false)
        ? filterVar(post('id_album'))  
        : DEFAULT_INT;

        $this->titulo       = post('titulo', false)
        ? filterVar(post('titulo'))  
        : DEFAULT_STRING;

        $this->descricao    = post('descricao', false)
        ? filterVar(post('descricao'))  
        : DEFAULT_STRING;

        $this->origem       = post('origem', false)
        ? filterVar(post('origem'))  
        : '';

        $this->video        = post('video', false)
        ? trim(post('video')) 
        : '';

        $this->old_image    = post('old_image', false)
        ? filterVar(post('old_image'))  
        : DEFAULT_STRING;

        $this->data_lancamento = post('data_lancamento', false)
        ? filterVar(post('data_lancamento'))  
        : DEFAULT_STRING;

        $this->registo   = date('d/m/Y');

        $this->file      = _file('img');
    }
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM podcast_video WHERE titulo = :titulo AND descricao = :descricao',
            [
                'titulo'    => $this->titulo, 
                'descricao' => $this->descricao
            ]
        ))){ return 'Esta podcast já se encontra registado!'; }else{ return 0; }
    }

    function setTransmicao() //Criar podcast
    {
        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        if ($this->file === false){
            $insert = DB\Mysql::insert(
                'INSERT podcast_video (id_album, id_artista, titulo, descricao, origem, video, data_lancamento, registo) VALUES (:id_album, :id_artista, :titulo, :descricao, :origem, :video, :data_lancamento, :registo)',
                [
					'id_album'        => $this->id_album,
					'id_artista'      => $this->id_artista,
                    'titulo'          => $this->titulo,
                    'descricao'       => $this->descricao,
					'origem'          => $this->origem, 
					'video'           => $this->video,
					'data_lancamento' => $this->data_lancamento,
                    'registo'         => $this->registo
                ]
            );
            return $insert;
        }else{
            //Adicionando imagem ao artista
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );

            if(is_array($upload) && isset($upload[0])){
                $insert = DB\Mysql::insert(
                    'INSERT podcast_video (id_album, id_artista, titulo, imagem, descricao, origem, video, data_lancamento, registo) VALUES (:id_album, :id_artista, :titulo, :imagem, :descricao, :origem, :video, :data_lancamento, :registo)',
                    [
						'id_album'        => $this->id_album,
						'id_artista'      => $this->id_artista,
						'titulo'          => $this->titulo,
						'imagem'          => $upload[0]['name'],
						'descricao'       => $this->descricao,
						'origem'          => $this->origem, 
						'video'           => $this->video,
						'data_lancamento' => $this->data_lancamento,
						'registo'         => $this->registo
                    ]
                );
                return $insert;
            }else{
                return $upload;
            }
        }
    }

    function editTransmicao() //Editar podcast
    {

        if ($this->file === false){
            $update = DB\Mysql::update(
                'UPDATE podcast_video SET id_album = :id_album, id_artista = :id_artista, titulo = :titulo, descricao = :descricao, origem = :origem, video = :video, data_lancamento = :data_lancamento WHERE id = :id',
                [
                    'id'              => $this->podcast_id, 
					'id_album'        => $this->id_album,
					'id_artista'      => $this->id_artista,
					'titulo'          => $this->titulo,
					'descricao'       => $this->descricao,
					'origem'          => $this->origem, 
					'video'           => $this->video,
					'data_lancamento' => $this->data_lancamento
                ]
            );

            if(is_numeric($update) && $update > 0){
                return 1;
            }else{
                return $update;
            }

        }else{
            
            //Alterando imagem do podcast
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );
            
            if(is_array($upload) && isset($upload[0])){
                $update = DB\Mysql::update(
                    'UPDATE podcast_video SET id_album = :id_album, id_artista = :id_artista, titulo = :titulo, imagem = :imagem, descricao = :descricao, origem = :origem, video = :video, data_lancamento = :data_lancamento WHERE id = :id',
                    [
						'id'              => $this->podcast_id, 
						'id_album'        => $this->id_album,
						'id_artista'      => $this->id_artista,
						'titulo'          => $this->titulo,
						'imagem'          => $upload[0]['name'],
						'descricao'       => $this->descricao,
						'origem'          => $this->origem, 
						'video'           => $this->video,
						'data_lancamento' => $this->data_lancamento
                    ]
                );
                @unlink($this->folder.$this->old_image);
                if(is_numeric($update) && $update > 0){
                    return 1;
                }else{
                    return $update;
                }
            }else{
                return $upload;
            }
        }

    }

    function removeData() //Remover podcast
    {
        $this->podcast_id = array_map('intval' ,explode(',', $this->podcast_id));
        $key = array_search('', $this->podcast_id);

        if($key!==false){
            unset($this->podcast_id[$key]);
        }

        $status = 1; 

        foreach ($this->podcast_id as $key => $value){
            $select = DB\Mysql::select(
                'SELECT id, imagem FROM podcast_video WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM podcast_video WHERE id = :id",
                    ['id' => $value]
                );
                if($delete <= 0){
                    $status = 0;
                    break;
                }else{
                    @unlink($this->folder.$select[0]['imagem']);
					@unlink($this->foldervideo.$select[0]['video']);
                }
            }else{
                $status++;
                break;
            }
        }

        return $status;
    }

}

if(post('add_transmicao')):

    $data = new Transmicao();
    eco($data->setTransmicao(), true);
    exit();

elseif(post('edit_transmicao')):

    $data = new Transmicao();
    eco($data->editTransmicao(), true);
    exit();

elseif(post('remove_transmicao')):

    $data = new Transmicao();
    eco($data->removeData(), true);
    exit();

endif;

?>