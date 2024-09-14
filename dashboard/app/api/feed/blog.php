<?php

include_once '../conexao.php';

Class Blog{

    private $id, $blog_id, $old_img;

    //POST
    private $file, $video, $titulo, $subtitulo, $youtube, $descricao, $categoria, $subcategoria, $registo, $estado;

    //PASTA
    private $folder   = '../../../../publico/img/posts/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->blog_id     = post('blog_id', false) 
        ? filterVar(post('blog_id'))  
        : DEFAULT_INT;
    
        $this->old_img     = post('old_img', false)
        ? filterVar(post('old_img'))  
        : '';

        //POST
        $this->titulo      = post('titulo', false) 
        ? filterVar(post('titulo'))  
        : DEFAULT_STRING;

        $this->subtitulo   = post('subtitulo', false) 
        ? filterVar(post('subtitulo'))  
        : DEFAULT_STRING;

        $this->youtube     = post('youtube', false) 
        ? filterVar(post('youtube'))  
        : '';

        $this->descricao   = post('descricao', false) 
        ? trim(post('descricao'))  
        : DEFAULT_STRING;

        $this->categoria   = post('categoria', false)
        ? filterVar(post('categoria'))  
        : DEFAULT_STRING;

        $this->subcategoria = post('subcategoria', false)
        ? filterVar(post('subcategoria'))  
        : '';

        $this->file      = _file('img');

        $this->video     = _file('video');

        $this->registo   = date('d/m/Y');
        
        $this->estado    = is_numeric(post('estado', false))
        ? filterInt(post('estado'), 0)  
        : DEFAULT_INT;

	}

    function setPost() //Criar publicação
    {
        //FILE INFO
        if ($this->file === false){

            //Publicando sem imagem
            $insert = DB\Mysql::insert(
                'INSERT blog (id_adm, titulo, subtitulo, youtube, descricao, categoria, subcategoria, registo, estado) VALUES (:id_adm, :titulo, :subtitulo, :youtube, :descricao, :categoria, :subcategoria, :registo, :estado)',
                [
                    'id_adm'       => $this->id,
                    'titulo'       => $this->titulo,
                    'subtitulo'    => $this->subtitulo, 
                    'youtube'      => $this->youtube,
                    'descricao'    => $this->descricao,
                    'categoria'    => $this->categoria,
                    'subcategoria' => $this->subcategoria,
                    'estado'       => $this->estado,
                    'registo'      => $this->registo
                ]
            );
            
            return $insert;

        }else{

            //Publicando com imagem
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );
    
            if(is_array($upload) && isset($upload[0])){
                $insert = DB\Mysql::insert(
                    'INSERT blog (id_adm, titulo, subtitulo, imagem, youtube, descricao, categoria, subcategoria, registo, estado) VALUES (:id_adm, :titulo, :subtitulo, :img, :youtube, :descricao, :categoria, :subcategoria, :registo, :estado)',
                    [
                        'id_adm'       => $this->id,
                        'titulo'       => $this->titulo,
                        'subtitulo'    => $this->subtitulo, 
                        'youtube'      => $this->youtube,
                        'descricao'    => $this->descricao,
                        'categoria'    => $this->categoria,
                        'subcategoria' => $this->subcategoria,
                        'estado'       => $this->estado,
                        'img'          => $upload[0]['name'],
                        'registo'      => $this->registo
                    ]
                );
                
                return $insert;

            }else{
                return $upload;
            }
            //Final da publicação com imagem

        }

    }

    function editPost()
    {
        if ($this->file === false){

            //Actualização sem imagem
            $update = DB\Mysql::update(
                'UPDATE blog SET titulo = :titulo, subtitulo = :subtitulo, youtube = :youtube, descricao = :descricao, categoria = :categoria, subcategoria = :subcategoria, estado = :estado WHERE id = :id',
                [
                    'id'           => $this->blog_id,
                    'titulo'       => $this->titulo,
                    'subtitulo'    => $this->subtitulo, 
                    'youtube'      => $this->youtube,
                    'descricao'    => $this->descricao,
                    'categoria'    => $this->categoria,
                    'subcategoria' => $this->subcategoria,
                    'estado'       => $this->estado,
                ]
            );
            if(is_numeric($update) && $update > 0){
                return 1;
            }else{
                return $update;
            }

        }else{

            //Actualização com imagem
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );
    
            if(is_array($upload) && isset($upload[0])){

                $update = DB\Mysql::update(
                    'UPDATE blog SET titulo = :titulo, subtitulo = :subtitulo, imagem = :img, youtube = :youtube, descricao = :descricao, categoria = :categoria, subcategoria = :subcategoria, estado = :estado WHERE id = :id',
                    [
                        'id'           => $this->blog_id,
                        'titulo'       => $this->titulo,
                        'subtitulo'    => $this->subtitulo, 
                        'youtube'      => $this->youtube,
                        'descricao'    => $this->descricao,
                        'categoria'    => $this->categoria,
                        'subcategoria' => $this->subcategoria,
                        'estado'       => $this->estado,
                        'img'          => $upload[0]['name'],
                    ]
                );
                if(is_numeric($update) && $update > 0){
                    if(is_file($this->folder.$this->old_img)){ 
                        @unlink($this->folder.$this->old_img); 
                    }
                    return 1;
                }else{
                    return $update;
                }

            }else{
               return $upload; 
            }
            //Final da actualização com imagem
        }

    }

    function setVideo() //Adicionar e altera vídeo
    {

        if($this->video == false){
            return "Selecione no mínimo um vídeo para o diretório!";
        }

        $folder = '../../../../publico/video/posts/';
        $upload = Components\uploadFile::upload(
            $this->video, 
            $folder, 
            ['video/mp4', 'video/mkv'], 
            (1024 * 1024 * 1024 * 1) // 1GB
        );

        if(is_array($upload) && isset($upload[0])){
            $update = DB\Mysql::update(
                "UPDATE blog SET video = :video WHERE id = :id",
                ['id' => $this->blog_id, 'video' => $upload[0]['name']]
            );
            if(is_numeric($update) && $update > 0){
                if(is_file($folder.$this->old_img)){ 
                    @unlink($folder.$this->old_img); 
                }
                return 1;
            }else{
                return $update;
            }
        }else{
            return $upload;
        }
    }

    function removeVideo($video)
    {
        $empty = '';
        $update = DB\Mysql::update(
            "UPDATE blog SET video = :empty WHERE video = :video",
            ['empty' => $empty, 'video' => $video]
        );
        if(is_numeric($update) && $update > 0){
            @unlink('../../../../publico/video/posts/'.$video);
            return 1;
        }else{
            return 'Falha ao remover o vídeo!';
        }
    }

    function removeData() //Remover post
    {
        $this->blog_id = array_map('intval' ,explode(',', $this->blog_id));
        $key = array_search('', $this->blog_id);

        if($key!==false){
            unset($this->blog_id[$key]);
        }

        $status = 1; 

        foreach ($this->blog_id as $key => $value){

            $select = DB\Mysql::select(
                'SELECT id, imagem, video FROM blog WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM blog WHERE id = :id",
                    ['id' => $value]
                );
                if (is_numeric($delete) && $delete > 0){
                    @unlink($this->folder.$select[0]['imagem']);
                    @unlink('../../../../publico/video/posts/'.$select[0]['video']);
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

if(post('setPost')):

    $data = new Blog();
    eco($data->setPost(), true);
    exit();

elseif(post('remove_post')):

    $data = new Blog();
    eco($data->removeData(), true);
    exit();

elseif(post('editPost')):
    
    $data = new Blog();
    eco($data->editPost(), true);
    exit();

elseif(post('setVideo')):
    
    $data = new Blog();
    eco($data->setVideo(), true);
    exit();

elseif(post('delete_video')):
    
    $data = new Blog();
    echo $data->removeVideo(filterVar(post('delete_video')), true);
    exit();

endif;

?>