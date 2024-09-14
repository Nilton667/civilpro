<?php

include_once '../conexao.php';

Class Entrega{

    private $id, $entrega, $entrega_id;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->entrega    = 1;

        $this->entrega_id = post('entrega_id', false) 
        ? filterVar(post('entrega_id')) 
        : DEFAULT_INT;
	}

    function confirmEntrega() //Confirmar entrega
    {
        $update = DB\Mysql::update(
            'UPDATE fatura SET entrega = :entrega WHERE id = :id',
            ['id' => $this->entrega_id, 'entrega' => $this->entrega]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }
    }
}

if(post('confirm_entrega')):

    $data = new Entrega();
    eco($data->confirmEntrega(), true);
    exit();

endif;

?>