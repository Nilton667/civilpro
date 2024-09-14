<?php

include_once '../conexao.php';

Class Artigo extends Conexao{

    private $id, $id_carrinho, $id_cliente, $id_produto, $quantidade, $cor, $tamanho;

  	function __construct()
  	{
          $this->id = Components\getSession('maestro', 'id', 1,  true);

          $this->id_cliente  = is_numeric(post('id_cliente', false)) 
          ? filterInt(post('id_cliente')) 
          : 0;
          
          $this->id_produto  = is_numeric(post('id_produto', false)) 
          ? filterInt(post('id_produto')) 
          : 0;

          $this->quantidade  = is_numeric(post('quantidade', false)) 
          ? filterInt(post('quantidade')) 
          : 0;

          $this->cor         = post('cor', false) 
          ? filterVar(post('cor')) 
          : '';

          $this->tamanho     = post('tamanho', false) 
          ? filterVar(post('tamanho')) 
          : '';

          $this->id_carrinho = is_numeric(post('id_carrinho', false)) 
          ? filterInt(post('id_carrinho')) 
          : 0;

  	}

    //Adicionar no carrinho
    function addCart()
    {

        $data        = date('d/m/Y').' às '.date('H:i');

        //Verificar a quantidade
        try {
          $SELECT = "SELECT quantidade FROM artigos WHERE id = :id_produto LIMIT 1";
          $result = Conexao::getCon(1)->prepare($SELECT);
          $result->bindParam(':id_produto', $this->id_produto, PDO::PARAM_INT);
          $result->execute();
          $contar = $result->rowCount();
          if($contar > 0):
            while ($mostra = $result->FETCH(PDO::FETCH_OBJ)) {
              if($mostra->quantidade < $this->quantidade){
                return json_encode('A quantidade solicitada ultrapassa o limite restante!');
              }
              $quantidade_restante = $mostra->quantidade - $this->quantidade;
            }
          else:
            return json_encode('Artigo não encontrado!');
          endif;
        } catch (Exception $e) {
          return json_encode($e);
        }

        try {
            //Inserir no carrinho
            $INSERT = "INSERT INTO carrinho (id_cliente, id_produto, quantidade, cor, tamanho, data)";
            $INSERT.= " VALUES (:id_cliente, :id_produto, :quantidade, :cor, :tamanho, :data)";

            $result = Conexao::getCon(1)->prepare($INSERT);
            $result->bindParam(':id_cliente', $this->id_cliente, PDO::PARAM_INT);
            $result->bindParam(':id_produto', $this->id_produto, PDO::PARAM_INT);
            $result->bindParam(':quantidade', $this->quantidade, PDO::PARAM_INT);
            $result->bindParam(':cor', $this->cor, PDO::PARAM_STR);
            $result->bindParam(':tamanho', $this->tamanho, PDO::PARAM_STR);
            $result->bindParam(':data', $data, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
            if($contar > 0):

                $UPDATE = "UPDATE artigos SET quantidade = :quantidade WHERE id = :id_produto";
                $result = Conexao::getCon(1)->prepare($UPDATE);
                $result->bindParam(':quantidade', $quantidade_restante, PDO::PARAM_INT);
                $result->bindParam(':id_produto', $this->id_produto, PDO::PARAM_INT);
                $result->execute();
                return json_encode(1);

            else:
                return json_encode(0);
            endif;
        } catch (Exception $e) {
          return json_encode($e);
        }
    }

    //Remover do carrinho
    function removeCart(){

      try {
        $SELECT = "SELECT id, id_produto, quantidade FROM carrinho WHERE id = :id_carrinho LIMIT 1";
        $result = Conexao::getCon(1)->prepare($SELECT);
        $result->bindParam(':id_carrinho', $this->id_carrinho, PDO::PARAM_INT);
        $result->execute();
        $contar = $result->rowCount();
        if($contar > 0){
          while ($mostra = $result->FETCH(PDO::FETCH_OBJ)){

            $DELETE = "DELETE FROM `carrinho` WHERE id = :id_carrinho";
            $result = Conexao::getCon(1)->prepare($DELETE);
            $result->bindParam(':id_carrinho', $this->id_carrinho, PDO::PARAM_INT);
            $result->execute();
            $contar = $result->rowCount();
            if($contar > 0){

              $SELECT = "SELECT quantidade FROM artigos WHERE id = :id_produto LIMIT 1";
              $result = Conexao::getCon(1)->prepare($SELECT);
              $result->bindParam(':id_produto', $mostra->id_produto, PDO::PARAM_INT);
              $result->execute();
              $contar = $result->rowCount();
              if($contar > 0):

                while ($mostra2 = $result->FETCH(PDO::FETCH_OBJ)) {

                  $quantidade_restante = ($mostra->quantidade + $mostra2->quantidade);

                  $UPDATE = "UPDATE artigos SET quantidade = :quantidade WHERE id = :id_produto";
                  $result2 = Conexao::getCon(1)->prepare($UPDATE);
                  $result2->bindParam(':quantidade', $quantidade_restante, PDO::PARAM_INT);
                  $result2->bindParam(':id_produto', $mostra->id_produto, PDO::PARAM_INT);
                  $result2->execute();

                }
              endif;

              return json_encode(1);

            }else{
              return json_encode(0);
            } 

          }
        }else{
          return json_encode(0);
        }
      } catch (Exception $e) {
        return json_encode($e);
      } 

    }

    //Processar compra
    function processarCompra(){

      $nome               = post('nome', false) 
      ? filterVar(post('nome')) 
      : '';

      $id_cliente         = post('id_cliente', false) 
      ? filterInt(post('id_cliente')) 
      : 0;

      $morada             = post('morada', false) 
      ? filterVar(post('morada')) 
      : '';

      $contacto           = post('contacto', false) 
      ? filterVar(post('contacto')) 
      : '';

      $nota_extra         = post('extra', false) 
      ? filterVar(post('extra'))
      : '';

      $forma_de_pagamento = post('forma_de_pagamento', false) 
      ? filterVar(post('forma_de_pagamento')) 
      : '';

      $valor_recebido     = post('valor_recebido', false) 
      ?  filterInt(post('valor_recebido'))
      : 0;

      $frete              = post('taxa_envio', false) 
      ? filterInt(post('taxa_envio')) 
      : 0;

      $desgnacao_deliver  = post('desgnacao_deliver', false) 
      ? filterVar(post('desgnacao_deliver')) 
      : '';

      $data               = date("d/m/Y").' às '.date('H:i');

      $entrega            = 0;

      $estado             = 0;

      $id_factura         = $id_cliente.date('dmYHis');

      $select = "SELECT * from carrinho WHERE id_cliente = :id_cliente ORDER BY id DESC";

      try {
        $result = Conexao::getCon(1)->prepare($select);
        $result->bindParam(':id_cliente', $id_cliente, PDO::PARAM_STR);
        $result->execute();
        $contar = $result->rowCount();
          if ($contar > 0) {

            $gravar_registo = "INSERT INTO fatura (id_factura, nome_do_cliente, contacto, morada, estado, forma, nota_extra, id_cliente, desgnacao_deliver, taxa_deliver, valor_recebido, registo)";

            $gravar_registo.= " VALUES (:id_factura, :nome, :contacto, :morada, :estado, :forma, :nota_extra, :id_cliente, :desgnacao_deliver, :taxa_deliver, :valor_recebido, :data)";

            $save = Conexao::getCon(1)->prepare($gravar_registo);
            $save->bindParam(':nome', $nome, PDO::PARAM_STR);
            $save->bindParam(':contacto', $contacto, PDO::PARAM_STR);
            $save->bindParam(':morada', $morada, PDO::PARAM_STR);
            $save->bindParam(':estado', $estado, PDO::PARAM_STR);
            $save->bindParam(':id_factura', $id_factura, PDO::PARAM_INT);
            $save->bindParam(':forma', $forma_de_pagamento, PDO::PARAM_STR);
            $save->bindParam(':desgnacao_deliver', $desgnacao_deliver, PDO::PARAM_STR);
            $save->bindParam(':taxa_deliver', $frete, PDO::PARAM_STR);
            $save->bindParam(':valor_recebido', $valor_recebido, PDO::PARAM_STR);
            $save->bindParam(':data', $data, PDO::PARAM_STR);
            $save->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $save->bindParam(':nota_extra', $nota_extra, PDO::PARAM_STR);
            $save->execute();

            while ($itens = $result->FETCH(PDO::FETCH_OBJ)) {
            //inicio

            $id         = $itens->id_produto;
            $quantidade = $itens->quantidade;
            $cor        = $itens->cor;
            $tamanho    = $itens->tamanho;

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

            $INSERT = "INSERT INTO vendas (id_objecto, preco, quantidade, cor, tamanho, data, id_de_compra) VALUES (:id_objecto, :preco, :quantidade, :cor, :tamanho, :data, :id_de_compra)";

            $result2 = Conexao::getCon(1)->prepare($INSERT);
            $result2->bindParam(':id_objecto', $id, PDO::PARAM_INT);
            $result2->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
            $result2->bindParam(':cor', $cor, PDO::PARAM_STR);
            $result2->bindParam(':tamanho', $tamanho, PDO::PARAM_STR);
            $result2->bindParam(':preco', $preco, PDO::PARAM_INT);
            $result2->bindParam(':data', $data, PDO::PARAM_STR);
            $result2->bindParam(':id_de_compra', $id_factura, PDO::PARAM_INT);
            $result2->execute();
            $contar2 = $result2->rowCount();        
            if ($contar2 > 0) {
              $delete = "DELETE from carrinho WHERE id = :id";
              $resultDelete = Conexao::getCon(1)->prepare($delete);
              $resultDelete->bindParam(':id', $itens->id, PDO::PARAM_INT);
              $resultDelete->execute();
            }
          }

          return json_encode(1);

        }else{
          return json_encode(0);
        }
      } catch (Exception $e) {
        return json_encode($e);
      }
    }

}

if(post('add_cart', true)):

    $data = new Artigo();
    eco($data->addCart());
    exit();

elseif(post('remove_cart', true)):

    $data = new Artigo();
    eco($data->removeCart());
    exit();

elseif (post('processar_compra', true)):
    
    $data = new Artigo();
    eco($data->processarCompra());
    exit();

endif;

?>