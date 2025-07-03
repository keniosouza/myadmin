<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\Calls;
use \vendor\controller\calls\CallsValidate;
use \vendor\model\CallsClients;
use \vendor\model\CallsUsers;
use \vendor\model\CallsMessages;
use \vendor\model\CallsProducts;
use \vendor\model\CallsDrafts;
use \vendor\model\CallsActivities;

/** Instânciamento de classes */
$Main = new Main();
$Calls = new Calls();
$CallsValidate = new CallsValidate();
$CallsClients = new CallsClients();
$CallsUsers = new CallsUsers();
$CallsMessages = new CallsMessages();
$CallsProducts = new CallsProducts();
$CallsDrafts = new CallsDrafts();
$CallsActivities = new CallsActivities();

/** Tratamento dos dados de entrada */
$CallsValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));
$CallsValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);

/** Verifico se existe registro */
if ($CallsValidate->getCallId() > 0) {

    /** Busca de registro */
    $resultCalls = $Calls->load($CallsValidate->getCallId());
    $resultCallsClients = $CallsClients->All($CallsValidate->getCallId(), $CallsValidate->getCompanyId());
    $resultCallsUsers = $CallsUsers->All($CallsValidate->getCallId(), $CallsValidate->getCompanyId());
    $resultCallsProducts = $CallsProducts->All($CallsValidate->getCallId(), $CallsValidate->getCompanyId());
    $resultCallsDrafts = $CallsDrafts->All($CallsValidate->getCallId(), $CallsValidate->getCompanyId());
    $resultCallsActivities = $CallsActivities->All($CallsValidate->getCallId(), $CallsValidate->getCompanyId());

    /** Decodifico o texto */
    $resultCalls->description = base64_decode($resultCalls->description);

}

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>

            <i class="fas fa-file-word mr-1"></i>

            Chamados

        </strong>

        /Detalhes/

        <button type="button" class="btn btn-primary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DATAGRID', '#loadContent', true, '', '', '', '', '', 'circle', 'md', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12">

    <div class="card shadow-sm border animate slideIn">

        <div class="card-body">

            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">

                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">

                    <a class="nav-link active" id="pills-1-tab" data-toggle="pill" href="#pills-1" role="tab" aria-controls="pills-1" aria-selected="true">

                        <i class="fas fa-info mr-1"></i>1º - Inicio

                    </a>

                </li>

                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">

                    <a class="nav-link" id="pills-8-tab" data-toggle="pill" href="#pills-8" role="tab" aria-controls="pills-8" aria-selected="false">

                        <i class="far fa-file mr-1"></i>2º - Documentos

                    </a>

                </li>

                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">

                    <a class="nav-link" id="pills-2-tab" data-toggle="pill" href="#pills-2" role="tab" aria-controls="pills-2" aria-selected="false">

                        <i class="fas fa-users mr-1"></i>3º - Clientes

                    </a>

                </li>

                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">

                    <a class="nav-link" id="pills-3-tab" data-toggle="pill" href="#pills-3" role="tab" aria-controls="pills-3" aria-selected="false">

                        <i class="fas fa-user-friends mr-1"></i>4º - Operadores

                    </a>

                </li>

                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">

                    <a class="nav-link" id="pills-7-tab" data-toggle="pill" href="#pills-7" role="tab" aria-controls="pills-7" aria-selected="false">

                        <i class="fas fa-box mr-1"></i>5º - Produtos

                    </a>

                </li>

                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">

                    <a class="nav-link" id="pills-9-tab" data-toggle="pill" href="#pills-9" role="tab" aria-controls="pills-9" aria-selected="false">

                        <i class="fas fa-hiking mr-1"></i>6º - Atividades

                    </a>

                </li>

                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">

                    <a class="nav-link" id="pills-5-tab" data-toggle="pill" href="#pills-5" role="tab" aria-controls="pills-5" aria-selected="false">

                        <i class="fas fa-comment-dots mr-1"></i>7º - Feedback's

                    </a>

                </li>

                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">

                    <a class="nav-link" id="pills-6-tab" data-toggle="pill" href="#pills-6" role="tab" aria-controls="pills-6" aria-selected="false">

                        <i class="fas fa-film mr-1"></i>8º - Histórico

                    </a>

                </li>

            </ul>

            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade active show" id="pills-1" role="tabpanel" aria-labelledby="pills-1-tab">

                    <h6 class="card-title text-muted">

                        <i class="fas fa-info mr-1"></i>Inicio

                    </h6>

                    <div class="card shadow-sm bg-gray mb-3">

                        <div class="card-body">

                            <div class="row grid-divider">

                                <div class="col-md">

                                    <h6 class="mt-0 mb-0">

                                        Tipo:

                                    </h6>

                                    <h6>

                                        <strong>

                                            <?php echo @(string)$resultCalls->description_call_type?>

                                        </strong>

                                    </h6>

                                </div>

                                <div class="col-md">

                                    <h6 class="mt-0 mb-0">

                                        Nível:

                                    </h6>

                                    <h6>

                                        <strong>

                                            <?php echo @(string)$resultCalls->description_call_level?>

                                        </strong>

                                    </h6>

                                </div>

                                <div class="col-md">

                                    <h6 class="mt-0 mb-0">

                                        Prioridade:

                                    </h6>

                                    <h6>

                                        <strong>

                                            <?php echo @(string)$resultCalls->description_call_priority?>

                                        </strong>

                                    </h6>

                                </div>

                                <div class="col-md">

                                    <h6 class="mt-0 mb-0">

                                        Execução:

                                    </h6>

                                    <h6>

                                        <strong>

                                            <?php echo date('d/m/Y', strtotime(@(string)$resultCalls->date_execution))?>

                                        </strong>

                                    </h6>

                                </div>

                                <div class="col-md">

                                    <h6 class="mt-0 mb-0">

                                        Conclusão:

                                    </h6>

                                    <h6>

                                        <strong>

                                            <?php

                                            /** Verifico o status do registro */
                                            if (empty(@(string)$resultCalls->date_close))
                                            {?>

                                                Não possui

                                            <?php }else{?>

                                                <?php echo date('d/m/Y', strtotime(@(string)$resultCalls->date_close))?>

                                            <?php }?>

                                        </strong>

                                    </h6>

                                </div>

                            </div>

                            <div class="row grid-divider">

                                <div class="col-md">

                                    <h6 class="mt-0 mb-0">

                                        Nome:

                                    </h6>

                                    <h6>

                                        <strong>

                                            <?php echo @(string)$resultCalls->name?>

                                        </strong>

                                    </h6>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card shadow-sm">

                        <div class="card-body">

                            <h6 class="mt-0 mb-0">

                                Descrição:

                            </h6>

                            <div class="card-text" style="overflow-y: scroll; max-height: 400px">

                                <?php echo @(string)$resultCalls->description?>

                            </div>

                        </div>

                    </div>

                    <div class="mt-3">

                        <div class="row mt-2">

                            <div class="col-md-10 mx-auto">

                                <div class="card bg-danger text-light border-danger">

                                    <div class="card-body text-center">

                                        <h6 class="card-title">

                                            <strong>

                                                <?php

                                                /** Verifico o status do registro */
                                                if (empty(@(string)$result->date_close))
                                                {?>

                                                    Problema Central em Andamento:

                                                <?php }else{?>

                                                    Problema Central Encerrado:

                                                <?php }?>

                                            </strong>

                                        </h6>

                                        <?php echo @(string)$resultCalls->name?>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="row mt-2">

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($resultCallsActivities as $keyResultCallsActivities => $result)
                            {?>

                                <div class="col-md d-flex mx-auto">

                                    <div class="card bg-dark text-light border-dark w-100">

                                        <div class="card-body text-center">

                                            <h6 class="card-title">

                                                <strong>

                                                    <?php

                                                    /** Verifico o status do registro */
                                                    if (empty(@(string)$result->date_close))
                                                    {?>

                                                        <span class="badge badge-danger">

                                                            Causa em Andamento:

                                                        </span>

                                                    <?php }else{?>

                                                        <span class="badge badge-success">

                                                            Causa Encerrada:

                                                        </span>

                                                    <?php }?>

                                                </strong>

                                            </h6>

                                            <?php echo @(string)$result->name?>

                                        </div>

                                    </div>

                                </div>

                            <?php } ?>

                        </div>

                    </div>

                    <div class="row mt-3">

                        <?php

                        /** Verifico o status do registro */
                        if (empty(@(string)$resultCalls->date_close))
                        {

                            /** Crio o nome da função */
                            $function = 'function_calls_close_' . @(int)$CallsValidate->getCallId() . '_' . rand(1, 1000);

                            ?>

                            <div class="col-md-12">

                                <button class="btn btn-primary btn-block" type="button" onclick="modalPage(true, 0, 0,   'Atenção', 'Deseja realmente remover o registro?', '', 'question', <?php echo @(string)$function?>)">

                                    <i class="fas fa-lock mr-1"></i>Encerrar Chamado

                                </button>

                                <script type="text/javascript">

                                    /** Carrega a função de logout */
                                    let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS&ACTION=CALLS_CLOSE&call_id=<?php echo @(int)$CallsValidate->getCallId()?>', '', true, '', 0, '', 'Encerrando chamado', 'random', 'circle', 'sm', true)";

                                </script>

                            </div>

                        <?php }else{

                            /** Crio o nome da função */
                            $function = 'function_calls_close_' . @(int)$CallsValidate->getCallId() . '_' . rand(1, 1000);

                            ?>

                            <div class="col-md-12">

                                <button class="btn btn-primary btn-block" type="button" onclick="modalPage(true, 0, 0,   'Atenção', 'Deseja realmente remover o registro?', '', 'question', <?php echo @(string)$function?>)">

                                    <i class="fas fa-lock-open mr-1"></i>Reativar Chamado

                                </button>

                                <script type="text/javascript">

                                    /** Carrega a função de logout */
                                    let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS&ACTION=CALLS_OPEN&call_id=<?php echo @(int)$CallsValidate->getCallId()?>', '', true, '', 0, '', 'Reativando chamado', 'random', 'circle', 'sm', true)";

                                </script>

                            </div>

                        <?php }?>

                    </div>

                </div>

                <div class="tab-pane fade" id="pills-8" role="tabpanel" aria-labelledby="pills-8-tab">

                    <div class="row">

                        <div class="col-md-6">

                            <h6 class="card-title text-muted">

                                <i class="far fa-file mr-1"></i>Documentos - <span class="badge badge-primary"><?php echo count($resultCallsDrafts)?></span>

                            </h6>

                        </div>

                        <?php

                        /** Verifico o status do registro */
                        if (empty(@(string)$resultCalls->date_close))
                        { ?>

                            <div class="col-md-6 text-right">

                                <a class="btn btn-primary btn-sm" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_DRAFTS&ACTION=CALLS_DRAFTS_FORM&CALL_ID=<?php echo @(int)$resultCalls->call_id?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                    <i class="fas fa-plus mr-1"></i>Novo

                                </a>

                            </div>

                        <?php }?>

                    </div>

                    <?php

                    /** Verifico se existem registros */
                    if (@(int)count($resultCallsDrafts) > 0)
                    { ?>

                        <div class="row">

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($resultCallsDrafts as $keyResultCallsDrafts => $result)
                            {

                                /** Crio o nome da função */
                                $function = 'function_delete_calls_drafts_' . @(int)$keyResultCallsDrafts . '_' . rand(1, 1000);

                                ?>

                                <div class="col-md-3 mb-4 d-flex">

                                    <div class="card bg-light text-black shadow-sm w-100">

                                        <div class="card-body">

                                            <?php echo @(string)$result->name?>

                                        </div>

                                        <div class="card-footer border-0">

                                            <div class="dropdown">

                                                <button class="btn btn-primary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">

                                                    <i class="fas fa-cog mr-1"></i>Operações

                                                </button>

                                                <div class="dropdown-menu w-100 shadow-sm" aria-labelledby="dropdownMenuButton">

                                                    <a class="dropdown-item" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_DRAFTS&ACTION=CALLS_DRAFTS_DETAILS&CALL_ID=<?php echo @(int)$resultCalls->call_id?>&CALL_DRAFT_ID=<?php echo @(int)$result->call_draft_id; ?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                                        <span class="badge badge-primary mr-1">

                                                            <i class="fas fa-eye"></i>

                                                        </span>

                                                        Detalhes

                                                    </a>

                                                    <div class="dropdown-divider"></div>

                                                    <a class="dropdown-item" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_DRAFTS&ACTION=CALLS_DRAFTS_FORM_TEXT&CALL_ID=<?php echo @(int)$resultCalls->call_id?>&CALL_DRAFT_ID=<?php echo @(int)$result->call_draft_id; ?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                                        <span class="badge badge-primary mr-1">

                                                            <i class="fas fa-pencil-alt"></i>

                                                        </span>

                                                        Alterar

                                                    </a>

                                                    <div class="dropdown-divider"></div>

                                                    <a class="dropdown-item" type="button" onclick="modalPage(true, 0, 0, 'Atenção', 'Deseja realmente remover o registro?', '', 'question', <?php echo @(string)$function?>)">

                                                        <span class="badge badge-danger mr-1">

                                                            <i class="fas fa-trash"></i>

                                                        </span>

                                                        Excluir

                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <script type="text/javascript">

                                    /** Carrega a função de logout */
                                    let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS_DRAFTS&ACTION=CALLS_DRAFTS_DELETE&CALL_ID=<?php echo @(int)$result->call_id?>&CALL_DRAFT_ID=<?php echo @(int)$result->call_draft_id?>', '', true, '', 0, '', 'Removendo produto', 'yellow', 'circle', 'sm', true)";

                                </script>

                            <?php }?>

                        </div>

                    <?php }else{ ?>

                        <div class="card shadow-sm mt-2 bg-light">

                            <div class="card-body text-center">

                                <img src="img/404.jpg" class="img-fluid mb-3" width="200px" alt="">

                                <div class="row">

                                    <div class="col-md-6 mx-auto">

                                        <h2 class="card-title text-center text-muted">

                                            <strong>

                                                Não foram localizadas minutas para este chamado

                                            </strong>

                                        </h2>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php }?>

                </div>

                <div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab">

                    <div class="row">

                        <div class="col-md-6">

                            <h6 class="card-title text-muted">

                                <i class="fas fa-users mr-1"></i>Clientes - <span class="badge badge-primary"><?php echo count($resultCallsClients)?></span>

                            </h6>

                        </div>

                        <?php

                        /** Verifico o status do registro */
                        if (empty(@(string)$resultCalls->date_close))
                        { ?>

                            <div class="col-md-6 text-right">

                                <a class="btn btn-primary btn-sm" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_CLIENTS&ACTION=CALLS_CLIENTS_FORM&CALL_ID=<?php echo @(int)$resultCalls->call_id?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                    <i class="fas fa-plus mr-1"></i>Novo

                                </a>

                            </div>

                        <?php }?>

                    </div>

                    <?php

                    /** Verifico se existem registros */
                    if (@(int)count($resultCallsClients) > 0)
                    { ?>

                        <table class="table table-bordered table-borderless table-hover bg-white shadow-sm border mt-2">

                            <thead>
                            <tr>

                                <th class="text-center">

                                    Nº

                                </th>

                                <th>

                                    Nome

                                </th>

                                <?php

                                /** Verifico o status do registro */
                                if (empty(@(string)$resultCalls->date_close))
                                { ?>

                                    <th class="text-center">

                                        Operações

                                    </th>

                                <?php }?>

                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($resultCallsClients as $keyResultCallsClients => $result)
                            {

                                /** Crio o nome da função */
                                $function = 'function_delete_calls_clients_' . @(int)$keyResultCallsClients . '_' . rand(1, 1000);

                                ?>

                                <tr class="border-top">

                                    <td class="text-center">

                                        <?php echo @(int)$result->call_client_id; ?>

                                    </td>

                                    <td>

                                        <?php echo @(string)$result->fantasy_name; ?>

                                    </td>

                                    <?php

                                    /** Verifico o status do registro */
                                    if (empty(@(string)$resultCalls->date_close))
                                    { ?>

                                        <td class="text-center">

                                            <div role="form" id="formProductsCompanies<?php echo @(int)$keyResultCallsClients?>" class="btn-group dropleft">

                                                <button class="btn btn-primary dropdown-toggle" type="button" id="buttonDropdown_<?php echo @(int)$keyResultCallsClients?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                                    <i class="fas fa-cog"></i>

                                                </button>

                                                <div class="dropdown-menu shadow-sm" aria-labelledby="dropdownMenuButton">

                                                    <a type="button" class="dropdown-item" data-toggle="collapse" href="#collapse_calls_clients_<?php echo @(int)$keyResultCallsClients?>">

                                                        <span class="badge badge-primary mr-1">

                                                            <i class="fas fa-eye"></i>

                                                        </span>

                                                        Detalhes

                                                    </a>

                                                    <div class="dropdown-divider"></div>

                                                    <a type="button" class="dropdown-item" onclick="modalPage(true, 0, 0, 'Atenção', 'Deseja realmente remover o registro?', '', 'question', <?php echo @(string)$function?>)">

                                                        <span class="badge badge-danger mr-1">

                                                            <i class="fas fa-fire-alt"></i>

                                                        </span>

                                                        Excluir

                                                    </a>

                                                </div>

                                                <script type="text/javascript">

                                                    /** Carrega a função de logout */
                                                    let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS_CLIENTS&ACTION=CALLS_CLIENTS_DELETE&CALL_ID=<?php echo @(int)$result->call_id?>&CALL_CLIENT_ID=<?php echo @(int)$result->call_client_id?>', '', true, '', 0, '', 'Removendo cliente', 'yellow', 'circle', 'sm', true)";

                                                </script>

                                            </div>

                                        </td>

                                    <?php }?>

                                </tr>

                                <tr class="collapse" id="collapse_calls_clients_<?php echo @(int)$keyResultCallsClients?>">

                                    <td class="border-top bg-gray" colspan="3">

                                        <div class="main-card card shadow-sm">

                                            <div class="card-body">

                                                <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">

                                                    <?php

                                                    /** Pego o histórico existente */
                                                    $history = json_decode($result->history, TRUE);

                                                    /** Listo os acessos realizados */
                                                    foreach ($history as $keyResultHistory => $resultHistory)
                                                    { ?>

                                                        <div class="vertical-timeline-item vertical-timeline-element">

                                                            <div>

                                                                <span class="vertical-timeline-element-icon bounce-in">

                                                                    <i class="badge badge-dot badge-dot-xl <?php echo @(string)$resultHistory['class']?>"> </i>

                                                                </span>

                                                                <div class="vertical-timeline-element-content bounce-in">

                                                                    <h4 class="timeline-title">

                                                                        <?php echo @(string)$resultHistory['title']?> - <?php echo @(string)$resultHistory['user']?>

                                                                    </h4>

                                                                    <p>

                                                                        <?php echo @(string)$resultHistory['description']?>

                                                                        <a href="javascript:void(0);" data-abc="true">

                                                                            <?php echo @(string)$resultHistory['date']?>

                                                                        </a>

                                                                    </p>

                                                                    <span class="vertical-timeline-element-date">

                                                                        <?php echo @(string)$resultHistory['time']?>

                                                                    </span>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    <?php }?>

                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                            </tbody>

                        </table>

                    <?php }else{ ?>

                        <div class="card shadow-sm mt-2 bg-light">

                            <div class="card-body text-center">

                                <img src="img/404.jpg" class="img-fluid mb-3" width="200px" alt="">

                                <div class="row">

                                    <div class="col-md-6 mx-auto">

                                        <h2 class="card-title text-center text-muted">

                                            <strong>

                                                Não foram localizados clientes para este chamado

                                            </strong>

                                        </h2>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php }?>

                </div>

                <div class="tab-pane fade" id="pills-3" role="tabpanel" aria-labelledby="pills-3-tab">

                    <div class="row">

                        <div class="col-md-6">

                            <h6 class="card-title text-muted">

                                <i class="fas fa-user-friends mr-1"></i>Operadores - <span class="badge badge-primary"><?php echo count($resultCallsUsers)?></span>

                            </h6>

                        </div>

                        <?php

                        /** Verifico o status do registro */
                        if (empty(@(string)$resultCalls->date_close))
                        { ?>

                            <div class="col-md-6 text-right">

                                <a class="btn btn-primary btn-sm" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_USERS&ACTION=CALLS_USERS_FORM&CALL_ID=<?php echo @(int)$resultCalls->call_id?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                    <i class="fas fa-plus mr-1"></i>Novo

                                </a>

                            </div>

                        <?php }?>

                    </div>

                    <?php

                    /** Verifico se existem registros */
                    if (@(int)count($resultCallsUsers) > 0)
                    { ?>

                        <table class="table table-bordered table-borderless table-hover bg-white shadow-sm border mt-2">

                            <thead>
                            <tr>

                                <th class="text-center">

                                    Nº

                                </th>

                                <th>

                                    Nome

                                </th>

                                <?php

                                /** Verifico o status do registro */
                                if (empty(@(string)$resultCalls->date_close))
                                { ?>

                                    <th class="text-center">

                                        Operações

                                    </th>

                                <?php }?>

                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($resultCallsUsers as $keyResultCallsUsers => $result)
                            {

                                /** Crio o nome da função */
                                $function = 'function_delete_calls_users_' . @(int)$keyResultCallsUsers . '_' . rand(1, 1000);

                                ?>

                                <tr class="border-top">

                                    <td class="text-center">

                                        <?php echo @(int)$result->call_user_id; ?>

                                    </td>

                                    <td>

                                        <?php echo $Main->decryptData($result->name_first); ?>

                                    </td>

                                    <?php

                                    /** Verifico o status do registro */
                                    if (empty(@(string)$resultCalls->date_close))
                                    { ?>

                                        <td class="text-center">

                                            <div role="form" id="formProductsCompanies<?php echo @(int)$keyResultCallsUsers?>" class="btn-group dropleft">

                                                <button class="btn btn-primary dropdown-toggle" type="button" id="buttonDropdown_<?php echo @(int)$keyResultCallsUsers?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                                    <i class="fas fa-cog"></i>

                                                </button>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    <a type="button" class="dropdown-item" data-toggle="collapse" href="#collapse_calls_users_<?php echo @(int)$keyResultCallsUsers?>">

                                                        <span class="badge badge-primary mr-1">

                                                            <i class="fas fa-eye"></i>

                                                        </span>

                                                        Detalhes

                                                    </a>

                                                    <div class="dropdown-divider"></div>

                                                    <a type="button" class="dropdown-item" onclick="modalPage(true, 0, 0, 'Atenção', 'Deseja realmente remover o registro?', '', 'question', <?php echo @(string)$function?>)">

                                                        <span class="badge badge-danger mr-1">

                                                            <i class="fas fa-fire-alt"></i>

                                                        </span>

                                                        Excluir

                                                    </a>

                                                </div>

                                                <script type="text/javascript">

                                                    /** Carrega a função de logout */
                                                    let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS_USERS&ACTION=CALLS_USERS_DELETE&CALL_ID=<?php echo @(int)$result->call_id?>&CALL_USER_ID=<?php echo @(int)$result->call_user_id?>', '', true, '', 0, '', 'Removendo usuário', 'yellow', 'circle', 'sm', true)";

                                                </script>

                                            </div>

                                        </td>

                                    <?php }?>

                                </tr>

                                <tr class="collapse" id="collapse_calls_users_<?php echo @(int)$keyResultCallsUsers?>">

                                    <td class="border-top bg-gray" colspan="3">

                                        <div class="main-card card shadow-sm">

                                            <div class="card-body">

                                                <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">

                                                    <?php

                                                    /** Pego o histórico existente */
                                                    $history = json_decode($result->history, TRUE);

                                                    /** Listo os acessos realizados */
                                                    foreach ($history as $keyResultHistory => $resultHistory)
                                                    { ?>

                                                        <div class="vertical-timeline-item vertical-timeline-element">

                                                            <div>

                                                                <span class="vertical-timeline-element-icon bounce-in">

                                                                    <i class="badge badge-dot badge-dot-xl <?php echo @(string)$resultHistory['class']?>"> </i>

                                                                </span>

                                                                <div class="vertical-timeline-element-content bounce-in">

                                                                    <h4 class="timeline-title">

                                                                        <?php echo @(string)$resultHistory['title']?> - <?php echo @(string)$resultHistory['user']?>

                                                                    </h4>

                                                                    <p>

                                                                        <?php echo @(string)$resultHistory['description']?>

                                                                        <a href="javascript:void(0);" data-abc="true">

                                                                            <?php echo @(string)$resultHistory['date']?>

                                                                        </a>

                                                                    </p>

                                                                    <span class="vertical-timeline-element-date">

                                                                        <?php echo @(string)$resultHistory['time']?>

                                                                    </span>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    <?php }?>

                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                            </tbody>

                        </table>

                    <?php }else{ ?>

                        <div class="card shadow-sm mt-2 bg-light">

                            <div class="card-body text-center">

                                <img src="img/404.jpg" class="img-fluid mb-3" width="200px" alt="">

                                <div class="row">

                                    <div class="col-md-6 mx-auto">

                                        <h2 class="card-title text-center text-muted">

                                            <strong>

                                                Não foram localizados operadores para este chamado

                                            </strong>

                                        </h2>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php }?>

                </div>

                <div class="tab-pane fade" id="pills-7" role="tabpanel" aria-labelledby="pills-7-tab">

                    <div class="row">

                        <div class="col-md-6">

                            <h6 class="card-title text-muted">

                                <i class="fas fa-box mr-1"></i>Produtos - <span class="badge badge-primary"><?php echo count($resultCallsProducts)?></span>

                            </h6>

                        </div>

                        <?php

                        /** Verifico o status do registro */
                        if (empty(@(string)$resultCalls->date_close))
                        { ?>

                            <div class="col-md-6 text-right">

                                <a class="btn btn-primary btn-sm" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_PRODUCTS&ACTION=CALLS_PRODUCTS_FORM&CALL_ID=<?php echo @(int)$resultCalls->call_id?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                    <i class="fas fa-plus mr-1"></i>Novo

                                </a>

                            </div>

                        <?php }?>

                    </div>

                    <?php

                    /** Verifico se existem registros */
                    if (@(int)count($resultCallsProducts) > 0)
                    { ?>

                        <table class="table table-bordered table-borderless table-hover bg-white shadow-sm border mt-2">

                            <thead>
                            <tr>

                                <th class="text-center">

                                    Nº

                                </th>

                                <th>

                                    Nome

                                </th>

                                <?php

                                /** Verifico o status do registro */
                                if (empty(@(string)$resultCalls->date_close))
                                { ?>

                                    <th class="text-center">

                                        Operações

                                    </th>

                                <?php }?>

                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($resultCallsProducts as $keyResultCallsProducts => $result)
                            {

                                /** Crio o nome da função */
                                $function = 'function_delete_calls_products_' . @(int)$keyResultCallsProducts . '_' . rand(1, 1000);

                                ?>

                                <tr class="border-top">

                                    <td class="text-center">

                                        <?php echo @(int)$result->call_product_id; ?>

                                    </td>

                                    <td>

                                        <?php echo @(string)$result->description; ?>

                                    </td>

                                    <?php

                                    /** Verifico o status do registro */
                                    if (empty(@(string)$resultCalls->date_close))
                                    { ?>

                                        <td class="text-center">

                                            <div role="form" id="formProductsCompanies<?php echo @(int)$keyResultCallsProducts?>" class="btn-group dropleft">

                                                <button class="btn btn-primary dropdown-toggle" type="button" id="buttonDropdown_<?php echo @(int)$keyResultCallsProducts?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                                    <i class="fas fa-cog"></i>

                                                </button>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    <a type="button" class="dropdown-item" data-toggle="collapse" href="#collapse_calls_products_<?php echo @(int)$keyResultCallsProducts?>">

                                                        <span class="badge badge-primary mr-1">

                                                            <i class="fas fa-eye"></i>

                                                        </span>

                                                        Detalhes

                                                    </a>

                                                    <div class="dropdown-divider"></div>

                                                    <a type="button" class="dropdown-item" onclick="modalPage(true, 0, 0, 'Atenção', 'Deseja realmente remover o registro?', '', 'question', <?php echo @(string)$function?>)">

                                                        <span class="badge badge-danger mr-1">

                                                            <i class="fas fa-fire-alt"></i>

                                                        </span>

                                                        Excluir

                                                    </a>

                                                </div>

                                                <script type="text/javascript">

                                                    /** Carrega a função de logout */
                                                    let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS_PRODUCTS&ACTION=CALLS_PRODUCTS_DELETE&CALL_ID=<?php echo @(int)$result->call_id?>&CALL_PRODUCT_ID=<?php echo @(int)$result->call_product_id?>', '', true, '', 0, '', 'Removendo produto', 'yellow', 'circle', 'sm', true)";

                                                </script>

                                            </div>

                                        </td>

                                    <?php }?>

                                </tr>

                                <tr class="collapse" id="collapse_calls_products_<?php echo @(int)$keyResultCallsProducts?>">

                                    <td class="border-top bg-gray" colspan="3">

                                        <div class="main-card card shadow-sm">

                                            <div class="card-body">

                                                <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">

                                                    <?php

                                                    /** Pego o histórico existente */
                                                    $history = json_decode($result->history, TRUE);

                                                    /** Listo os acessos realizados */
                                                    foreach ($history as $keyResultHistory => $resultHistory)
                                                    { ?>

                                                        <div class="vertical-timeline-item vertical-timeline-element">

                                                            <div>

                                                                <span class="vertical-timeline-element-icon bounce-in">

                                                                    <i class="badge badge-dot badge-dot-xl <?php echo @(string)$resultHistory['class']?>"> </i>

                                                                </span>

                                                                <div class="vertical-timeline-element-content bounce-in">

                                                                    <h4 class="timeline-title">

                                                                        <?php echo @(string)$resultHistory['title']?> - <?php echo @(string)$resultHistory['user']?>

                                                                    </h4>

                                                                    <p>

                                                                        <?php echo @(string)$resultHistory['description']?>

                                                                        <a href="javascript:void(0);" data-abc="true">

                                                                            <?php echo @(string)$resultHistory['date']?>

                                                                        </a>

                                                                    </p>

                                                                    <span class="vertical-timeline-element-date">

                                                                        <?php echo @(string)$resultHistory['time']?>

                                                                    </span>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    <?php }?>

                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                            </tbody>

                        </table>

                    <?php }else{ ?>

                        <div class="card shadow-sm mt-2 bg-light">

                            <div class="card-body text-center">

                                <img src="img/404.jpg" class="img-fluid mb-3" width="200px" alt="">

                                <div class="row">

                                    <div class="col-md-6 mx-auto">

                                        <h2 class="card-title text-center text-muted">

                                            <strong>

                                                Não foram localizados produtos para este chamado

                                            </strong>

                                        </h2>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php }?>

                </div>

                <div class="tab-pane fade" id="pills-9" role="tabpanel" aria-labelledby="pills-9-tab">

                    <div class="row">

                        <div class="col-md-8">

                            <h6 class="card-title text-muted">

                                <i class="far fa-file mr-1"></i>Atividades - <span class="badge badge-primary"><?php echo count($resultCallsActivities)?></span>

                                <span class="badge badge-primary">Dentro do Prazo</span> - <span class="badge badge-warning">Dia de Conclusão</span> - <span class="badge badge-danger">Entrega Atrasada</span> - <span class="badge badge-success">Entrega Realizada</span>

                            </h6>

                        </div>

                        <?php

                        /** Verifico o status do registro */
                        if (empty(@(string)$resultCalls->date_close))
                        { ?>

                            <div class="col-md-4 text-right">

                                <a class="btn btn-primary btn-sm" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_ACTIVITIES&ACTION=CALLS_ACTIVITIES_FORM&CALL_ID=<?php echo @(int)$resultCalls->call_id?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                    <i class="fas fa-plus mr-1"></i>Novo

                                </a>

                            </div>

                        <?php }?>

                    </div>

                    <?php

                    /** Verifico se existem registros */
                    if (@(int)count($resultCallsActivities) > 0)
                    { ?>

                        <div class="row mt-2">

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($resultCallsActivities as $keyResultCallsActivities => $result)
                            {

                                /** Crio o nome da função */
                                $function = 'function_delete_calls_activities_' . @(int)$keyResultCallsActivities . '_' . rand(1, 1000);
                                $classCss = null;

                                /** Verifico o tipo de classe css que devo usar */
                                if (date('Y-m-d') < $result->date_expected)
                                {

                                    /** Anteção */
                                    $classCss = 'primary';

                                }
                                elseif (date('Y-m-d') == $result->date_expected)
                                {

                                    /** Perigo */
                                    $classCss = 'warning';

                                }
                                elseif (date('Y-m-d') >= $result->date_expected)
                                {

                                    /** Perigo */
                                    $classCss = 'danger';

                                }

                                /** Verifico se existe da encerramento */
                                if (!empty($result->date_close))
                                {

                                    /** Sucesso */
                                    $classCss = 'success';

                                }

                                ?>

                                <div class="col-md-3 mb-4 d-flex">

                                    <div class="card bg-light text-black shadow-sm w-100 border-<?php echo $classCss?>">

                                        <div class="card-body">

                                            <?php echo @(string)$result->name?>

                                            <div class="text-black-50 small">

                                                Previsto: <?php echo !empty(@(string)$result->date_expected) ? date('d/m/Y', strtotime(@(string)$result->date_expected)) : 'Não possui'?>
                                                <br>
                                                Inicio: <?php echo !empty(@(string)$result->date_start) ? date('d/m/Y', strtotime(@(string)$result->date_start)) : 'Não possui'?>
                                                <br>
                                                Encerramento:<?php echo !empty(@(string)$result->date_close) ? date('d/m/Y', strtotime(@(string)$result->date_close)) : 'Não possui'?>

                                            </div>

                                        </div>

                                        <div class="card-footer border-0">

                                            <div class="dropdown">

                                                <button class="btn btn-<?php echo $classCss?> dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">

                                                    <i class="fas fa-cog mr-1"></i>Operações

                                                </button>

                                                <div class="dropdown-menu w-100 shadow-sm" aria-labelledby="dropdownMenuButton">

                                                    <?php

                                                    /** Verifico o status do registro */
                                                    if (empty(@(string)$resultCalls->date_close))
                                                    { ?>

                                                        <a class="dropdown-item" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_ACTIVITIES_USERS&ACTION=CALLS_ACTIVITIES_USERS_FORM&CALL_ID=<?php echo @(int)$result->call_id?>&CALL_ACTIVITY_ID=<?php echo @(int)$result->call_activity_id?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                                            <span class="badge badge-primary mr-1">

                                                                <i class="fas fa-users"></i>

                                                            </span>

                                                            Operadores

                                                        </a>

                                                        <div class="dropdown-divider"></div>

                                                    <?php }?>

                                                    <a class="dropdown-item" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_ACTIVITIES_USERS&ACTION=CALLS_ACTIVITIES_USERS_DETAILS&CALL_ID=<?php echo @(int)$result->call_id?>&CALL_ACTIVITY_ID=<?php echo @(int)$result->call_activity_id?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                                        <span class="badge badge-primary mr-1">

                                                            <i class="fas fa-eye"></i>

                                                        </span>

                                                        Detalhes

                                                    </a>

                                                    <?php

                                                    /** Verifico o status do registro */
                                                    if (empty(@(string)$resultCalls->date_close))
                                                    { ?>

                                                        <div class="dropdown-divider"></div>

                                                        <a class="dropdown-item" type="button" onclick="request('FOLDER=VIEW&TABLE=CALLS_ACTIVITIES&ACTION=CALLS_ACTIVITIES_FORM&CALL_ID=<?php echo @(int)$resultCalls->call_id?>&CALL_ACTIVITY_ID=<?php echo @(int)$result->call_activity_id?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                                            <span class="badge badge-primary mr-1">

                                                                <i class="fas fa-pencil-alt"></i>

                                                            </span>

                                                            Alterar

                                                        </a>

                                                    <?php }?>

                                                    <?php

                                                    /** Verifico o status do registro */
                                                    if (empty(@(string)$resultCalls->date_close))
                                                    { ?>

                                                        <div class="dropdown-divider"></div>

                                                        <a class="dropdown-item" type="button" onclick="modalPage(true, 0, 0, 'Atenção', 'Deseja realmente remover o registro?', '', 'question', <?php echo @(string)$function?>)">

                                                            <span class="badge badge-danger mr-1">

                                                                <i class="fas fa-trash"></i>

                                                            </span>

                                                            Excluir

                                                        </a>

                                                    <?php }?>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <script type="text/javascript">

                                    /** Carrega a função de logout */
                                    let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS_ACTIVITIES&ACTION=CALLS_ACTIVITIES_DELETE&CALL_ID=<?php echo @(int)$result->call_id?>&CALL_ACTIVITY_ID=<?php echo @(int)$result->call_activity_id?>', '', true, '', 0, '', 'Removendo produto', 'yellow', 'circle', 'sm', true)";

                                </script>

                            <?php } ?>

                        </div>

                    <?php }else{ ?>

                        <div class="card shadow-sm mt-2 bg-light">

                            <div class="card-body text-center">

                                <img src="img/404.jpg" class="img-fluid mb-3" width="200px" alt="">

                                <div class="row">

                                    <div class="col-md-6 mx-auto">

                                        <h2 class="card-title text-center text-muted">

                                            <strong>

                                                Não foram localizadas atividades para este chamado

                                            </strong>

                                        </h2>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php }?>

                </div>

                <div class="tab-pane fade" id="pills-5" role="tabpanel" aria-labelledby="pills-5-tab">

                    <div class="row">

                        <div class="col-md-6">

                            <h6 class="card-title text-muted">

                                <i class="fas fa-comment-dots mr-1"></i>Feedback's

                            </h6>

                        </div>

                    </div>

                    <section class="chat rounded border">

                        <div class="bg-chat"></div>

                    </section>

                    <?php

                    /** Verifico o status do registro */
                    if (empty(@(string)$resultCalls->date_close))
                    { ?>

                        <form role="form" id="formChat" class="mt-3 text-right">

                            <div id="text_toolbar"></div>

                            <div class="editor border" id="text" style="max-height: 100px"></div>

                            <button class="btn btn-primary mt-3" onclick="sendMessage('#formChat', 'S')" type="button">

                                <i class="far fa-paper-plane mr-1"></i>Enviar

                            </button>

                            <input type="hidden" name="call_id" value="<?php echo @(int)$CallsValidate->getCallId() ?>"/>
                            <input type="hidden" name="user_id" value="<?php echo @(int)$_SESSION['USERSID'] ?>"/>
                            <input type="hidden" name="company_id" value="<?php echo @(int)$_SESSION['USERSCOMPANYID'] ?>"/>
                            <input type="hidden" name="FOLDER" value="ACTION"/>
                            <input type="hidden" name="TABLE" value="CALLS_MESSAGES"/>
                            <input type="hidden" name="ACTION" value="CALLS_MESSAGES_SAVE"/>

                        </form>

                    <?php }?>

                    <form role="form" id="formChatLoadMessage">

                        <input type="hidden" name="call_message_id" id="call_message_id" value=""/>
                        <input type="hidden" name="call_id" value="<?php echo @(int)$CallsValidate->getCallId() ?>"/>
                        <input type="hidden" name="company_id" value="<?php echo @(int)$_SESSION['USERSCOMPANYID'] ?>"/>
                        <input type="hidden" name="FOLDER" value="ACTION"/>
                        <input type="hidden" name="TABLE" value="CALLS_MESSAGES"/>
                        <input type="hidden" name="ACTION" value="CALLS_MESSAGES_LOAD"/>

                    </form>

                </div>

                <div class="tab-pane fade" id="pills-6" role="tabpanel" aria-labelledby="pills-7-tab">

                    <div class="col-md-12">

                        <h6 class="card-title text-muted">

                            <i class="fas fa-film mr-1"></i>Histórico

                        </h6>

                    </div>

                    <?php

                    /** Verifico se existem registros */
                    if (!empty($resultCalls->history))
                    { ?>

                        <div class="main-card card shadow-sm">

                            <div class="card-body">

                                <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">

                                    <?php

                                    /** Pego o histórico existente */
                                    $history = json_decode($resultCalls->history, TRUE);

                                    /** Listo os acessos realizados */
                                    foreach ($history as $keyResultHistory => $resultHistory)
                                    { ?>

                                        <div class="vertical-timeline-item vertical-timeline-element">

                                            <div>

                                                <span class="vertical-timeline-element-icon bounce-in">

                                                    <i class="badge badge-dot badge-dot-xl <?php echo @(string)$resultHistory['class']?>"> </i>

                                                </span>

                                                <div class="vertical-timeline-element-content bounce-in">

                                                    <h4 class="timeline-title">

                                                        <?php echo @(string)$resultHistory['title']?> - <?php echo @(string)$resultHistory['user']?>

                                                    </h4>

                                                    <p>

                                                        <?php echo @(string)$resultHistory['description']?>

                                                        <a href="javascript:void(0);" data-abc="true">

                                                            <?php echo @(string)$resultHistory['date']?>

                                                        </a>

                                                    </p>

                                                    <span class="vertical-timeline-element-date">

                                                        <?php echo @(string)$resultHistory['time']?>

                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                    <?php }?>

                                </div>

                            </div>

                        </div>

                    <?php }else{ ?>

                        <div class="card shadow-sm mt-2 bg-light">

                            <div class="card-body text-center">

                                <img src="img/404.jpg" class="img-fluid mb-3" width="200px" alt="">

                                <div class="row">

                                    <div class="col-md-3 mx-auto">

                                        <h2 class="card-title text-center text-muted">

                                            <strong>

                                                Não foram localizados históricos para este chamado

                                            </strong>

                                        </h2>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php }?>

                </div>

            </div>

        </div>

    </div>

</div>

<script type="text/javascript">

    /** Envio uma requisição para o backend */
    function sendMessage(form, editor) {

        $.ajax({

            url: 'router.php',
            type: 'post',
            dataType: 'json',
            data: editor === 'S' ? $(form).serialize() + '&' + $('.editor').attr('id') + '=' + encodeURIComponent(ckeditor.getData()) : $(form).serialize(),

            /** Caso tenha sucesso */
            success: function (response) {

                /** Verifico o código de retorno */
                switch (response.cod) {

                    /** Verifica se a solicitação foi bem sucedida */
                    case 200:

                        /** Limpo o campo digitado */
                        ckeditor.setData('');
                        break;

                }

            },

            /** Caso tenha falha */
            error: function (xhr, ajaxOptions, thrownError) {

                /** Abro um popup com os dados **/
                modalPage(true, 0, 0, xhr.status + ' - ' + ajaxOptions, thrownError, '', 'alert', '', true);

            },

            /** Ao completar a requisição, cancela o Block Page */
            complete: function () {

                /** Cancela o block page */
                blockPage(false);

            }

        });

    }

    function loadMessage(form) {

        $.ajax({

            url: 'router.php',
            type: 'post',
            dataType: 'json',
            async: 'false',
            data: $(form).serialize(),

            /** Caso tenha sucesso */
            success: function (response) {

                /** Verifico o código de retorno */
                switch (response.cod) {

                    /** Verifica se a solicitação foi bem sucedida */
                    case 200:

                        /** Verifico se tem dados para ser atualizado */
                        if (response.data.length > 0)
                        {

                            /** Monto todos os registros */
                            for (let i = 0; i < response.data.length; i++)
                            {

                                /** Defino a estrutura HTML */
                                let html = null;

                                /** Verifico quem esta mandando a mensagem */
                                if (response.data[i].user_id == <?php echo @(int)$_SESSION['USERSID']?>)
                                {

                                    /** Defino a estrutura HTML */
                                    html  = '<div class="row mb-3 animate slideIn">';
                                    html += '	<div class="offset-1 col-md-11 pr-4">';
                                    html += '		<div class="media text-break">';
                                    html += '			<div class="media-body bg-message-left rounded border p-4">';
                                    html +=                 response.data[i].text;
                                    html += '				<h6 class="mt-0">' + response.data[i].date + '</h6>';
                                    html += '			</div>';
                                    html += '		</div>';
                                    html += '	</div>';
                                    html += '</div>';

                                }
                                else
                                {

                                    let imageAvatar = null;

                                    /** Verifico o tipo de imagem que deve aparecer */
                                    if (response.data[i].genre === 'F')
                                    {

                                        imageAvatar = 'img/female.png'

                                    }
                                    else
                                    {

                                        imageAvatar = 'img/male.png'

                                    }


                                    /** Defino a estrutura HTML */
                                    html  = '<div class="row mb-3 animate slideIn">';
                                    html += '	<div class="col-md-11">';
                                    html += '		<div class="media text-break">';
                                    html += '			<img src="'+ imageAvatar +'" width="50" class="align-self-start mr-3 img-profile rounded">';
                                    html += '			<div class="media-body bg-message-right rounded border p-4">';
                                    html += '				<h6 class="mt-0">'+ response.data[i].name_first +'</h6>';
                                    html +=                 response.data[i].text;
                                    html += '				<h6 class="mt-0">' + response.data[i].date + '</h6>';
                                    html += '			</div>';
                                    html += '		</div>';
                                    html += '	</div>';
                                    html += '</div>';

                                }

                                /** Preencho o HTML dentro da DIV desejad **/
                                $('.bg-chat').append(html);

                                /** Defino o id da ultima mensagem carregada */
                                $('#call_message_id').val(response.data[i].call_mesage_id);

                            }

                        }

                        /** Coloco o SCROLL no final da DIV */
                        $(".chat").animate({ scrollTop: $('.chat').prop("scrollHeight")}, 1000);
                        break;

                }

            },

            /** Caso tenha falha */
            error: function (xhr, ajaxOptions, thrownError) {

                /** Abro um popup com os dados **/
                modalPage(true, 0, 0, xhr.status + ' - ' + ajaxOptions, thrownError, '', 'alert', '', true);

            },

            /** Ao completar a requisição, cancela o Block Page */
            complete: function () {

                /** Verifico se existe o formulário */
                if ($('#formChatLoadMessage').length)
                {

                    /** Defino um delay para a proxima execução */
                    window.setTimeout(() => {

                        /** Carrego as mensagens existentes */
                        loadMessage('#formChatLoadMessage');

                    }, 2000);

                }

            }

        });

    }

    /** Operações ao carregar a página */
    $(document).ready(function(e) {

        /** Carrego todas as mensagens */
        loadMessage('#formChatLoadMessage');

    });

    /** Carrego o editor de texto */
    loadCKEditor();

</script>