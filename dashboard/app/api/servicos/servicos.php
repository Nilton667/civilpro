<?php

include_once '../conexao.php';

Class Servicos{

    private $id, $blog_id, $old_img;

    //POST
    private $file, $nome, $descricao, $categoria, $preco, $registo, $estado;

    //PASTA
    private $folder   = '../../../../publico/img/servicos/';

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
        $this->nome        = post('nome', false) 
        ? filterVar(post('nome'))  
        : DEFAULT_STRING;

        $this->descricao   = post('descricao', false) 
        ? trim(post('descricao'))  
        : DEFAULT_STRING;

        $this->categoria   = post('categoria', false)
        ? filterVar(post('categoria'))  
        : DEFAULT_STRING;

        $this->preco      = is_numeric(post('preco', false))
        ? filterInt(post('preco'), 0)  
        : DEFAULT_INT;

        $this->file      = _file('img');

        $this->registo   = date('d/m/Y');
        
        $this->estado    = is_numeric(post('estado', false))
        ? filterInt(post('estado'), 0)  
        : DEFAULT_INT;

	}

    function setService() //Registar Serviço
    {
        //FILE INFO
        if ($this->file === false){

            //Publicando sem imagem
            $insert = DB\Mysql::insert(
                'INSERT servicos (id_adm, nome, descricao, categoria, preco, registo, estado) VALUES (:id_adm, :nome, :descricao, :categoria, :preco, :registo, :estado)',
                [
                    'id_adm'       => $this->id,
                    'nome'       => $this->nome,
                    'descricao'    => $this->descricao,
                    'categoria'    => $this->categoria,
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
                    'INSERT servicos (id_adm, nome, imagem, descricao, categoria, preco, registo, estado) VALUES (:id_adm, :nome, :img, :descricao, :categoria, :preco, :registo, :estado)',
                    [
                        'id_adm'       => $this->id,
                        'nome'       => $this->nome, 
                        'descricao'    => $this->descricao,
                        'categoria'    => $this->categoria,
                        'estado'       => $this->estado,
                        'preco'        => $this->preco,
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

    function editService()
    {
        if ($this->file === false){

            //Actualização sem imagem
            $update = DB\Mysql::update(
                'UPDATE servicos SET nome = :nome, descricao = :descricao, categoria = :categoria, preco = :preco, estado = :estado WHERE id = :id',
                [
                    'id'           => $this->blog_id,
                    'nome'         => $this->nome,
                    'descricao'    => $this->descricao,
                    'categoria'    => $this->categoria,
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
                    'UPDATE servicos SET nome = :nome, imagem = :img, descricao = :descricao, categoria = :categoria, preco = :preco, estado = :estado WHERE id = :id',
                    [
                        'id'           => $this->blog_id,
                        'nome'         => $this->nome,
                        'descricao'    => $this->descricao,
                        'categoria'    => $this->categoria,
                        'preco'        => $this->preco,
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

    function removeData() //Remover Serviço
    {
        $this->blog_id = array_map('intval' ,explode(',', $this->blog_id));
        $key = array_search('', $this->blog_id);

        if($key!==false){
            unset($this->blog_id[$key]);
        }

        $status = 1; 

        foreach ($this->blog_id as $key => $value){

            $select = DB\Mysql::select(
                'SELECT id, imagem FROM servicos WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM servicos WHERE id = :id",
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

}

if(post('setService')):

    $data = new Servicos();
    eco($data->setService(), true);
    exit();

elseif(post('remove_service')):

    $data = new Servicos();
    eco($data->removeData(), true);
    exit();

elseif(post('editService')):
    
    $data = new Servicos();
    eco($data->editService(), true);
    exit();

endif;

?>