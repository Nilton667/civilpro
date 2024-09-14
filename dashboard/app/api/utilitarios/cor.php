<?php

include_once '../conexao.php';

Class Cor{

    private $id, $cor, $old_cor, $cor_id, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->cor_id   = post('cor_id', false) 
        ? filterVar(post('cor_id'))  
        : DEFAULT_INT;

        $this->cor      = post('cor', false)
        ? filterVar(post('cor'))  
        : DEFAULT_STRING;

        $this->old_cor  = post('old_cor', false)
        ? filterVar(post('old_cor')) 
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}

    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM cor WHERE cor = :cor',
            ['cor' => $this->cor]
        ))){ return 'Esta cor já se encontra registada!'; }else{ return 0; }
    }

    function setcor() //Criar cor
    {
        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT cor (cor, registo) VALUES (:cor, :registo)',
            ['cor' => $this->cor, 'registo' => $this->registo]
        );
        return $insert;
    }

    function editcor()//Editar cor
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $update = DB\Mysql::update(
            'UPDATE cor SET cor = :cor WHERE id = :id',
            ['id' => $this->cor_id, 'cor' => $this->cor]
        );

        if(is_numeric($update) && $update > 0){
            foreach (['artigos'] as $key => $table) {
                DB\Mysql::update(
                    "UPDATE $table SET cor = :cor WHERE cor = :old_cor",
                    ['old_cor' => $this->old_cor, 'cor' => $this->cor]
                );   
            }
            return 1;
        }else{
            return $update;
        }

    }

    //Remover cor
    function removeData(){

        $this->cor_id = array_map('intval' ,explode(',', $this->cor_id));
        $key = array_search('', $this->cor_id);

        if($key!==false){
            unset($this->cor_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM cor WHERE id IN(".implode(',', $this->cor_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }
        
    }

}

if(post('add_cor')):

    $data = new Cor();
    eco($data->setcor(), true);
    exit();

elseif(post('edit_cor')):

    $data = new Cor();
    eco($data->editcor(), true);
    exit();

elseif(post('remove_cor')):

    $data = new Cor();
    eco($data->removeData(), true);
    exit();

endif;

?>