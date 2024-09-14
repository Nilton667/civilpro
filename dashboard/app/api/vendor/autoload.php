<?php
    require_once __DIR__ . '/fusion/fusionPhp/essential.php';
    require_once __DIR__ . '/fusion/fusionPhp/utility.php';
    require_once __DIR__ . '/fusion/fusionPhp/component.php';
    require_once __DIR__ . '/fusion/fusionPhp/db.php';

    //Json obrigatorio
    if(post('header') == 'application/json'):
        header('Content-Type: application/json; charset=UTF-8');   
    endif;
?>