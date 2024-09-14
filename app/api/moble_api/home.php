<?php

include_once 'conexao.php';

class Home
{
    private $id, $idArtigo, $id_carrinho, $limiter, $categoria, $subcategoria, $quantidade, $preco, $cor, $tamanho, $query;

    function __construct(){

        $this->idArtigo       = post('idArtigo')
        ? filterInt(post('idArtigo')) 
        : 0;

        $this->id_carrinho    = post('id_carrinho')
        ? filterInt(post('id_carrinho')) 
        : 0;

        $this->limiter        = post('limiter')
        ? filterInt(post('limiter')) 
        : 0;

        $this->categoria      = post('categoria')
        ? filterVar(post('categoria')) 
        : DEFAULT_STRING;

        $this->subcategoria   = post('subcategoria')
        ? filterVar(post('subcategoria')) 
        : DEFAULT_STRING;

        $this->quantidade     = post('quantidade')
        ? filterInt(post('quantidade')) 
        : 0;

        $this->preco          = post('preco')
        ? filterInt(post('preco')) 
        : 0;

        $this->cor            = post('cor', false) 
        ? filterVar(post('cor'))
        : '';  

        $this->tamanho         = post('tamanho', false) 
        ? filterVar(post('tamanho'))
        : '';  

        $this->query          = post('query')
        ? filterVar(post('query')) 
        : DEFAULT_STRING;

    }

    //Pegar dados do artigo
    function getData(){

        if ($this->limiter <= 0) {
            $SELECT = "SELECT * from artigos WHERE estado = 1 ORDER BY id DESC LIMIT 15";
        }else{
            $SELECT = "SELECT * from artigos WHERE estado = 1 AND id < :limiter ORDER BY id DESC LIMIT 15";
        }

        $select = DB\Mysql::select(
            $SELECT,
            [
               'limiter' => $this->limiter
            ]
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }

    }

    //Get Categoria
    function getCategoria(){

        $select = DB\Mysql::select(
            "SELECT * from categoria ORDER BY id DESC",
            []
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

    //Get Destaque
    function getDestaque(){

        $select = DB\Mysql::select(
            "SELECT * from artigos WHERE estado = 1 ORDER BY rand() DESC LIMIT 8",
            []
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

    //Get artigos da categoria
    function getDataCategoria()
    {
        if ($this->limiter <= 0) {
            $SELECT = "SELECT * from artigos WHERE estado = 1 AND categoria = :categoria ORDER BY id DESC LIMIT 15";
        }else{
            $SELECT = "SELECT * from artigos WHERE estado = 1 AND categoria = :categoria AND id < :limiter ORDER BY id DESC LIMIT 15";
        }

        $select = DB\Mysql::select(
            $SELECT,
            $this->limiter > 0 ?
                ['limiter' => $this->limiter, 'categoria' => $this->categoria]
            :
                ['categoria' => $this->categoria]
        );

        if(is_array($select)){
            return $select;
        }else{
            return $select;
        }
    }

    //Get subcategorias
    function getSubcategoria(){

        $select = DB\Mysql::select(
            "SELECT * from subcategoria WHERE categoria = :categoria ORDER BY id DESC",
            [
                'categoria' => $this->categoria
            ]
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

    function getDataSubcategoria()
    {
        if ($this->limiter <= 0) {
            $SELECT = "SELECT * from artigos WHERE estado = 1 AND categoria = :categoria AND subcategoria = :subcategoria ORDER BY id DESC LIMIT 15";
        }else{
            $SELECT = "SELECT * from artigos WHERE estado = 1 AND categoria = :categoria AND subcategoria = :subcategoria WHERE id < :limiter ORDER BY id DESC LIMIT 15";
        }

        $select = DB\Mysql::select(
            $SELECT,
            $this->limiter > 0 ?
                ['limiter' => $this->limiter, 'categoria' => $this->categoria, 'subcategoria' => $this->subcategoria]
            :
                ['categoria' => $this->categoria, 'subcategoria' => $this->subcategoria]
        );

        if(is_array($select)){
            return $select;
        }else{
            return $select;
        }
    }

    function getSearchData()
    {
        $SELECT = "SELECT nome from artigos WHERE estado = 1 AND nome LIKE '%$this->query%'";
        $SELECT.= " OR estado = 1 AND categoria LIKE '%$this->query%'";
        $SELECT.= " OR estado = 1 AND subcategoria LIKE '%$this->query%' ORDER BY id DESC LIMIT 30";

        $select = DB\Mysql::select(
            $SELECT,
            []
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

    function getQueryData()
    {
        if ($this->limiter <= 0) {
            $SELECT = "SELECT * from artigos WHERE estado = 1 AND nome LIKE '%$this->query%'";
            $SELECT.= " OR estado = 1 AND categoria    LIKE '%$this->query%'";
            $SELECT.= " OR estado = 1 AND subcategoria LIKE '%$this->query%' ORDER BY id DESC LIMIT 15";
        }else{
            $SELECT = "SELECT * from artigos WHERE estado = 1 AND nome LIKE '%$this->query%' AND id < :limiter";
            $SELECT.= " OR estado = 1 AND categoria    LIKE '%$this->query%' AND id < :limiter";
            $SELECT.= " OR estado = 1 AND subcategoria LIKE '%$this->query%' AND id < :limiter ORDER BY id DESC LIMIT 15";
        }

        if($this->limiter > 0):
            $select = DB\Mysql::select(
                $SELECT,
                ['limiter' => $this->limiter]
            );
        else:
            $select = DB\Mysql::select(
                $SELECT,
                []
            );
        endif;

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

    function getDataId()
    {
        $select = DB\Mysql::select(
            "SELECT * from artigos WHERE id = :id LIMIT 1",
            ['id' => $this->idArtigo]
        );

        if(is_array($select)){
            foreach ($select as $key => $value):

                $getAdm = DB\Mysql::select(
                    "SELECT * from adm WHERE id = :id LIMIT 1",
                    ['id' => $value['id_adm']]
                );

                if(is_array($getAdm)):
                    $select[$key]['publicador'] = $getAdm[0]['nome'].' '.$getAdm[0]['sobrenome'];
                    $select[$key]['contacto']   = $getAdm[0]['telemovel'];
                    $select[$key]['email']      = $getAdm[0]['email'];
                else:
                    $select[$key]['publicador'] = 'n/a';
                    $select[$key]['contacto']   = 'n/a';
                    $select[$key]['email']      = 'n/a';
                endif;
            endforeach;
            return $select;
        }else{
            return 0;
        }
    }

    //Adicionar no carrinho
    function addCart()
    {
        $this->id = isAccessible(
            post('id', false)    ? filterInt(post('id'))    : 0, 
            post('token', false) ? filterVar(post('token')) : ''
        );
        $data   = date('d/m/Y').' às '.date('H:i');

        //Verificar a quantidade
        $select = DB\Mysql::select(
            "SELECT quantidade FROM artigos WHERE id = :id_produto LIMIT 1",
            ['id_produto' => $this->idArtigo]
        );

        if(is_array($select)){
            if($select[0]['quantidade'] < $this->quantidade){
                return 'A quantidade solicitada ultrapassa o limite restante!';
            }
            $quantidade_restante = $select[0]['quantidade'] - $this->quantidade;

            $insert = DB\Mysql::insert(
                "INSERT INTO carrinho (id_cliente, id_produto, quantidade, cor, tamanho, data) VALUES (:id_cliente, :id_produto, :quantidade, :cor, :tamanho, :data)",
                [                
                    'id_cliente' => $this->id,
                    'id_produto' => $this->idArtigo,
                    'quantidade' => $this->quantidade,
                    'cor'        => $this->cor,
                    'tamanho'    => $this->tamanho,
                    'data'       => $data
                ]
            );

            if(is_numeric($insert) && $insert > 0):
                DB\Mysql::update(
                    "UPDATE artigos SET quantidade = :quantidade WHERE id = :id_produto",
                    [                
                        'quantidade' => $quantidade_restante,
                        'id_produto' => $this->idArtigo
                    ]
                );
                return 1;
            else:
                return 0;
            endif;

        }else{
            return 'Artigo não encontrado!';
        }
    }

    function remove_cart()
    {
        $select = DB\Mysql::select(
            "SELECT id, id_produto, quantidade FROM carrinho WHERE id = :id_carrinho LIMIT 1",
            ['id_carrinho' => $this->id_carrinho]
        );
        if(is_array($select)){
            $delete = DB\Mysql::delete(
                "DELETE FROM `carrinho` WHERE id = :id_carrinho",
                ['id_carrinho' => $this->id_carrinho]
            );
            if(is_numeric($delete) && $delete > 0){
                ///
                $SELECT = "SELECT quantidade FROM artigos WHERE id = :id_produto LIMIT 1";
                $result = Conexao::getCon(1)->prepare($SELECT);
                $result->bindParam(':id_produto', $select[0]['id_produto'], PDO::PARAM_INT);
                $result->execute();
                $contar = $result->rowCount();
                if($contar > 0):
  
                  while ($mostra2 = $result->FETCH(PDO::FETCH_OBJ)) {
  
                    $quantidade_restante = ($select[0]['quantidade'] + $mostra2->quantidade);
  
                    $UPDATE = "UPDATE artigos SET quantidade = :quantidade WHERE id = :id_produto";
                    $result2 = Conexao::getCon(1)->prepare($UPDATE);
                    $result2->bindParam(':quantidade', $quantidade_restante, PDO::PARAM_INT);
                    $result2->bindParam(':id_produto', $select[0]['id_produto'], PDO::PARAM_INT);
                    $result2->execute();
  
                  }
                endif;
                ///
                return 1;

            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    function verifique()
    {
        $this->id = isAccessible(
            post('id', false)    ? filterInt(post('id'))    : 0, 
            post('token', false) ? filterVar(post('token')) : ''
        );
        $select = DB\Mysql::select(
            "SELECT * FROM carrinho WHERE id_cliente = :id_cliente AND id_produto = :id_produto LIMIT 1",
            [
                'id_cliente' => $this->id,
                'id_produto' => $this->idArtigo
            ]
        );
        if(is_array($select)):
            return $select;
        else:
            return 0;
        endif;
    }

    function getImagem()
    {
        $select = DB\Mysql::select(
            "SELECT * FROM artigos_imagem WHERE id_artigo = :id_artigo",
            [
                'id_artigo' => $this->idArtigo
            ]
        );
        if(is_array($select)):
            return $select;
        else:
            return 0;
        endif;
    }

}

if (post('getData')):

    $data = new Home();
    eco($data->getData(), true);
    exit();

elseif(post('getCategoria')):

    $data = new Home();
    eco($data->getCategoria(), true);
    exit();

elseif(post('getDestaque')):

    $data = new Home();
    eco($data->getDestaque(), true);
    exit();

elseif(post('getDataCategoria')):

    $data = new Home();
    eco($data->getDataCategoria(), true);
    exit();

elseif (post('getSubcategoria')):
    
    $data = new Home();
    eco($data->getSubcategoria(), true);
    exit();

elseif (post('getDataSubcategoria')):

    $data = new Home();
    eco($data->getDataSubcategoria(), true);
    exit();

elseif(post('getSearchData')):

    $data = new Home();
    eco($data->getSearchData(), true);
    exit();

elseif(post('getQueryData')):

    $data = new Home();
    eco($data->getQueryData(), true);
    exit();

elseif (post('getDataId')):

    $data = new Home();
    eco($data->getDataId(), true);
    exit();

elseif (post('getImagem')):

    $data = new Home();
    eco($data->getImagem(), true);
    exit();

elseif (post('number_format')):

    $data = new Home();
    eco($data->numberFormat(), true);
    exit();

elseif(post('add_cart')):

    $data = new Home();
    eco($data->addCart(), true);
    exit();

elseif(post('remove_cart')):

    $data = new Home();
    eco($data->remove_cart(), true);
    exit();

elseif(post('verifique')):

    $data = new Home();
    eco($data->verifique(), true);
    exit();

endif;
    
?>