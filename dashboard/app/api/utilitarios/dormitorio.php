<?php

include_once '../conexao.php';

Class Dormitorio{

    private $id, $dormitorio, $old_dormitorio, $dormitorio_id, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->dormitorio_id   = post('dormitorio_id', false) 
        ? filterVar(post('dormitorio_id'))  
        : DEFAULT_INT;

        $this->dormitorio      = post('dormitorio', false)
        ? filterVar(post('dormitorio')) 
        : DEFAULT_STRING;

        $this->old_dormitorio  = post('old_dormitorio', false)
        ? filterVar(post('old_dormitorio'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM dormitorio WHERE dormitorio = :dormitorio',
            ['dormitorio' => $this->dormitorio]
        ))){ return 'Este dormitório já se encontra registado!'; }else{ return 0; }
    }

    function setdormitorio() //Criar dormitorio
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT dormitorio (dormitorio, registo) VALUES (:dormitorio, :registo)',
            ['dormitorio' => $this->dormitorio, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editdormitorio() //Editar dormitorio
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $update = DB\Mysql::update(
            'UPDATE dormitorio SET dormitorio = :dormitorio WHERE id = :id',
            ['id' => $this->dormitorio_id, 'dormitorio' => $this->dormitorio]
        );
        
        if(is_numeric($update) && $update > 0){
            foreach (['artigos'] as $key => $table){
                DB\Mysql::update(
                    "UPDATE $table SET dormitorio = :dormitorio WHERE dormitorio = :old_dormitorio",
                    ['old_dormitorio' => $this->old_dormitorio, 'dormitorio' => $this->dormitorio]
                );   
            }
            return 1;
        }else{
            return $update;
        }

    }

    
    function removeData() //Remover dormitorio
    {
        
        $this->dormitorio_id = array_map('intval' ,explode(',', $this->dormitorio_id));
        $key = array_search('', $this->dormitorio_id);

        if($key!==false){
            unset($this->dormitorio_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM dormitorio WHERE id IN(".implode(',', $this->dormitorio_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('add_dormitorio')):

    $data = new Dormitorio();
    eco($data->setDormitorio(), true);
    exit();

elseif(post('edit_dormitorio')):

    $data = new Dormitorio();
    eco($data->editDormitorio(), true);
    exit();

elseif(post('remove_dormitorio')):

    $data = new Dormitorio();
    eco($data->removeData(), true);
    exit();

endif;

?>