<?php

include_once '../conexao.php';

Class Marca{

    private $id, $marca, $old_marca, $marca_id, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->marca_id   = post('marca_id', false) 
        ? filterVar(post('marca_id'))  
        : DEFAULT_INT;

        $this->marca      = post('marca', false)
        ? filterVar(post('marca'))  
        : DEFAULT_STRING;

        $this->old_marca  = post('old_marca', false)
        ? filterVar(post('old_marca'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}

    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM marca WHERE marca = :marca',
            ['marca' => $this->marca]
        ))){ return 'Esta marca já se encontra registada!'; }else{ return 0; }
    }

    function setmarca() //Criar marca
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT marca (marca, registo) VALUES (:marca, :registo)',
            ['marca' => $this->marca, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editmarca()//Editar marca
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $update = DB\Mysql::update(
            'UPDATE marca SET marca = :marca WHERE id = :id',
            ['id' => $this->marca_id, 'marca' => $this->marca]
        );
        
        if(is_numeric($update) && $update > 0){
            foreach (['artigos'] as $key => $table){
                DB\Mysql::update(
                    "UPDATE $table SET categoria = :categoria WHERE categoria = :old_categoria",
                    ['old_marca' => $this->old_marca, 'marca' => $this->marca]
                );   
            }
            return 1;
        }else{
            return $update;
        }

    }

    //Remover marca
    function removeData(){
        
        $this->marca_id = array_map('intval' ,explode(',', $this->marca_id));
        $key = array_search('', $this->marca_id);

        if($key!==false){
            unset($this->marca_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM marca WHERE id IN(".implode(',', $this->marca_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('add_marca')):

    $data = new Marca();
    eco($data->setmarca(), true);
    exit();

elseif(post('edit_marca')):

    $data = new Marca();
    eco($data->editmarca(), true);
    exit();

elseif(post('remove_marca')):

    $data = new Marca();
    eco($data->removeData(), true);
    exit();

endif;

?>