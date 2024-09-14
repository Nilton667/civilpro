<?php

include_once '../conexao.php';

Class Pagamento{

    private $id, $pagamento, $old_pagamento, $pagamento_id, $descricao, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->pagamento_id   = post('pagamento_id', false)
        ? filterVar(post('pagamento_id'))  
        : DEFAULT_INT;

        $this->pagamento      = post('pagamento', false)
        ? filterVar(post('pagamento'))  
        : DEFAULT_STRING;

        $this->descricao   = post('descricao', false) 
        ? trim(post('descricao'))  
        : DEFAULT_STRING;

        $this->old_pagamento  = post('old_pagamento', false)
        ? filterVar(post('old_pagamento'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM pagamento WHERE pagamento = :pagamento AND descricao = :descricao',
            ['pagamento' => $this->pagamento, 'descricao' => $this->descricao]
        ))){ return 'Este método de pagamento já se encontra registado!'; }else{ return 0; }
    }

    function setPagamento() //Criar pagamento
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT pagamento (pagamento, descricao, registo) VALUES (:pagamento, :descricao, :registo)',
            ['pagamento' => $this->pagamento, 'descricao' => $this->descricao, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editPagamento() //Editar pagamento
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;
        
        $update = DB\Mysql::update(
            'UPDATE pagamento SET pagamento = :pagamento, descricao = :descricao WHERE id = :id',
            ['id' => $this->pagamento_id, 'pagamento' => $this->pagamento, 'descricao' => $this->descricao]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

    
    function removeData() //Remover pagamento
    {
        $this->pagamento_id = array_map('intval' ,explode(',', $this->pagamento_id));
        $key = array_search('', $this->pagamento_id);

        if($key!==false){
            unset($this->pagamento_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM pagamento WHERE id IN(".implode(',', $this->pagamento_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('add_pagamento')):

    $data = new Pagamento();
    eco($data->setPagamento(), true);
    exit();

elseif(post('edit_pagamento')):

    $data = new Pagamento();
    eco($data->editPagamento(), true);
    exit();

elseif(post('remove_pagamento')):

    $data = new Pagamento();
    eco($data->removeData(), true);
    exit();

endif;

?>