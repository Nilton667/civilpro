<?php

include_once 'conexao.php';

class Carrinho
{
    private $id, $registo;

    function __construct(){

        $this->id = isAccessible(
            post('id', false)    ? filterInt(post('id'))    : 0, 
            post('token', false) ? filterVar(post('token')) : ''
        );

        $this->registo  = date('d/m/Y');

    }

    function getData(){

        $SELECT = "SELECT * from carrinho WHERE id_cliente = :id ORDER BY id DESC";

        $select = DB\Mysql::select(
            $SELECT,
            [
            	'id' => $this->id
            ]
        );

        if(is_array($select)){
            foreach ($select as $key => $value) {
		        $selectArtigo = DB\Mysql::select(
		            "SELECT * FROM artigos WHERE id = :id",
		            [
		            	'id' => $value['id_produto']
		            ]
		        );
		        if(is_array($selectArtigo)):
	                $select[$key]['nome']       = $selectArtigo[0]['nome'];
		        	$select[$key]['preco']      = $selectArtigo[0]['preco'].'';
		        	$select[$key]['total']      = $selectArtigo[0]['preco'] * $value['quantidade'].'';
		        	$select[$key]['totalCount'] = $selectArtigo[0]['preco'] * $value['quantidade'];
		        else:
	                $select[$key]['nome']       = 'n/a';
		        	$select[$key]['preco']      = '0';
		        	$select[$key]['totalCount'] = '0';
		        	$select[$key]['total']      = "{$selectArtigo[0]['preco']}";
		        endif;
            }
            return $select;
        }else{
            return 0;
        }
    }

    //Processar compra
    function processarCompra(){

		$nome               = post('nome', false) 
		? filterVar(post('nome')).' '.filterVar(post('sobrenome')) 
		: '';

		$morada             = post('morada', false) 
		? filterVar(post('morada')) 
		: '';

		$nota_extra         = post('extra', false) 
		? filterVar(post('extra'))
		: '';

		$forma_de_pagamento = post('forma_de_pagamento', false) 
		? filterVar(post('forma_de_pagamento')) 
		: '';

		$valor_recebido     = post('valor_recebido', false) 
		?  filterInt(post('valor_recebido'))
		: '';

		$data               = date("d/m/Y").' às '.date('H:i');

		$entrega            = 0;

		$id_factura         = $this->id.date('dmYHis');


        $select = DB\Mysql::select(
            "SELECT * from carrinho WHERE id_cliente = :id ORDER BY id DESC",
            [
            	'id' => $this->id
            ]
        );

        if(is_array($select)):

            $gravar_registo = "INSERT INTO fatura (id_factura, nome_do_cliente, morada, forma, nota_extra, id_cliente, valor_recebido, registo)";
            $gravar_registo.= " VALUES (:id_factura, :nome, :morada, :forma, :nota_extra, :id_cliente, :valor_recebido, :data)";

	        DB\Mysql::insert(
	            $gravar_registo,
	            [
	            	'nome' => $nome,'morada' => $morada, 'id_factura' => $id_factura, 'forma' => $forma_de_pagamento, 'valor_recebido' => $valor_recebido, 'data' => $data, 'id_cliente' => $this->id, 'nota_extra' => $nota_extra,
	            ]
	        );

			foreach ($select as $key => $value) {
	            $id         = $value['id_produto'];
	            $quantidade = $value['quantidade'];

	            $obter_valor_do_produto = "SELECT * FROM artigos WHERE id = :id";
	            $incorporar = Conexao::getCon(1)->prepare($obter_valor_do_produto);
	            $incorporar->bindParam(':id', $id, PDO::PARAM_INT);
	            $incorporar->execute();
	            $target = $incorporar->rowCount();
	            if ($target > 0) {
	              while ($get = $incorporar->FETCH(PDO::FETCH_OBJ)) {
	                $preco    = $get->preco;
	              }
	            }else{
	              $preco      = 0;
	            }

		        $insert = DB\Mysql::insert(
		            "INSERT INTO vendas (id_objecto, preco, quantidade, cor, tamanho, data, id_de_compra) VALUES (:id_objecto, :preco, :quantidade, :cor, :tamanho, :data, :id_de_compra)",
		            [
		           		'id_objecto' => $id, 'quantidade' => $quantidade, 'cor' => $value['cor'], 'tamanho' => $value['tamanho'], 'preco' => $preco, 'data' => $data, 'id_de_compra' => $id_factura,
		            ]
		        );

		        if(is_numeric($insert) && $insert > 0){
	              $delete = "DELETE from carrinho WHERE id = :id";
	              $resultDelete = Conexao::getCon(1)->prepare($delete);
	              $resultDelete->bindParam(':id', $value['id'], PDO::PARAM_INT);
	              $resultDelete->execute();
		        }
			}	

          return 1;
        else:
        	return 0;
        endif;
    }

}

if (post('getData')):

    $data = new Carrinho();
    eco($data->getData(), true);
    exit();

elseif(post('processarCompra')):

    $data = new Carrinho();
    eco($data->processarCompra(), false);
    exit();

endif;
    
?>