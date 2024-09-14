<?php

include_once '../conexao.php';

Class Pendente{

    private $id, $estado, $pendente_id;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->estado = 1;

        $this->pendente_id = post('pendente_id', false) 
        ? filterVar(post('pendente_id')) 
        : DEFAULT_INT;
	}

    function confirmCompra() //Confirmar compra
    {
        $update = DB\Mysql::update(
            'UPDATE cursos_pendentes SET estado = :estado WHERE id = :id',
            ['id' => $this->pendente_id, 'estado' => $this->estado]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return 0;
        }
    }

    function removeData() //Remover compra pendente
    { 
        $this->pendente_id = array_map('intval' ,explode(',', $this->pendente_id));
        $key = array_search('', $this->pendente_id);

        if($key!==false){
            unset($this->pendente_id[$key]);
        }

        $status = 1;

        foreach ($this->pendente_id as $key => $value) {

            $delete = DB\Mysql::delete(
                "DELETE FROM cursos_pendentes WHERE id = :id",
                ['id' => $value]
            );
            if($delete <= 0){
                $status = 0;
                break;
            }

        }

        return $status;

    }

}

if(post('remove_compra')):

    $data = new Pendente();
    eco($data->removeData(), true);
    exit();

elseif(post('confirm_compra')):

    $data = new Pendente();
    eco($data->confirmCompra(), true);
    exit();

endif;

?>