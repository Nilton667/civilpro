<?php

  //Informações de cabeçario

  $header_title       = '';
  $header_description = '';

  $get_current_page = explode('/', get('url', false) ? get('url') : '');

  $is_home            = false; 

  switch (trim(current($get_current_page))):

    case 'contacto':
      $header_title       = 'Contacto';
      $header_description = 'Email: geral@civilpro.co.ao, Tel: (+244) 933 006 565.';
      break;

    case 'sobre':
      $header_title       = 'Sobre';
      $header_description = 'Somos uma empresa angolana de direito privado, criado com capital próprio registado na Conservatório do Registo Comercial de Luanda 2ª Secção do Guiché Único de Empresa nº 1924/14. Com sede em Luanda, Rua Major Cahangulo, Bairro Patrice Lumumba, Distrito Urbano da Ingombota e filial na Província do Uíge com o Número de Identificação Fiscal 5417283525.';
      break;

    case 'termos':
      $header_title       = 'Termos de serviços & privacidade';
      $header_description = 'A sua privacidade é importante para nós. É política da CivilPro Lda, respeitar a sua privacidade em relação a qualquer informação sua que possamos coletar no site.';
      break;

    case 's':
      $header_title       = isset($get_current_page[1]) && $get_current_page[1] != '' ? trim($get_current_page[1]) : 'Pesquisar';
      $header_description = 'Resultados de Pesquisa.';
      break;

    case 'servicos':
      $header_title       = 'Serviços';
      $header_description = '';
    break;

    case 'tleva':
      $header_title       = "T'Leva";
      $header_description = '';
    break;

    case 'ledyboy':
      $header_title       = 'LedyBoy';
      $header_description = '';
    break;

    case 'acabamentos':
      $header_title       = 'Acabamentos e Acessórios';
      $header_description = '';
    break;

    case 'categoria':
      $data    = new Util();
      $data    = $data->getNameCategoria(isset($get_current_page[1]) ? trim($get_current_page[1]) : '');
      $data    = json_decode($data);

      $data_verify = isset($get_current_page[1]) && trim($get_current_page[1]) != '' ? trim($get_current_page[1]) : 'Todas categorias';

      $header_title       = is_array($data) ? $data[0]->categoria : $data_verify;
      $header_description = is_array($data) ? $data[0]->descricao : 'n/a';
      break;

    case 'categorias':
      $header_title       = 'Categorias';
      $header_description = 'Esteja livre para explorar a nossa vasta gama de tópicos.';
      break;

    case 'galeria':
      $header_title       = 'Galeria';
      $header_description = '';
      break;

    case 'post':
      $data    = new Util();
      $data    = $data->getDataPost(isset($get_current_page[1]) ? trim($get_current_page[1]) : '');
      $data    = json_decode($data);

      $post    = isset($data[0]->id) ? true : false;

      $header_title       = $post ? $data[0]->titulo : 'n/a';
      $header_description = $post ? limitarTexto(filterVar($data[0]->descricao), 200) : 'Nenhum resultado encontrado!';
      break;

    case '':
      $header_title       = 'CivilPro';
      $header_description = 'Somos uma empresa angolana de direito privado, criado com capital próprio registado na Conservatório do Registo Comercial de Luanda 2ª Secção do Guiché Único de Empresa nº 1924/14. Com sede em Luanda, Rua Major Cahangulo, Bairro Patrice Lumumba, Distrito Urbano da Ingombota e filial na Província do Uíge com o Número de Identificação Fiscal 5417283525.';
      $is_home = true;
      break;

    default:
      $header_title       = '404';
      $header_description = 'Document error 404';
      break;

  endswitch;

?>