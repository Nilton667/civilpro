<?php

include_once '../conexao.php';

Class Transfer{

    private $id, $transfer_id;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->transfer_id   = post('transfer_id', false)
        ? filterVar(post('transfer_id'))  
        : DEFAULT_INT;
	}
    
    function removeData() //Remover transfer
    {

        $this->transfer_id = array_map('intval' ,explode(',', $this->transfer_id));
        $key = array_search('', $this->transfer_id);

        if($key !== false){
            unset($this->transfer_id[$key]);
        }

        $status = 1; 

        foreach ($this->transfer_id as $key => $value) {
            $delete = DB\Mysql::delete(
                "DELETE FROM transfer WHERE id = :id",
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

if(post('remove_transfer')):

    $data = new Transfer();
    eco($data->removeData(), true);
    exit();

endif;

?>