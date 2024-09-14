<?php

include_once '../conexao.php';

Class Tamanho{

    private $id, $tamanho, $old_tamanho, $tamanho_id, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->tamanho_id   = post('tamanho_id', false) 
        ? filterVar(post('tamanho_id'))  
        : DEFAULT_INT;

        $this->tamanho      = post('tamanho', false)
        ? filterVar(post('tamanho')) 
        : DEFAULT_STRING;

        $this->old_tamanho  = post('old_tamanho', false)
        ? filterVar(post('old_tamanho'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM tamanho WHERE tamanho = :tamanho',
            ['tamanho' => $this->tamanho]
        ))){ return 'Este tamanho já se encontra registado!'; }else{ return 0; }
    }

    function setTamanho() //Criar tamanho
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT tamanho (tamanho, registo) VALUES (:tamanho, :registo)',
            ['tamanho' => $this->tamanho, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editTamanho() //Editar tamanho
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $update = DB\Mysql::update(
            'UPDATE tamanho SET tamanho = :tamanho WHERE id = :id',
            ['id' => $this->tamanho_id, 'tamanho' => $this->tamanho]
        );
        
        if(is_numeric($update) && $update > 0){
            foreach (['artigos'] as $key => $table){
                DB\Mysql::update(
                    "UPDATE $table SET tamanho = :tamanho WHERE tamanho = :old_tamanho",
                    ['old_tamanho' => $this->old_tamanho, 'tamanho' => $this->tamanho]
                );   
            }
            return 1;
        }else{
            return $update;
        }

    }

    
    function removeData() //Remover tamanho
    {
        
        $this->tamanho_id = array_map('intval' ,explode(',', $this->tamanho_id));
        $key = array_search('', $this->tamanho_id);

        if($key!==false){
            unset($this->tamanho_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM tamanho WHERE id IN(".implode(',', $this->tamanho_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('add_tamanho')):

    $data = new Tamanho();
    eco($data->setTamanho(), true);
    exit();

elseif(post('edit_tamanho')):

    $data = new Tamanho();
    eco($data->editTamanho(), true);
    exit();

elseif(post('remove_tamanho')):

    $data = new Tamanho();
    eco($data->removeData(), true);
    exit();

endif;

?>