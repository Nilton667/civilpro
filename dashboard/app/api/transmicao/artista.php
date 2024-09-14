<?php

include_once '../conexao.php';

Class Artista{

    private $id, $nome, $descricao, $old_nome, $artista_id, $file, $old_image, $registo;

    //PASTA
    private $folder   = '../../../../publico/img/artistas/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->artista_id   = post('artista_id', false)
        ? filterVar(post('artista_id'))  
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

        $this->registo   = date('d/m/Y');

        $this->file      = _file('img');
    }
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM podcast_artista WHERE nome = :nome AND descricao = :descricao',
            [
                'nome'      => $this->nome, 
                'descricao' => $this->descricao
            ]
        ))){ return 'Esta artista já se encontra registado!'; }else{ return 0; }
    }

    function setArtista() //Criar artista
    {
        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        if ($this->file === false){
            $insert = DB\Mysql::insert(
                'INSERT podcast_artista (nome, descricao, registo) VALUES (:nome, :descricao, :registo)',
                [
                    'nome'      => $this->nome,
                    'descricao' => $this->descricao,
                    'registo'   => $this->registo
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
                    'INSERT podcast_artista (nome, imagem, descricao, registo) VALUES (:nome, :imagem, :descricao, :registo)',
                    [
                        'nome'      => $this->nome,
                        'imagem'    => $upload[0]['name'],
                        'descricao' => $this->descricao,
                        'registo'   => $this->registo
                    ]
                );
                return $insert;
            }else{
                return $upload;
            }
        }
    }

    function editArtista() //Editar artista
    {

        if ($this->file === false){
            $update = DB\Mysql::update(
                'UPDATE podcast_artista SET nome = :nome, descricao = :descricao WHERE id = :id',
                [
                    'id'        => $this->artista_id, 
                    'nome'      => $this->nome,
                    'descricao' => $this->descricao
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
                    'UPDATE podcast_artista SET nome = :nome, imagem = :imagem, descricao = :descricao WHERE id = :id',
                    [
                        'id'        => $this->artista_id, 
                        'nome'      => $this->nome,
                        'imagem'    => $upload[0]['name'],
                        'descricao' => $this->descricao
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

    function removeData() //Remover artista
    {
        $this->artista_id = array_map('intval' ,explode(',', $this->artista_id));
        $key = array_search('', $this->artista_id);

        if($key!==false){
            unset($this->artista_id[$key]);
        }

        $status = 1; 

        foreach ($this->artista_id as $key => $value){
            $select = DB\Mysql::select(
                'SELECT id, imagem FROM podcast_artista WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM podcast_artista WHERE id = :id",
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

if(post('add_artista')):

    $data = new Artista();
    eco($data->setArtista(), true);
    exit();

elseif(post('edit_artista')):

    $data = new Artista();
    eco($data->editArtista(), true);
    exit();

elseif(post('remove_artista')):

    $data = new Artista();
    eco($data->removeData(), true);
    exit();

endif;

?>