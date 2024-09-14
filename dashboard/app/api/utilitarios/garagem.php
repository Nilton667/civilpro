<?php

include_once '../conexao.php';

Class Garagem{

    private $id, $garagem, $old_garagem, $garagem_id, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->garagem_id   = post('garagem_id', false) 
        ? filterVar(post('garagem_id'))  
        : DEFAULT_INT;

        $this->garagem      = post('garagem', false)
        ? filterVar(post('garagem')) 
        : DEFAULT_STRING;

        $this->old_garagem  = post('old_garagem', false)
        ? filterVar(post('old_garagem'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM garagem WHERE garagem = :garagem',
            ['garagem' => $this->garagem]
        ))){ return 'Este garagem já se encontra registado!'; }else{ return 0; }
    }

    function setgaragem() //Criar garagem
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT garagem (garagem, registo) VALUES (:garagem, :registo)',
            ['garagem' => $this->garagem, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editgaragem() //Editar garagem
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $update = DB\Mysql::update(
            'UPDATE garagem SET garagem = :garagem WHERE id = :id',
            ['id' => $this->garagem_id, 'garagem' => $this->garagem]
        );
        
        if(is_numeric($update) && $update > 0){
            foreach (['artigos'] as $key => $table){
                DB\Mysql::update(
                    "UPDATE $table SET garagem = :garagem WHERE garagem = :old_garagem",
                    ['old_garagem' => $this->old_garagem, 'garagem' => $this->garagem]
                );   
            }
            return 1;
        }else{
            return $update;
        }

    }

    
    function removeData() //Remover garagem
    {
        
        $this->garagem_id = array_map('intval' ,explode(',', $this->garagem_id));
        $key = array_search('', $this->garagem_id);

        if($key!==false){
            unset($this->garagem_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM garagem WHERE id IN(".implode(',', $this->garagem_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('add_garagem')):

    $data = new Garagem();
    eco($data->setGaragem(), true);
    exit();

elseif(post('edit_garagem')):

    $data = new Garagem();
    eco($data->editGaragem(), true);
    exit();

elseif(post('remove_garagem')):

    $data = new Garagem();
    eco($data->removeData(), true);
    exit();

endif;

?>