<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\CallsActivities;
use \vendor\controller\calls_activities\CallsActivitiesValidate;

/** Instânciamento de classes */
$Main = new Main();
$CallsActivities = new CallsActivities();
$CallsActivitiesValidate = new CallsActivitiesValidate();

/** Operações */
$Main->SessionStart();

/** ID da Empresa */
$UserCompanyId = @(int)$_SESSION['USERSCOMPANYID'];

/** Tratamento dos dados de entrada */
$CallsActivitiesValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));
$CallsActivitiesValidate->setCallActivityId(@(int)filter_input(INPUT_POST, 'CALL_ACTIVITY_ID', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($CallsActivitiesValidate->getCallActivityId() > 0) {

    /** Busca de registro */
    $resultCallsActivities = $CallsActivities->get($CallsActivitiesValidate->getCallActivityId());

    /** Decodifico o texto */
    $resultCallsActivities->description = utf8_decode(base64_decode($resultCallsActivities->description));

}

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>

            <i class="fas fa-file-word mr-1"></i>

            Chamados

        </strong>

        /Atividades/Formulário/

        <button type="button" class="btn btn-primary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=<?php echo utf8_decode($CallsActivitiesValidate->getCallId())?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12 animate slideIn">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formCalls">

            <div class="row">

                <div class="col-md-12">

                    <div class="form-group">

                        <label for="name">

                            Nome:

                        </label>

                        <input type="text" class="form-control" name="name" id="name" value="<?php echo @(string)$resultCallsActivities->name ?>">

                    </div>

                </div>

                <div class="col-md-12">

                    <div class="form-group">

                        <label for="description">

                            Descrição:

                        </label>

                        <main>

                            <div id="description_toolbar"></div>

                            <div class="row-editor">

                                <div class="editor-container">

                                    <div class="editor" id="description">

                                        <?php echo utf8_encode(@(string)$resultCallsActivities->description) ?>

                                    </div>

                                </div>

                            </div>

                        </main>

                    </div>

                </div>

                <div class="col-md-12">

                    <div class="form-group">

                        <label for="date_expected">

                            Data prevista:

                        </label>

                        <input type="date" class="form-control" name="date_expected" id="date_expected" value="<?php echo date('d/m/Y', strtotime(@(string)$resultCallsActivities->date_expected)) ?>">

                    </div>

                </div>

                <div class="col-md-12 text-right">

                    <button type="button" class="btn btn-primary" onclick="sendForm('#formCalls', 'S', true, '', 0, '', '', 'random', 'circle', 'md', true)">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>

                </div>

            </div>

            <input type="hidden" name="call_activity_id" value="<?php echo @(int)$resultCallsActivities->call_activity_id ?>"/>
            <input type="hidden" name="call_id" value="<?php echo @(int)$CallsActivitiesValidate->getCallId() ?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="CALLS_ACTIVITIES"/>
            <input type="hidden" name="ACTION" value="CALLS_ACTIVITIES_SAVE"/>

        </form>

    </div>

</div>

<script type="text/javascript">

    /** Carrego o editor de texto */
    loadCKEditor();

</script>