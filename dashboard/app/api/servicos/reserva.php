<?php

include_once '../conexao.php';

Class Reserva{

    private $id, $reserva_id;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->reserva_id   = post('reserva_id', false)
        ? filterVar(post('reserva_id'))  
        : DEFAULT_INT;
	}

    function removeData() //Remover reserva
    {

        $this->reserva_id = array_map('intval' ,explode(',', $this->reserva_id));
        $key = array_search('', $this->reserva_id);

        if($key!==false){
            unset($this->reserva_id[$key]);
        }

        $status = 1; 

        foreach ($this->reserva_id as $key => $value) {
            $delete = DB\Mysql::delete(
                "DELETE FROM servicos_reservas WHERE id = :id",
                ['id' => $value]
            );
            if($delete <= 0){
                $status++;
                break;
            }
        }

        return $status;

    }

}

if(post('remove_reserva')):

    $data = new Reserva();
    eco($data->removeData(), true);
    exit();

endif;

?>