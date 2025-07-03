<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\CallsActivities;
use \vendor\model\CallsActivitiesUsers;
use \vendor\controller\calls_activities_users\CallsActivitiesUsersValidate;

/** Instânciamento de classes */
$Main = new Main();
$CallsActivities = new CallsActivities();
$CallsActivitiesUsers = new CallsActivitiesUsers();
$CallsActivitiesUsersValidate = new CallsActivitiesUsersValidate();

/** Tratamento dos dados de entrada */
$CallsActivitiesUsersValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));
$CallsActivitiesUsersValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);
$CallsActivitiesUsersValidate->setCallActivityId(@(int)filter_input(INPUT_POST, 'CALL_ACTIVITY_ID', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($CallsActivitiesUsersValidate->getCallActivityId() > 0) {

    /** Busca de registro */
    $resultCallsActivities = $CallsActivities->Get($CallsActivitiesUsersValidate->getCallActivityId());

    /** Decodifico o texto */
    $resultCallsActivities->description = base64_decode($resultCallsActivities->description);
    $resultCallsActivitiesUsers = $CallsActivitiesUsers->All($CallsActivitiesUsersValidate->getCallId(), $CallsActivitiesUsersValidate->getCallActivityId(), $CallsActivitiesUsersValidate->getCompanyId());

}

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>

            <i class="fas fa-file-word mr-1"></i>

            Chamados

        </strong>

        /Detalhes/Atividade/Detalhes/

        <button type="button" class="btn btn-primary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=<?php echo utf8_decode(@(string)$CallsActivitiesUsersValidate->getCallId())?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12 animate slideIn">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formDrafts">

            <div class="card shadow-sm bg-gray mb-3">

                <div class="card-body">

                    <div class="row grid-divider">

                        <div class="col-md">

                            <h6 class="mt-0 mb-0">

                                Nome:

                            </h6>

                            <h6>

                                <strong>

                                    <?php echo @(string)$resultCallsActivities->name?>

                                </strong>

                            </h6>

                        </div>

                        <div class="col-md">

                            <h6 class="mt-0 mb-0">

                                Previsão de Conclusão:

                            </h6>

                            <h6>

                                <strong>

                                    <?php

                                    /** Verifico o status do registro */
                                    if (empty(@(string)$resultCallsActivities->date_expected))
                                    {?>

                                        Não possui

                                    <?php }else{?>

                                        <?php echo date('d/m/Y', strtotime(@(string)$resultCallsActivities->date_expected))?>

                                    <?php }?>

                                </strong>

                            </h6>

                        </div>

                        <div class="col-md">

                            <h6 class="mt-0 mb-0">

                                Início:

                            </h6>

                            <h6>

                                <strong>

                                    <?php

                                    /** Verifico o status do registro */
                                    if (empty(@(string)$resultCallsActivities->date_start))
                                    {?>

                                        Não possui

                                    <?php }else{?>

                                        <?php echo date('d/m/Y', strtotime(@(string)$resultCallsActivities->date_start))?>

                                    <?php }?>

                                </strong>

                            </h6>

                        </div>

                        <div class="col-md">

                            <h6 class="mt-0 mb-0">

                                Encerramento:

                            </h6>

                            <h6>

                                <strong>

                                    <?php

                                    /** Verifico o status do registro */
                                    if (empty(@(string)$resultCallsActivities->date_close))
                                    {?>

                                        Não possui

                                    <?php }else{?>

                                        <?php echo date('d/m/Y', strtotime(@(string)$resultCallsActivities->date_close))?>

                                    <?php }?>

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

                        <?php echo @(string)$resultCallsActivities->description?>

                    </div>

                </div>

            </div>

            <?php

            /** Verifico se existem registros */
            if (@(int)count($resultCallsActivitiesUsers) > 0)
            { ?>

                <div class="mt-3">

                    <span class="badge badge-primary">Dentro do Prazo</span> - <span class="badge badge-warning">Dia de Conclusão</span> - <span class="badge badge-danger">Entrega Atrasada</span> - <span class="badge badge-success">Entrega Realizada</span>

                </div>

                <div class="row mt-3">

                    <?php

                    /** Consulta os usuário cadastrados*/
                    foreach ($resultCallsActivitiesUsers as $keyResultCallsActivitiesUsers => $result)
                    {

                        /** Crio o nome da função */
                        $function = 'function_delete_calls_activities_users_' . @(int)$keyResultCallsActivitiesUsers . '_' . rand(1, 1000);
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

                            <div class="card bg-light text-black shadow-sm w-100 border-<?php echo utf8_encode($classCss)?>">

                                <div class="card-body">

                                    <strong><?php echo $Main->decryptData(@(string)$result->name_first)?></strong>

                                    <div class="text-black-50 small">

                                        Previsto: <strong><?php echo !empty(@(string)$result->date_expected) ? date('d/m/Y', strtotime(@(string)$result->date_expected)) : 'Não possui'?></strong>
                                        <br>
                                        Início: <strong><?php echo !empty(@(string)$result->date_start) ? date('d/m/Y', strtotime(@(string)$result->date_start)) : 'Não possui'?></strong>
                                        <br>
                                        Encerramento: <strong><?php echo !empty(@(string)$result->date_close) ? date('d/m/Y', strtotime(@(string)$result->date_close)) : 'Não possui'?></strong>

                                    </div>

                                </div>

                                <?php

                                /** Verifico se devo habilitar o botão para o usuário */
                                if (@(int)$result->user_id === @(int)$_SESSION['USERSID']){?>

                                    <div class="card-footer border-0">

                                        <div class="row">

                                            <?php

                                            /** Busco atividade em aberto para o usuário */
                                            $resultCallsActivitiesUsersSteps = $CallsActivitiesUsers->Get($resultCallsActivities->call_activity_id, $_SESSION['USERSID']);

                                            /** Verifico o status do registro */
                                            if (empty(@(string)$resultCallsActivitiesUsersSteps->date_start))
                                            {

                                                /** Crio o nome da função */
                                                $function = 'function_calls_start_' . @(int)$CallsActivitiesUsersValidate->getCallActivityId() . '_' . rand(1, 1000);

                                                ?>

                                                <div class="col-md-12">

                                                    <button class="btn btn-<?php echo utf8_encode($classCss)?> btn-block" type="button" onclick="modalPage(true, 0, 0,   'Atenção', 'Deseja iniciar a sua participação?', '', 'question', <?php echo @(string)$function?>)">

                                                        <i class="fas fa-play mr-1"></i>Iniciar Participação

                                                    </button>

                                                    <script type="text/javascript">

                                                        /** Carrega a função de logout */
                                                        let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS_ACTIVITIES_USERS&ACTION=CALLS_ACTIVITIES_USERS_START&CALL_ID=<?php echo @(int)$resultCallsActivities->call_id?>&CALL_ACTIVITY_ID=<?php echo @(int)$resultCallsActivities->call_activity_id?>', '', true, '', 0, '', 'Iniciando participação', 'random', 'circle', 'sm', true)";

                                                    </script>

                                                </div>

                                            <?php }elseif(empty(@(string)$resultCallsActivitiesUsersSteps->date_close)){

                                                /** Crio o nome da função */
                                                $function = 'function_calls_close_' . @(int)$CallsActivitiesUsersValidate->getCallActivityId() . '_' . rand(1, 1000);

                                                ?>

                                                <div class="col-md-12">

                                                    <button class="btn btn-<?php echo utf8_encode($classCss)?> btn-block" type="button" onclick="modalPage(true, 0, 0,   'Atenção', 'Deseja encerrar a sua participação?', '', 'question', <?php echo @(string)$function?>)">

                                                        <i class="fas fa-stop mr-1"></i>Encerrar Participação

                                                    </button>

                                                    <script type="text/javascript">

                                                        /** Carrega a função de logout */
                                                        let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS_ACTIVITIES_USERS&ACTION=CALLS_ACTIVITIES_USERS_CLOSE&CALL_ID=<?php echo @(int)$resultCallsActivities->call_id?>&CALL_ACTIVITY_ID=<?php echo @(int)$resultCallsActivities->call_activity_id?>', '', true, '', 0, '', 'Encerrando participação', 'random', 'circle', 'sm', true)";

                                                    </script>

                                                </div>

                                            <?php }?>

                                        </div>

                                    </div>

                                <?php }?>

                            </div>

                        </div>

                    <?php } ?>

                </div>

            <?php }else{ ?>

                <div class="card shadow-sm mt-3 bg-light">

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

            <?php

            /** Contagem de atividades em aberto */
            $activitiesOpen = 0;

            /** Consulta os usuário cadastrados*/
            foreach ($resultCallsActivitiesUsers as $keyResultCallsActivitiesUsers => $result) {

                /** Verifico se devo habilitar o botão para o usuário */
                if (!empty($result->date_close)) {

                    /** Aumento o contador */
                    $activitiesOpen++;

                }

            }?>

            <?php

            /** Verifico se devo encerrar a atividade */
            if (count($resultCallsActivitiesUsers) > 0)
            {?>

                <?php

                /** Verifico o status do registro */
                if ((@(int)$activitiesOpen === @(int)count($resultCallsActivitiesUsers)) && (empty($resultCallsActivities->date_close)))
                {?>

                    <div class="row  mt-3">

                        <div class="col-md-12">

                            <button class="btn btn-primary btn-block" type="button" onclick="modalPage(true, 0, 0,   'Atenção', 'Deseja realmente remover o registro?', '', 'question', <?php echo utf8_encode(@(string)$function)?>)">

                                <i class="fas fa-stop mr-1"></i>Encerrar Atividade

                            </button>

                            <script type="text/javascript">

                                /** Carrega a função de logout */
                                let <?php echo @(string)$function?> = "request('FOLDER=ACTION&TABLE=CALLS_ACTIVITIES&ACTION=CALLS_ACTIVITIES_CLOSE&CALL_ID=<?php echo @(int)$resultCallsActivities->call_id?>&CALL_ACTIVITY_ID=<?php echo @(int)$resultCallsActivities->call_activity_id?>', '', true, '', 0, '', 'Encerrando chamado', 'random', 'circle', 'sm', true)";

                            </script>

                        </div>

                    </div>

                <?php }?>

            <?php }?>

        </form>

    </div>

</div>