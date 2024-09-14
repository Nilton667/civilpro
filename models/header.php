<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light shadow p-0">
  <div class="container-fluid">
    <button class="navbar-toggler rigth" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="./"><img height="50" class="p-1" src="publico/img/logo/logo.png"></a>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item p-2 <?= trim(current($get_current_page)) != '' ? '' : 'active'; ?>">
                <a class="nav-link" href="./"><i class="las la-home"></i> Início</a>
            </li>
            <li class="nav-item p-2">
                <a class="nav-link" href="<?= trim(current($get_current_page)) == '' ? '#sobre' : './#sobre'; ?>"><i class="las la-user-check"></i> Sobre Nós</a>
            </li>
            <li class="nav-item p-2 <?= trim(current($get_current_page)) == 'servicos' ? 'active' : ''; ?>">
                <a class="nav-link" href="./servicos"><i class="las la la-concierge-bell"></i> Serviços</a>
            </li>
            <li class="nav-item dropdown <?= current($get_current_page) == 'ledyboy' || current($get_current_page) == 'tleva' || current($get_current_page) == 'acabamentos' ? 'active' : ''; ?>">
                <a class="nav-link m-2" href="javascript:void('Serviços')" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="las la la-briefcase"></i> Outros Serviços
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="./ledyboy">LedyBoy</a></li>
                    <li><a class="dropdown-item" href="./tleva">T'Leva</a></li>
                    <li><a class="dropdown-item" href="./acabamentos">Acabamentos e Acessórios</a></li>
                </ul>
            </li>
            <li class="nav-item p-2 <?= trim(current($get_current_page)) == 'galeria' ? 'active' : ''; ?>">
                <a class="nav-link" href="./galeria"><i class="las la-image"></i> Galeria</a>
            </li>
            <li class="nav-item p-2 <?= trim(current($get_current_page)) == 'contacto' ? 'active' : ''; ?>">
                <a class="nav-link" href="./contacto"><i class="las la-phone"></i> Contacto</a>
            </li>
        </ul>
        <div class="d-flex mb-2 mb-md-0 ms-2 me-2">
            <input class="form-control me-2 search-input" name="s" type="search" placeholder="Filtrar Resultados" aria-label="Search">
            <button class="btn btn-outline-danger search-input-button" type="buttom">Pesquisar</button>
        </div>
    </div>
  </div>
</nav>
<div style="height: 60px;"></div>