<?php

include_once '../conexao.php';

Class Curso{

    private $id, $cursos_id, $old_img;

    //CURSO
    private $file, $video, $titulo, $descricao, $categoria, $subcategoria, $registo, $preco, $estado;

    //PASTA
    private $folder   = '../../../../publico/img/cursos/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->cursos_id     = post('cursos_id', false) 
        ? filterVar(post('cursos_id'))  
        : DEFAULT_INT;
    
        $this->old_img     = post('old_img', false)
        ? filterVar(post('old_img'))  
        : '';

        //CURSO
        $this->titulo      = post('titulo', false) 
        ? filterVar(post('titulo'))  
        : DEFAULT_STRING;

        $this->descricao   = post('descricao', false) 
        ? trim(post('descricao'))  
        : DEFAULT_STRING;

        $this->categoria   = post('categoria', false)
        ? filterVar(post('categoria'))  
        : DEFAULT_STRING;

        $this->subcategoria = post('subcategoria', false)
        ? filterVar(post('subcategoria'))  
        : '';

        $this->preco      = is_numeric(post('preco', false))
        ? filterInt(post('preco'), 0)  
        : DEFAULT_INT;

        $this->file      = _file('img');

        $this->video     = _file('video');

        $this->registo   = date('d/m/Y');
        
        $this->estado    = is_numeric(post('estado', false))
        ? filterInt(post('estado'), 0)  
        : DEFAULT_INT;

	}

    function removeData() //Remover curso
    {
        $this->cursos_id = array_map('intval' ,explode(',', $this->cursos_id));
        $key = array_search('', $this->cursos_id);

        if($key!==false){
            unset($this->cursos_id[$key]);
        }

        $status = 1; 

        foreach ($this->cursos_id as $key => $value){
            $select = DB\Mysql::select(
                'SELECT id, imagem FROM curso WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM curso WHERE id = :id",
                    ['id' => $value]
                );
                if (is_numeric($delete) && $delete > 0){
                    @unlink($this->folder.$select[0]['imagem']);
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

    function setCurso() //Criar curso
    {
        //FILE INFO
        if ($this->file === false){

            //Publicando sem imagem
            $insert = DB\Mysql::insert(
                'INSERT curso (titulo, descricao, categoria, subcategoria, preco, registo, estado) VALUES (:titulo, :descricao, :categoria, :subcategoria, :preco, :registo, :estado)',
                [
                    'titulo'       => $this->titulo,
                    'descricao'    => $this->descricao, 
                    'categoria'    => $this->categoria,
                    'subcategoria' => $this->subcategoria,
                    'preco'        => $this->preco,
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
                    'INSERT curso (titulo, imagem, descricao, categoria, subcategoria, preco, registo, estado) VALUES (:titulo, :img, :descricao, :categoria, :subcategoria, :preco, :registo, :estado)',
                    [
                        'titulo'       => $this->titulo,
                        'descricao'    => $this->descricao, 
                        'categoria'    => $this->categoria,
                        'subcategoria' => $this->subcategoria,
                        'preco'        => $this->preco,
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

    function editCurso()
    {
        if ($this->file === false){

            //Atcualização sem imagem
            $update = DB\Mysql::update(
                'UPDATE curso SET titulo = :titulo, descricao = :descricao, categoria = :categoria, subcategoria = :subcategoria, preco = :preco, estado = :estado WHERE id = :id',
                [
                    'id'           => $this->cursos_id,
                    'titulo'       => $this->titulo,
                    'descricao'    => $this->descricao, 
                    'categoria'    => $this->categoria,
                    'subcategoria' => $this->subcategoria,
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
                    'UPDATE curso SET titulo = :titulo, imagem = :img, descricao = :descricao, categoria = :categoria, subcategoria = :subcategoria, preco = :preco, estado = :estado WHERE id = :id',
                    [
                        'id'           => $this->cursos_id,
                        'titulo'       => $this->titulo,
                        'descricao'    => $this->descricao, 
                        'categoria'    => $this->categoria,
                        'subcategoria' => $this->subcategoria,
                        'preco'        => $this->preco,
                        'img'          => $upload[0]['name'],
                        'estado'       => $this->estado,
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

    //Adicionar vídeo
    function setVideo(){

        if($this->video == false){
            return "Selecione no mínimo um vídeo para o diretório!";
        }

        $upload = Components\uploadFile::upload(
            $this->video, 
            '../../../../publico/video/cursos/', 
            ['video/mp4', 'video/mkv'], 
            (1024 * 1024 * 1024 * 1) // 1GB
        );

        if(is_array($upload) && isset($upload[0])){
            $insert = DB\Mysql::insert(
                "INSERT INTO cursos_file (id_curso, titulo, file, registo) VALUES (:id_curso, :titulo, :file, :registo)",
                [
                    'id_curso' => $this->cursos_id, 
                    'titulo'   => $this->titulo, 
                    'file'     => $upload[0]['name'], 
                    'registo'  => $this->registo
                ]
            );

            return $insert;

        }else{
            return $upload;
        }
        
    }

    function updateVideo(){
        $update = DB\Mysql::update(
            "UPDATE cursos_file SET titulo = :titulo WHERE id = :id",
            [
                'id'     => $this->cursos_id, 
                'titulo' => $this->titulo
            ]
        );
        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }
    }

    function removeVideo($file)
    {
        $delete = DB\Mysql::delete(
            "DELETE FROM cursos_file WHERE file = :file",
            [
                'file' => $file
            ]
        );
        if (is_numeric($delete) && $delete > 0){
            @unlink('../../../../publico/video/cursos/'.$file);
            return 1;
        }else{
            return 'Não foi possível remover o arquivo!';
        }
    }

}

if(post('setCurso')):

    $data = new Curso();
    eco($data->setCurso(), true);
    exit();

elseif(post('remove_curso')):

    $data = new Curso();
    eco($data->removeData(), true);
    exit();

elseif(post('editCurso')):
    
    $data = new Curso();
    eco($data->editCurso(), true);
    exit();

elseif(post('setVideo')):
    
    $data = new Curso();
    eco($data->setVideo(), true);
    exit();

elseif(post('updateVideo')):
    
    $data = new Curso();
    eco($data->updateVideo(), true);
    exit();

elseif(post('delete_video')):
    
    $data = new Curso();
    echo $data->removeVideo(filterVar(post('delete_video')), true);
    exit();

endif;

?>