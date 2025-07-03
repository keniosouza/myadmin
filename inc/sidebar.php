<!-- Sidebar -->
<ul class="navbar-nav sidebar-custom-color sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home" target="_top">
        <!-- <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cogs"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><span id="appTitleTop">...</span><sup id="appVersionTop"></sup></div> -->
        <img src="img/logo.png" class="img-fluid" />
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Módulos
    </div>

    <?php if( (isset($_SESSION['USERSCOMPANYID'])) && ($_SESSION['USERSCOMPANYID'] == 0) ){?>

        <!-- Empresas -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="far fa-building"></i>
                <span>Empresas</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Selecione uma opção:</h6>
                    <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=company_datagrid&TABLE=company', '#loadContent', true, '', 0, '', 'Carregando as empresas cadastradas', 'random', 'circle', 'sm', true);">Empresas</a>
                    <a class="collapse-item" href="#" onclick="request('FOLDER=view&TABLE=company&ACTION=company_form', '#loadContent', true, '', 0, '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">Cadastrar</a>            
                </div>
            </div>
        </li>

    <?php } ?>

    <!-- Adminsitrativo -->
    <li class="nav-item">

        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdministrative" aria-expanded="true" aria-controls="collapseAdministrative">

            <i class="fas fa-user-shield"></i>
            <span>

                Administrativo

            </span>

        </a>

        <div id="collapseAdministrative" class="collapse" aria-labelledby="headingsAdministrative" data-parent="#accordionSidebar">

            <div class="bg-white py-2 collapse-inner rounded">

                <h6 class="collapse-header">

                    Selecione uma opção:

                </h6>

                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=users_datagrid&TABLE=users', '#loadContent', true, '', 0, '', 'Carregando usuários cadastrados', 'random', 'circle', 'sm', true);">

                    Usuários

                </a>

                <a class="collapse-item" href="#" onclick="request('FOLDER=VIEW&TABLE=modules&ACTION=modules_datagrid', '#loadContent', true, '', 0, '', 'Carregando registros', 'random', 'circle', 'sm', true);">

                    Modulos

                </a>

                <a class="collapse-item" href="#" onclick="request('FOLDER=VIEW&TABLE=modules_acls&ACTION=modules_acls_datagrid', '#loadContent', true, '', 0, '', 'Carregando registros', 'random', 'circle', 'sm', true);">

                    Controles de Acesso

                </a>

            </div>

        </div>

    </li>

    <!-- Financeiro -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFinancial"
            aria-expanded="true" aria-controls="collapseFinancial">
            <i class="fas fa-dollar-sign"></i>
            <span>Financeiro</span>
        </a>
        <div id="collapseFinancial" class="collapse" aria-labelledby="headingFinancial"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Selecione uma opção:</h6>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=financial_movements_datagrid&TABLE=financial_movements', '#loadContent', true, '', 0, '', 'Carregando movimentações cadastradas', 'random', 'circle', 'sm', true);">Movimentações</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=financial_consolidations_datagrid&TABLE=financial_consolidations', '#loadContent', true, '', 0, '', 'Preparando formulário', 'random', 'circle', 'sm', true);">Consolidação</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=financial_movements_notpaid_datagrid&TABLE=financial_movements', '#loadContent', true, '', 0, '', 'Carregando moveimentações', 'random', 'circle', 'sm', true);">Check Sicoob</a>                
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">Orçamentos</a>                
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_commissions_datagrid', '#loadContent', true, '', '', '', 'Carregando comissões cadastradas', 'blue', 'circle', 'sm', true)">Comissões</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=financial_movements_form_report&TABLE=financial_movements', '#loadContent', true, '', 0, '', 'Carregando formulário', 'random', 'circle', 'sm', true);">Relatórios</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=financial_accounts_datagrid&TABLE=financial_accounts', '#loadContent', true, '', 0, '', 'Carregando contas cadastradas', 'random', 'circle', 'sm', true);">Contas</a>                        
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=financial_entries_datagrid&TABLE=financial_entries', '#loadContent', true, '', 0, '', 'Carregando entradas cadastradas', 'random', 'circle', 'sm', true);">Entradas</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=financial_outputs_datagrid&TABLE=financial_outputs', '#loadContent', true, '', 0, '', 'Carregando saídas cadastradas', 'random', 'circle', 'sm', true);">Saídas</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=financial_categories_datagrid&TABLE=financial_categories', '#loadContent', true, '', 0, '', 'Carregando categorias cadastradas', 'random', 'circle', 'sm', true);">Categorias</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=financial_readjustments_datagrid&TABLE=financial_readjustments', '#loadContent', true, '', 0, '', 'Carregando reajustes', 'random', 'circle', 'sm', true);">Reajuste</a>
            </div>
        </div>
    </li>

    <!-- GED -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDocuments"
            aria-expanded="true" aria-controls="collapseDocuments">
            <i class="far fa-file-alt"></i>
            <span>Documentos</span>
        </a>
        <div id="collapseDocuments" class="collapse" aria-labelledby="headingDocuments"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Selecione uma opção:</h6>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=documents_datagrid&TABLE=documents', '#loadContent', true, '', 0, '', 'Carregando documentos cadastrados', 'random', 'circle', 'sm', true);">Documentos</a>            
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=documents_categorys_datagrid&TABLE=documents_categorys', '#loadContent', true, '', 0, '', 'Carregando categorias de documentos cadastrados', 'random', 'circle', 'sm', true);">Categorias</a>            
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=documents_categorys_tags_datagrid&TABLE=documents_categorys_tags', '#loadContent', true, '', 0, '', 'Carregando tags das categorias de documentos cadastrados', 'random', 'circle', 'sm', true);">Marcações</a>            
            </div>
        </div>
    </li>

    <!-- Agendamento -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSchedule"
            aria-expanded="true" aria-controls="collapseSchedule">
            <i class="far fa-calendar-alt"></i>
            <span>Agenda</span>
        </a>
        <div id="collapseSchedule" class="collapse" aria-labelledby="headingSchedule"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Selecione uma opção:</h6>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=schedules_datagrid&TABLE=schedules', '#loadContent', true, '', 0, '', 'Carregando agendas cadastradas', 'random', 'circle', 'sm', true);">Agendas</a>            
            </div>
        </div>
    </li>

    <!-- Clientes -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClients"
            aria-expanded="true" aria-controls="collapseClients">
            <i class="far fa-address-card"></i>
            <span>Clientes</span>
        </a>
        <div id="collapseClients" class="collapse" aria-labelledby="headingClients"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Selecione uma opção:</h6>
                <a class="collapse-item" href="#" onclick="request('FOLDER=view&ACTION=clients_datagrid&TABLE=clients', '#loadContent', true, '', 0, '', 'Carregando clientes cadastrados', 'random', 'circle', 'sm', true);">Clientes</a>            
            </div>
        </div>
    </li>

    <li class="nav-item">

        <a class="nav-link" type="button" onclick="request('FOLDER=view&TABLE=products&ACTION=products_datagrid', '#loadContent', true, '', 0, '', 'Carregando produtos cadastrados', 'random', 'circle', 'sm', true);">

            <i class="fas fa-box"></i>

            <span>

                Produtos

            </span>

        </a>

    </li>

    <!-- Chamados -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCall" 
            aria-expanded="true" aria-controls="collapseCall">
            <i class="fas fa-tasks"></i>
            <span>Chamados</span>
        </a>
        <div id="collapseCall" class="collapse" aria-labelledby="headingsCall" 
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Selecione uma opção:</h6>
                <a class="collapse-item" href="#" onclick="request('FOLDER=VIEW&TABLE=CALLS_LEVELS&ACTION=CALLS_LEVELS_DATAGRID', '#loadContent', true, '', 0, '', 'Carregando registros', 'random', 'circle', 'sm', true);">Níveis</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=VIEW&TABLE=CALLS_PRIORITIES&ACTION=CALLS_PRIORITIES_DATAGRID', '#loadContent', true, '', 0, '', 'Carregando registros', 'random', 'circle', 'sm', true);">Prioridades</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=VIEW&TABLE=CALLS_TYPES&ACTION=CALLS_TYPES_DATAGRID', '#loadContent', true, '', 0, '', 'Carregando registros', 'random', 'circle', 'sm', true);">Tipos</a>
                <a class="collapse-item" href="#" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DATAGRID', '#loadContent', true, '', 0, '', 'Carregando registros', 'random', 'circle', 'sm', true);">Chamados</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->