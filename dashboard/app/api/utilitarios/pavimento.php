<?php

include_once '../conexao.php';

Class Pavimento{

    private $id, $pavimento, $old_pavimento, $pavimento_id, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->pavimento_id   = post('pavimento_id', false) 
        ? filterVar(post('pavimento_id'))  
        : DEFAULT_INT;

        $this->pavimento      = post('pavimento', false)
        ? filterVar(post('pavimento')) 
        : DEFAULT_STRING;

        $this->old_pavimento  = post('old_pavimento', false)
        ? filterVar(post('old_pavimento'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM pavimento WHERE pavimento = :pavimento',
            ['pavimento' => $this->pavimento]
        ))){ return 'Este pavimento já se encontra registado!'; }else{ return 0; }
    }

    function setpavimento() //Criar pavimento
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT pavimento (pavimento, registo) VALUES (:pavimento, :registo)',
            ['pavimento' => $this->pavimento, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editpavimento() //Editar pavimento
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $update = DB\Mysql::update(
            'UPDATE pavimento SET pavimento = :pavimento WHERE id = :id',
            ['id' => $this->pavimento_id, 'pavimento' => $this->pavimento]
        );
        
        if(is_numeric($update) && $update > 0){
            foreach (['artigos'] as $key => $table){
                DB\Mysql::update(
                    "UPDATE $table SET pavimento = :pavimento WHERE pavimento = :old_pavimento",
                    ['old_pavimento' => $this->old_pavimento, 'pavimento' => $this->pavimento]
                );   
            }
            return 1;
        }else{
            return $update;
        }

    }

    
    function removeData() //Remover pavimento
    {
        
        $this->pavimento_id = array_map('intval' ,explode(',', $this->pavimento_id));
        $key = array_search('', $this->pavimento_id);

        if($key!==false){
            unset($this->pavimento_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM pavimento WHERE id IN(".implode(',', $this->pavimento_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('add_pavimento')):

    $data = new Pavimento();
    eco($data->setPavimento(), true);
    exit();

elseif(post('edit_pavimento')):

    $data = new Pavimento();
    eco($data->editPavimento(), true);
    exit();

elseif(post('remove_pavimento')):

    $data = new Pavimento();
    eco($data->removeData(), true);
    exit();

endif;

?>