<?php

include_once '../conexao.php';

Class Comentario{

    private $id, $id_post, $comentario_id, $user_id, $comment_nome, $comentario, $registo;

	function __construct()
	{
        $this->comentario_id = post('comentario_id', false) 
        ? filterVar(post('comentario_id'))  
        : DEFAULT_INT;

        $this->user_id       = post('user_id', false)
        ? filterVar(post('user_id')) 
        : DEFAULT_INT;
    
        $this->comment_nome  = post('comment_nome', false)
        ? filterVar(post('comment_nome')) 
        : 'Anônimo';

        $this->id_post       = post('id_post', false)
        ? filterVar(post('id_post'))  
        : '';

        $this->comentario    = post('comentario', false)
        ? filterVar(post('comentario'))  
        : '';

        $this->registo       = date('d/m/Y').' ás '.date('H:m');

	}

    function setComent() //Adicionar comentario
    {
        $insert = DB\Mysql::insert(
            'INSERT blog_comentarios (id_post, id_usuario, nome, comentario, registo) VALUES (:id_post, :user_id, :nome, :comentario, :registo)',
            [
                'id_post'    => $this->id_post,
                'user_id'    => $this->user_id,
                'nome'       => $this->comment_nome,
                'comentario' => $this->comentario,
                'registo'    => $this->registo,
            ]
        );
        return $insert;
    }

    function setSubComent() //Adicionar subcomentario
    {
        $insert = DB\Mysql::insert(
            'INSERT blog_sub_comentarios (id_comentario, id_usuario, nome, comentario, registo) VALUES (:id_comentario, :user_id, :nome, :comentario, :registo)',
            [
                'id_comentario' => $this->comentario_id,
                'user_id'       => $this->user_id,
                'nome'          => $this->comment_nome,
                'comentario'    => $this->comentario,
                'registo'       => $this->registo,
            ]
        );
        return $insert;
    }

    function removeComent()
    {
        $delete = DB\Mysql::delete(
            'DELETE FROM blog_comentarios WHERE id = :id',
            [
                'id' => $this->comentario_id
            ]
        );

        if(is_numeric($delete) && $delete > 0){
            DB\Mysql::delete(
                'DELETE FROM blog_sub_comentarios WHERE id_comentario = :id_comentario',
                [
                    'id_comentario' => $this->comentario_id
                ]
            );    
            return 1;
        }else{
            return 'Não foi possível eliminar o seu comentario!';
        }

    }

    function removeSubComent()
    {
        $delete = DB\Mysql::delete(
            'DELETE FROM blog_sub_comentarios WHERE id = :id',
            [
                'id' => $this->comentario_id
            ]
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return 'Não foi possível eliminar o seu comentario!';
        }

    }

}

if(post('comment_add')):

    $data = new Comentario();
    eco($data->setComent(), true);
    exit();

elseif(post('comment_remove')):
    
    $data = new Comentario();
    eco($data->removeComent(), true);
    exit();

elseif(post('sub_comment_add')):

    $data = new Comentario();
    eco($data->setSubComent(), true);
    exit();

elseif(post('sub_comment_remove')):

    $data = new Comentario();
    eco($data->removeSubComent(), true);
    exit();

endif;

?>