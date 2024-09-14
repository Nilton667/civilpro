<?php

include_once '../conexao.php';

Class Banheiro{

    private $id, $banheiro, $old_banheiro, $banheiro_id, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->banheiro_id   = post('banheiro_id', false) 
        ? filterVar(post('banheiro_id'))  
        : DEFAULT_INT;

        $this->banheiro      = post('banheiro', false)
        ? filterVar(post('banheiro')) 
        : DEFAULT_STRING;

        $this->old_banheiro  = post('old_banheiro', false)
        ? filterVar(post('old_banheiro'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM banheiros WHERE banheiro = :banheiro',
            ['banheiro' => $this->banheiro]
        ))){ return 'Este banheiro já se encontra registado!'; }else{ return 0; }
    }

    function setbanheiro() //Criar banheiro
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT banheiros (banheiro, registo) VALUES (:banheiro, :registo)',
            ['banheiro' => $this->banheiro, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editbanheiro() //Editar banheiro
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $update = DB\Mysql::update(
            'UPDATE banheiros SET banheiro = :banheiro WHERE id = :id',
            ['id' => $this->banheiro_id, 'banheiro' => $this->banheiro]
        );
        
        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

    
    function removeData() //Remover banheiro
    {
        
        $this->banheiro_id = array_map('intval' ,explode(',', $this->banheiro_id));
        $key = array_search('', $this->banheiro_id);

        if($key!==false){
            unset($this->banheiro_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM banheiros WHERE id IN(".implode(',', $this->banheiro_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('add_banheiro')):

    $data = new Banheiro();
    eco($data->setBanheiro(), true);
    exit();

elseif(post('edit_banheiro')):

    $data = new Banheiro();
    eco($data->editBanheiro(), true);
    exit();

elseif(post('remove_banheiro')):

    $data = new Banheiro();
    eco($data->removeData(), true);
    exit();

endif;

?>