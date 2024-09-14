<div>
    <?php
        //Comentarios
        $comentarios = new Comentarios();
        $comentarios->post = get('comment', false);
        $comentarios->getComentarios();
    ?>
</div>