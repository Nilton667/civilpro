<?php

include_once '../conexao.php';

Class Artigo{

    private $id, $artigo_id;
    //Old image quando changeImage é chamado ele é 
    //adicionado como a image que ira substituir a image padrão
    private $old_img;

    //POST
    private $file, $nome, $quantidade, $preco, $descricao, $categoria, $subcategoria, $cor, $marca, $tamanho, $registo, $estado;

    //PASTA
    private $folder = '../../../../publico/img/artigos/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->artigo_id  = post('artigo_id', false)
        ? filterVar(post('artigo_id')) 
        : DEFAULT_INT;

        $this->old_img    = post('old_img', false)
        ? filterVar(post('old_img'))  
        : '';

        //POST
        $this->nome       = post('nome', false)
        ? filterVar(post('nome'))  
        : DEFAULT_STRING;

        $this->quantidade = is_numeric(post('quantidade', false))
        ? filterInt(post('quantidade'), 0)  
        : DEFAULT_INT;
        
        $this->preco      = is_numeric(post('preco', false))
        ? filterInt(post('preco'), 0)  
        : DEFAULT_INT;

        $this->descricao  = post('descricao', false)
        ? post('descricao')
        : DEFAULT_STRING;

        $this->categoria  = post('categoria', false)
        ? filterVar(post('categoria'))  
        : '';
        
        $this->subcategoria = post('subcategoria', false)
        ? filterVar(post('subcategoria'))  
        : '';

        $this->cor        = post('cor', false)
        ? filterVar(post('cor'))  
        : '';

        $this->marca      = post('marca', false)
        ? filterVar(post('marca'))  
        : '';

        $this->tamanho    = post('tamanho', false)
        ? filterVar(post('tamanho'))  
        : '';

        $this->file       = _file('img');

        $this->registo    = date('d/m/Y');

        $this->estado     = is_numeric(post('estado', false))
        ? filterInt(post('estado'), 0)  
        : DEFAULT_INT;

	}

    function deleteImage($id)
    {
        $select = DB\Mysql::select(
            'SELECT id, imagem FROM artigos_imagem WHERE id_artigo = :id',
            ['id' => $id]
        );
        if(is_array($select)){
            foreach ($select as $key => $value) {
                $delete = DB\Mysql::delete(
                    "DELETE FROM artigos_imagem WHERE id = :id",
                    ['id' => $value['id']]
                );
                if (is_numeric($delete) && $delete > 0){
                    @unlink($this->folder.$value['imagem']);
                }
            }
        }

    }

    function setArtigo() //Criar artigo
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

        if(is_array($upload) && isset($upload[0])){
            $insert = DB\Mysql::insert(
                'INSERT artigos (nome, id_adm, imagem, quantidade, preco, descricao, categoria, subcategoria, cor, marca, tamanho, registo, estado) VALUES (:nome, :id_adm, :img, :quantidade, :preco, :descricao, :categoria, :subcategoria, :cor, :marca, :tamanho, :registo, :estado)',
                [
                    'nome'         => $this->nome,
                    'id_adm'       => $this->id,
                    'quantidade'   => $this->quantidade,
                    'preco'        => $this->preco,
                    'img'          => $upload[0]['name'],
                    'descricao'    => $this->descricao,
                    'categoria'    => $this->categoria,
                    'subcategoria' => $this->subcategoria,
                    'cor'          => $this->cor,
                    'marca'        => $this->marca,
                    'tamanho'      => $this->tamanho,
                    'registo'      => $this->registo,
                    'estado'       => $this->estado,
                ]
            );
            
            return $insert;
        
        }else{
            return $upload;
        }
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
                    'INSERT artigos_imagem (id_artigo, imagem, registo) VALUES (:id_artigo, :img, :registo)',
                    [
                        'id_artigo' => $this->artigo_id,
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
            "DELETE FROM artigos_imagem WHERE imagem = :imagem",
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

    function editArtigo()
    {
        $update = DB\Mysql::update(
            "UPDATE artigos SET nome = :nome, quantidade = :quantidade, preco = :preco, descricao = :descricao, categoria = :categoria, subcategoria = :subcategoria, cor = :cor, marca = :marca, tamanho = :tamanho, estado = :estado WHERE id = :id",
            [
                'id'            => $this->artigo_id,
                'nome'         => $this->nome,
                'quantidade'   => $this->quantidade,
                'preco'        => $this->preco,
                'descricao'    => $this->descricao,
                'categoria'    => $this->categoria,
                'subcategoria' => $this->subcategoria,
                'cor'          => $this->cor,
                'marca'        => $this->marca,
                'tamanho'      => $this->tamanho,
                'estado'       => $this->estado,
            ]
        );
        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

    function changeImage()
    {
        $select = DB\Mysql::select(
            'SELECT imagem FROM artigos WHERE id = :artigo_id',
            ['artigo_id' => $this->artigo_id]
        );
        if(is_array($select)){
            $update = DB\Mysql::update(
                "UPDATE artigos_imagem SET imagem = :img WHERE imagem = :old_img",
                ['old_img' => $this->old_img, 'img' => $select[0]['imagem']]
            );
            if(is_numeric($update) && $update > 0){
                $update = DB\Mysql::update(
                    "UPDATE artigos SET imagem = :old_img WHERE imagem = :img AND id = :artigo_id",
                    ['artigo_id' => $this->artigo_id, 'old_img' => $this->old_img, 'img' => $select[0]['imagem']]
                );
                return 1;
            }else{
                return 'falha ao alterar a imagem!';
            }

        }else{
            return 'Falha ao verificar a imagem!';
        }

    }

    function removeData() //Remover artigo
    {
        $this->artigo_id = array_map('intval' ,explode(',', $this->artigo_id));
        $key = array_search('', $this->artigo_id);

        if($key!==false){
            unset($this->artigo_id[$key]);
        }

        $status = 1; 

        foreach ($this->artigo_id as $key => $value) {
            $select = DB\Mysql::select(
                'SELECT id, imagem FROM artigos WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){

                $delete = DB\Mysql::delete(
                    "DELETE FROM artigos WHERE id = :id",
                    ['id' => $value]
                );
                if (is_numeric($delete) && $delete > 0){
                    @unlink($this->folder.$select[0]['imagem']);
                    $this->deleteImage($value);
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

if(post('set_artigo')):

    $data = new Artigo();
    eco($data->setArtigo(), true);
    exit();

elseif(post('remove_artigo')):

    $data = new Artigo();
    eco($data->removeData(), true);
    exit();

elseif(post('add_image')):
    
    $data = new Artigo();
    eco($data->setImage(), true);
    exit();

elseif(post('delete_image')):

    $data = new Artigo();
    eco($data->removeImage(filterVar(post('delete_image'))), true);
    exit();

elseif(post('edit_artigo')):
    
    $data = new Artigo();
    eco($data->editArtigo(), true);
    exit();

elseif(post('change_image')):
    
    $data = new Artigo();
    eco($data->changeImage(), true);
    exit();

endif;

?>