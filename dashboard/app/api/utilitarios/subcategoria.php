<?php

include_once '../conexao.php';

Class Categoria{

    private $id, $subcategoria, $categoria, $subcategoria_id, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->subcategoria_id = post('subcategoria_id', false) 
        ? filterVar(post('subcategoria_id'))  
        : DEFAULT_INT;

        $this->subcategoria    = post('subcategoria', false)
        ? filterVar(post('subcategoria'))  
        : DEFAULT_STRING;

        $this->categoria       = post('categoria', false)
        ? filterVar(post('categoria'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}

    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM subcategoria WHERE subcategoria = :subcategoria AND categoria = :categoria',
            ['subcategoria' => $this->subcategoria, 'categoria' => $this->categoria]
        ))){ return 'Esta subcategoria já se encontra registada!'; }else{ return 0; }
    }

    function setCategoria() //Criar subcategoria
    {
        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT subcategoria (subcategoria, categoria, registo) VALUES (:subcategoria, :categoria, :registo)',
            ['subcategoria' => $this->subcategoria, 'categoria' => $this->categoria, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editCategoria() //Editar subcategoria
    {

        $update = DB\Mysql::update(
            'UPDATE subcategoria SET subcategoria = :subcategoria, categoria = :categoria WHERE id = :id',
            ['id' => $this->subcategoria_id, 'categoria' => $this->categoria, 'subcategoria' => $this->subcategoria]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

    //Remover subcategoria
    function removeData(){
        
        $this->subcategoria_id = array_map('intval' ,explode(',', $this->subcategoria_id));
        $key = array_search('', $this->subcategoria_id);

        if($key!==false){
            unset($this->subcategoria_id[$key]);
        }
        
        $delete = DB\Mysql::delete(
            "DELETE FROM subcategoria WHERE id IN(".implode(',', $this->subcategoria_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('add_subcategoria')):

    $data = new Categoria();
    eco($data->setCategoria(), true);
    exit();

elseif(post('edit_subcategoria')):

    $data = new Categoria();
    eco($data->editCategoria(), true);
    exit();

elseif(post('remove_subcategoria')):

    $data = new Categoria();
    eco($data->removeData(), true);
    exit();

endif;

?>