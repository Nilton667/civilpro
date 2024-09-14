<?php

include_once '../conexao.php';

Class Categoria{

    private $id, $categoria, $descricao, $categoria_id, $file, $old_image, $registo;
    
    //PASTA
    private $folder   = '../../../../publico/img/servicos/categorias/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->categoria_id   = post('categoria_id', false)
        ? filterVar(post('categoria_id'))  
        : DEFAULT_INT;

        $this->categoria      = post('categoria', false)
        ? filterVar(post('categoria'))  
        : DEFAULT_STRING;

        $this->descricao      = post('descricao', false)
        ? filterVar(post('descricao'))  
        : DEFAULT_STRING;

        $this->old_image      = post('old_image', false)
        ? filterVar(post('old_image'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');

        $this->file      = _file('img');
    }
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM servicos_categoria WHERE categoria = :categoria AND descricao = :descricao',
            [
                'categoria' => $this->categoria, 
                'descricao' => $this->descricao
            ]
        ))){ return 'Esta categoria já se encontra registada!'; }else{ return 0; }
    }

    function setCategoria() //Criar categoria
    {
        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        if ($this->file === false){
            $insert = DB\Mysql::insert(
                'INSERT servicos_categoria (categoria, descricao, registo) VALUES (:categoria, :descricao, :registo)',
                [
                    'categoria' => $this->categoria,
                    'descricao' => $this->descricao, 
                    'registo'   => $this->registo
                ]
            );
            return $insert;
        }else{
            //Adicionando imagem a categoria
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );

            if(is_array($upload) && isset($upload[0])){
                $insert = DB\Mysql::insert(
                    'INSERT servicos_categoria (categoria, imagem, descricao, registo) VALUES (:categoria, :imagem, :descricao, :registo)',
                    [
                        'categoria' => $this->categoria,
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

    function editCategoria() //Editar categoria
    {
        
        if ($this->file === false){
            $update = DB\Mysql::update(
                'UPDATE servicos_categoria SET categoria = :categoria, descricao = :descricao WHERE id = :id',
                [
                    'id' => $this->categoria_id, 
                    'categoria' => $this->categoria,
                    'descricao' => $this->descricao,
                ]
            );

            if(is_numeric($update) && $update > 0){
                return 1;
            }else{
                return $update;
            }

        }else{
            //Alterando imagem da categoria
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );
            
            if(is_array($upload) && isset($upload[0])){
                $update = DB\Mysql::update(
                    'UPDATE servicos_categoria SET categoria = :categoria, imagem = :imagem, descricao = :descricao WHERE id = :id',
                    [
                        'id'        => $this->categoria_id, 
                        'categoria' => $this->categoria,
                        'imagem'    => $upload[0]['name'],
                        'descricao' => $this->descricao,
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

    function removeData() //Remover categoria
    {
        $this->categoria_id = array_map('intval' ,explode(',', $this->categoria_id));
        $key = array_search('', $this->categoria_id);

        if($key!==false){
            unset($this->categoria_id[$key]);
        }

        $status = 1; 

        foreach ($this->categoria_id as $key => $value){
            $select = DB\Mysql::select(
                'SELECT id, imagem FROM servicos_categoria WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM servicos_categoria WHERE id = :id",
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

if(post('add_categoria')):

    $data = new Categoria();
    eco($data->setCategoria(), true);
    exit();

elseif(post('edit_categoria')):

    $data = new Categoria();
    eco($data->editCategoria(), true);
    exit();

elseif(post('remove_categoria')):

    $data = new Categoria();
    eco($data->removeData(), true);
    exit();

endif;

?>