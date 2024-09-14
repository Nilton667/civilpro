<?php

include_once '../conexao.php';

Class Imoveis{

    private $id, $imoveis_id, $old_img;

    //POST
    private $file, $video, $titulo, $subtitulo, $preco, $youtube, $descricao, $categoria, $subcategoria, $pavimento, $tamanho, $dormitorio, $area_construida, $banheiro, $garagem, $registo, $estado;

    //PASTA
    private $folder   = '../../../../publico/img/imoveis/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->imoveis_id     = post('imoveis_id', false) 
        ? filterVar(post('imoveis_id'))  
        : DEFAULT_INT;
    
        $this->old_img     = post('old_img', false)
        ? filterVar(post('old_img'))  
        : '';

        $this->preco      = is_numeric(post('preco', false))
        ? filterInt(post('preco'), 0)  
        : DEFAULT_INT;

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

        $this->pavimento = post('pavimento', false)
        ? filterVar(post('pavimento'))  
        : '';

        $this->tamanho = post('tamanho', false)
        ? filterVar(post('tamanho'))  
        : '';

        $this->dormitorio = post('dormitorio', false)
        ? filterVar(post('dormitorio'))  
        : '';

        $this->area_construida = post('area_construida', false)
        ? filterVar(post('area_construida'))  
        : '';

        $this->banheiro        = post('banheiro', false)
        ? filterVar(post('banheiro'))  
        : '';

        $this->garagem = post('garagem', false)
        ? filterVar(post('garagem'))  
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
                'INSERT imoveis (id_adm, titulo, subtitulo, youtube, descricao, categoria, subcategoria, pavimento, tamanho, dormitorio, area_construida, banheiro, garagem,  registo, estado, preco) VALUES (:id_adm, :titulo, :subtitulo, :youtube, :descricao, :categoria, :subcategoria, :pavimento, :tamanho, :dormitorio, :area_construida, :banheiro, :garagem, :registo, :estado, :preco)',
                [
                    'id_adm'       => $this->id,
                    'titulo'       => $this->titulo,
                    'subtitulo'    => $this->subtitulo, 
                    'youtube'      => $this->youtube,
                    'descricao'    => $this->descricao,
                    'categoria'    => $this->categoria,
                    'subcategoria' => $this->subcategoria,
                    'pavimento'    => $this->pavimento,
                    'tamanho'      => $this->tamanho,
                    'dormitorio'   => $this->dormitorio,
                    'banheiro'     => $this->banheiro,
                    'garagem'      => $this->garagem,
                    'area_construida' => $this->area_construida,
                    'estado'       => $this->estado,
                    'preco'        => $this->preco,
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
                    'INSERT imoveis (id_adm, titulo, subtitulo, imagem, youtube, descricao, categoria, subcategoria, pavimento, tamanho, dormitorio, area_construida, banheiro, garagem, registo, estado, preco) VALUES (:id_adm, :titulo, :subtitulo, :img, :youtube, :descricao, :categoria, :subcategoria, :pavimento, :tamanho, :dormitorio, :area_construida, :banheiro, :garagem, :registo, :estado, :preco)',
                    [
                        'id_adm'       => $this->id,
                        'titulo'       => $this->titulo,
                        'subtitulo'    => $this->subtitulo, 
                        'youtube'      => $this->youtube,
                        'descricao'    => $this->descricao,
                        'categoria'    => $this->categoria,
                        'subcategoria' => $this->subcategoria,
                        'pavimento'    => $this->pavimento,
                        'tamanho'      => $this->tamanho,
                        'dormitorio'   => $this->dormitorio,
                        'banheiro'     => $this->banheiro,
                        'garagem'      => $this->garagem,
                        'area_construida' => $this->area_construida,
                        'estado'       => $this->estado,
                        'img'          => $upload[0]['name'],
                        'preco'        => $this->preco,
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
                'UPDATE imoveis SET titulo = :titulo, subtitulo = :subtitulo, youtube = :youtube, descricao = :descricao, categoria = :categoria, subcategoria = :subcategoria, pavimento = :pavimento, tamanho = :tamanho, dormitorio = :dormitorio, area_construida = :area_construida, banheiro = :banheiro, garagem = :garagem, estado = :estado, preco = :preco WHERE id = :id',
                [
                    'id'           => $this->imoveis_id,
                    'titulo'       => $this->titulo,
                    'subtitulo'    => $this->subtitulo, 
                    'youtube'      => $this->youtube,
                    'descricao'    => $this->descricao,
                    'categoria'    => $this->categoria,
                    'subcategoria' => $this->subcategoria,
                    'pavimento'    => $this->pavimento,
                    'tamanho'      => $this->tamanho,
                    'dormitorio'   => $this->dormitorio,
                    'banheiro'     => $this->banheiro,
                    'garagem'      => $this->garagem,
                    'area_construida' => $this->area_construida,
                    'preco'        => $this->preco,
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
                    'UPDATE imoveis SET titulo = :titulo, subtitulo = :subtitulo, imagem = :img, youtube = :youtube, descricao = :descricao, categoria = :categoria, subcategoria = :subcategoria, pavimento = :pavimento, tamanho = :tamanho, dormitorio = :dormitorio, area_construida = :area_construida, banheiro = :banheiro, garagem = :garagem, estado = :estado, preco = :preco WHERE id = :id',
                    [
                        'id'           => $this->imoveis_id,
                        'titulo'       => $this->titulo,
                        'subtitulo'    => $this->subtitulo, 
                        'youtube'      => $this->youtube,
                        'descricao'    => $this->descricao,
                        'categoria'    => $this->categoria,
                        'subcategoria' => $this->subcategoria,
                        'pavimento'    => $this->pavimento,
                        'tamanho'      => $this->tamanho,
                        'dormitorio'   => $this->dormitorio,
                        'banheiro'     => $this->banheiro,
                        'garagem'      => $this->garagem,
                        'area_construida' => $this->area_construida,
                        'estado'       => $this->estado,
                        'preco'        => $this->preco,
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

        $folder = '../../../../publico/video/imoveis/';
        $upload = Components\uploadFile::upload(
            $this->video, 
            $folder, 
            ['video/mp4', 'video/mkv'], 
            (1024 * 1024 * 1024 * 1) // 1GB
        );

        if(is_array($upload) && isset($upload[0])){
            $update = DB\Mysql::update(
                "UPDATE imoveis SET video = :video WHERE id = :id",
                ['id' => $this->imoveis_id, 'video' => $upload[0]['name']]
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
            "UPDATE imoveis SET video = :empty WHERE video = :video",
            ['empty' => $empty, 'video' => $video]
        );
        if(is_numeric($update) && $update > 0){
            @unlink('../../../../publico/video/imoveis/'.$video);
            return 1;
        }else{
            return 'Falha ao remover o vídeo!';
        }
    }

    function removeData() //Remover post
    {
        $this->imoveis_id = array_map('intval' ,explode(',', $this->imoveis_id));
        $key = array_search('', $this->imoveis_id);

        if($key!==false){
            unset($this->imoveis_id[$key]);
        }

        $status = 1; 

        foreach ($this->imoveis_id as $key => $value){

            $select = DB\Mysql::select(
                'SELECT id, imagem, video FROM imoveis WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM imoveis WHERE id = :id",
                    ['id' => $value]
                );
                if (is_numeric($delete) && $delete > 0){
                    @unlink($this->folder.$select[0]['imagem']);
                    @unlink('../../../../publico/video/imoveis/'.$select[0]['video']);
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

    function setImage() //Adicionar imagem
    {
        //FILE INFO
        if ($this->file === false):
            return "Selecione no mínimo uma imagem para o diretório!";
        endif;


        $upload = Components\uploadFile::upload(
            $this->file, 
            $this->folder, 
            ['image/png', 'image/jpg', 'image/jpeg'], 
            (1024 * 1024 * 2) // 2MB
        );

        if(is_array($upload)){

            foreach ($upload as $key => $value) {
                DB\Mysql::insert(
                    'INSERT imoveis_imagem (id_imovel, imagem, registo) VALUES (:id_imovel, :img, :registo)',
                    [
                        'id_imovel' => $this->imoveis_id,
                        'img'       => $value['name'],
                        'registo'   => $this->registo,
                    ]
                );
            }
            
            return 1;

        }else{
            return $upload;
        }
    }

    function removeImage($image)
    {
        $delete = DB\Mysql::delete(
            "DELETE FROM imoveis_imagem WHERE imagem = :imagem",
            [
                'imagem' => $image,
            ]
        );
        if(is_numeric($delete) && $delete > 0){
            if(unlink($this->folder.$image)):
                return 1;
            else:
                return 0;
            endif;
        }else{
            return $delete;
        }
    }

    function changeImage()
    {
        $select = DB\Mysql::select(
            'SELECT imagem FROM imoveis WHERE id = :imoveis_id',
            ['imoveis_id' => $this->imoveis_id]
        );
        if(is_array($select)){
            $update = DB\Mysql::update(
                "UPDATE imoveis_imagem SET imagem = :img WHERE imagem = :old_img",
                ['old_img' => $this->old_img, 'img' => $select[0]['imagem']]
            );
            if(is_numeric($update) && $update > 0){
                $update = DB\Mysql::update(
                    "UPDATE imoveis SET imagem = :old_img WHERE imagem = :img AND id = :imoveis_id",
                    ['imoveis_id' => $this->imoveis_id, 'old_img' => $this->old_img, 'img' => $select[0]['imagem']]
                );
                return 1;
            }else{
                return 'falha ao alterar a imagem!';
            }

        }else{
            return 'Falha ao verificar a imagem!';
        }

    }

}

if(post('setImovel')):

    $data = new Imoveis();
    eco($data->setPost(), true);
    exit();

elseif(post('remove_post')):

    $data = new Imoveis();
    eco($data->removeData(), true);
    exit();

elseif(post('editImovel')):
    
    $data = new Imoveis();
    eco($data->editPost(), true);
    exit();

elseif(post('setVideo')):
    
    $data = new Imoveis();
    eco($data->setVideo(), true);
    exit();

elseif(post('delete_video')):
    
    $data = new Imoveis();
    echo $data->removeVideo(filterVar(post('delete_video')), true);
    exit();

elseif(post('add_image')):

    $data = new Imoveis();
    eco($data->setImage(), true);
    exit();

elseif(post('delete_image')):

    $data = new Imoveis();
    eco($data->removeImage(filterVar(post('delete_image'))), true);
    exit();

elseif(post('change_image')):

    $data = new Imoveis();
    eco($data->changeImage(), true);
    exit();

endif;

?>