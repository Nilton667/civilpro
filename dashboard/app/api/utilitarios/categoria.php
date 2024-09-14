<?php

include_once '../conexao.php';

Class Categoria{

    private $id, $categoria, $descricao, $old_categoria, $categoria_id, $file, $old_image, $blog, $curso, $loja, $imoveis, $registo;

    //PASTA
    private $folder   = '../../../../publico/img/categorias/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->categoria_id   = post('categoria_id', false)
        ? filterVar(post('categoria_id'))  
        : DEFAULT_INT;

        $this->categoria      = post('categoria', false)
        ? filterVar(post('categoria'))  
        : DEFAULT_STRING;

        $this->old_categoria  = post('old_categoria', false)
        ? filterVar(post('old_categoria'))  
        : DEFAULT_STRING;

        $this->descricao      = post('descricao', false)
        ? filterVar(post('descricao'))  
        : DEFAULT_STRING;

        $this->old_image      = post('old_image', false)
        ? filterVar(post('old_image'))  
        : DEFAULT_STRING;

        $this->blog           = post('blog', false)
        ? filterInt(post('blog'))
        : DEFAULT_INT; 
        
        $this->curso          = post('curso', false)
        ? filterInt(post('curso'))
        : DEFAULT_INT; 
        
        $this->loja           = post('loja', false)
        ? filterInt(post('loja'))
        : DEFAULT_INT; 
        
        $this->imoveis        = post('imoveis', false)
        ? filterInt(post('imoveis'))
        : DEFAULT_INT;

        $this->registo   = date('d/m/Y');

        $this->file      = _file('img');
    }
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM categoria WHERE categoria = :categoria AND descricao = :descricao',
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
                'INSERT categoria (categoria, descricao, blog, curso, loja, imoveis, registo) VALUES (:categoria, :descricao, :blog, :curso, :loja, :imoveis, :registo)',
                [
                    'categoria' => $this->categoria,
                    'descricao' => $this->descricao,
                    'blog'      => $this->blog,
                    'curso'     => $this->curso,
                    'loja'      => $this->loja,
                    'imoveis'   => $this->imoveis, 
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
                    'INSERT categoria (categoria, imagem, descricao, blog, curso, loja, imoveis, registo) VALUES (:categoria, :imagem, :descricao, :blog, :curso, :loja, :imoveis, :registo)',
                    [
                        'categoria' => $this->categoria,
                        'imagem'    => $upload[0]['name'],
                        'descricao' => $this->descricao,
                        'blog'      => $this->blog,
                        'curso'     => $this->curso,
                        'loja'      => $this->loja,
                        'imoveis'   => $this->imoveis,
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
                'UPDATE categoria SET categoria = :categoria, descricao = :descricao, blog = :blog, curso = :curso, loja = :loja, imoveis = :imoveis WHERE id = :id',
                [
                    'id' => $this->categoria_id, 
                    'categoria' => $this->categoria,
                    'descricao' => $this->descricao,
                    'blog'      => $this->blog,
                    'curso'     => $this->curso,
                    'loja'      => $this->loja,
                    'imoveis'   => $this->imoveis
                ]
            );

            if(is_numeric($update) && $update > 0){
                foreach (['subcategoria', 'artigos', 'blog'] as $key => $table) {
                    DB\Mysql::update(
                        "UPDATE $table SET categoria = :categoria WHERE categoria = :old_categoria",
                        ['old_categoria' => $this->old_categoria, 'categoria' => $this->categoria]
                    );   
                }
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
                    'UPDATE categoria SET categoria = :categoria, imagem = :imagem, descricao = :descricao, blog = :blog, curso = :curso, loja = :loja, imoveis = :imoveis WHERE id = :id',
                    [
                        'id'        => $this->categoria_id, 
                        'categoria' => $this->categoria,
                        'imagem'    => $upload[0]['name'],
                        'descricao' => $this->descricao,
                        'blog'      => $this->blog,
                        'curso'     => $this->curso,
                        'loja'      => $this->loja,
                        'imoveis'   => $this->imoveis
                    ]
                );
                @unlink($this->folder.$this->old_image);
                if(is_numeric($update) && $update > 0){
                    foreach (['subcategoria', 'artigos', 'blog'] as $key => $table) {
                        DB\Mysql::update(
                            "UPDATE $table SET categoria = :categoria WHERE categoria = :old_categoria",
                            ['old_categoria' => $this->old_categoria, 'categoria' => $this->categoria]
                        );   
                    }
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
                'SELECT id, imagem FROM categoria WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM categoria WHERE id = :id",
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