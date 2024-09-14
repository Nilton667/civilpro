<?php

include_once '../conexao.php';

Class Historico{

    private $id, $historico_id;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->historico_id = post('historico_id', false)
        ? filterVar(post('historico_id')) 
        : DEFAULT_INT;
	}

    function removeData(){//Remover venda
        $this->historico_id = array_map('intval' ,explode(',', $this->historico_id));
        $key = array_search('', $this->historico_id);

        if($key!==false){
            unset($this->historico_id[$key]);
        }

        $status = 1;

        foreach ($this->historico_id as $key => $value) {
            $select = DB\Mysql::select(
                'SELECT id_factura FROM fatura WHERE id = :id',
                ['id' => $value]
            );

            if(is_array($select)){
                $id_factura = $select[0]['id_factura'];
                $delete = DB\Mysql::delete(
                    'DELETE FROM fatura WHERE id = :id',
                    ['id' => $value]
                );
                if(is_numeric($delete) && $delete > 0){
                    DB\Mysql::delete(
                        'DELETE FROM vendas WHERE id_de_compra = :id_de_compra',
                        ['id_de_compra' => $id_factura]
                    );
                }else{
                    $status = 0;
                    break;
                }
            }else{
                $status = 0;
                break;
            }
        }

        return $status;

    }

}

if(post('remove_compra')):

    $data = new Historico();
    eco($data->removeData(), true);
    exit();

endif;

?>