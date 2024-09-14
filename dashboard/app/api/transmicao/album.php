<?php

include_once '../conexao.php';

Class Album{

    private $id, $nome, $descricao, $old_nome, $album_id, $file, $old_image, $data_lancamento, $registo;

    //PASTA
    private $folder   = '../../../../publico/img/album/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->album_id   = post('album_id', false)
        ? filterVar(post('album_id'))  
        : DEFAULT_INT;

        $this->nome         = post('nome', false)
        ? filterVar(post('nome'))  
        : DEFAULT_STRING;

        $this->old_nome     = post('old_nome', false)
        ? filterVar(post('old_nome'))  
        : DEFAULT_STRING;

        $this->descricao    = post('descricao', false)
        ? filterVar(post('descricao'))  
        : DEFAULT_STRING;

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
            'SELECT id FROM podcast_album WHERE nome = :nome AND descricao = :descricao',
            [
                'nome'      => $this->nome, 
                'descricao' => $this->descricao
            ]
        ))){ return 'Esta artista já se encontra registado!'; }else{ return 0; }
    }

    function setAlbum() //Criar artista
    {
        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        if ($this->file === false){
            $insert = DB\Mysql::insert(
                'INSERT podcast_album (nome, descricao, data_lancamento, registo) VALUES (:nome, :descricao, :data_lancamento, :registo)',
                [
                    'nome'            => $this->nome,
                    'descricao'       => $this->descricao,
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
                    'INSERT podcast_album (nome, imagem, descricao, data_lancamento, registo) VALUES (:nome, :imagem, :descricao, :data_lancamento, :registo)',
                    [
                        'nome'            => $this->nome,
                        'imagem'          => $upload[0]['name'],
                        'descricao'       => $this->descricao,
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

    function editAlbum() //Editar artista
    {

        if ($this->file === false){
            $update = DB\Mysql::update(
                'UPDATE podcast_album SET nome = :nome, descricao = :descricao, data_lancamento = :data_lancamento WHERE id = :id',
                [
                    'id'              => $this->album_id, 
                    'nome'            => $this->nome,
                    'descricao'       => $this->descricao,
					'data_lancamento' => $this->data_lancamento
                ]
            );

            if(is_numeric($update) && $update > 0){
                return 1;
            }else{
                return $update;
            }

        }else{
            
            //Alterando imagem do artista
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );
            
            if(is_array($upload) && isset($upload[0])){
                $update = DB\Mysql::update(
                    'UPDATE podcast_album SET nome = :nome, imagem = :imagem, descricao = :descricao, data_lancamento = :data_lancamento WHERE id = :id',
                    [
                        'id'              => $this->album_id, 
                        'nome'            => $this->nome,
                        'imagem'          => $upload[0]['name'],
                        'descricao'       => $this->descricao,
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

    function removeData() //Remover album
    {
        $this->album_id = array_map('intval' ,explode(',', $this->album_id));
        $key = array_search('', $this->album_id);

        if($key!==false){
            unset($this->album_id[$key]);
        }

        $status = 1; 

        foreach ($this->album_id as $key => $value){
            $select = DB\Mysql::select(
                'SELECT id, imagem FROM podcast_album WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM podcast_album WHERE id = :id",
                    ['id' => $value]
                );
                if($delete <= 0){
                    $status = 0;
                    break;
                }else{
                    @unlink($this->folder.$select[0]['imagem']);
                }
            }else{
                $status++;
                break;
            }
        }

        return $status;
    }

}

if(post('add_album')):

    $data = new Album();
    eco($data->setAlbum(), true);
    exit();

elseif(post('edit_album')):

    $data = new Album();
    eco($data->editAlbum(), true);
    exit();

elseif(post('remove_album')):

    $data = new Album();
    eco($data->removeData(), true);
    exit();

endif;

?>