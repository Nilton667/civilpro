<?php

include_once 'conexao.php';

Class Feedback{

    private $id, $feedback_id;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->feedback_id  = post('feedback_id', false)
        ? filterVar(post('feedback_id'))  
        : DEFAULT_INT;

	}

    function removeData() //Remover feedback
    {
        $this->feedback_id = array_map('intval' ,explode(',', $this->feedback_id));
        $key = array_search('', $this->feedback_id);

        if($key!==false){
            unset($this->feedback_id[$key]);
        }
        
        $delete = DB\Mysql::delete(
            "DELETE FROM feedback WHERE id IN(".implode(',', $this->feedback_id).")",
            []
        );
        if (is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('remove_feedback')):

    $data = new Feedback();
    eco($data->removeData(), true);
    exit();

endif;

?>