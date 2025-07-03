<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\CallsLevels;
use \vendor\model\CallsPriorities;
use \vendor\model\CallsTypes;
use \vendor\model\Calls;
use \vendor\controller\calls\CallsValidate;

/** Instânciamento de classes */
$Main = new Main();
$CallsLevels = new CallsLevels();
$CallsPriorities = new CallsPriorities();
$CallsTypes = new CallsTypes();
$Calls = new Calls();
$CallsValidate = new CallsValidate();

/** Operações */
$Main->SessionStart();

/** ID da Empresa */
$UserCompanyId = @(int)$_SESSION['USERSCOMPANYID'];

/** Tratamento dos dados de entrada */
$CallsValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($CallsValidate->getCallId() > 0) {

    /** Busca de registro */
    $resultCalls = $Calls->get($CallsValidate->getCallId());

    /** Decodifico o texto */
    $resultCalls->description = base64_decode($resultCalls->description);

}

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>Chamados</strong>

        / Formulário /

        <button type="button" class="btn btn-secondary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DATAGRID', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12 animate slideIn">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formCalls">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">

                        <label for="name">

                            Nome:

                        </label>

                        <input type="text" class="form-control" name="name" id="name" value="<?php echo $resultCalls->name ?>">

                    </div>

                </div>               

                <div class="col-md-2">

                    <div class="form-group">

                        <label for="call_type_id">

                            Tipo:

                        </label>

                        <select name="call_type_id" id="call_type_id" class="form-control custom-select">

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($CallsTypes->all($UserCompanyId) as $keyResultCallsTypes => $resultCallsTypes)
                            { ?>

                                <option value="<?php echo $resultCallsTypes->call_type_id; ?>" <?php echo $resultCallsTypes->call_type_id == $resultCalls->call_type_id ? 'selected' : '';?>>

                                    <?php echo $resultCallsTypes->description ?>

                                </option>

                            <?php }?>

                        </select>

                    </div>

                </div>

                <div class="col-md-2">

                    <div class="form-group">

                        <label for="call_level_id">

                            Nível:

                        </label>

                        <select name="call_level_id" id="call_level_id" class="form-control custom-select">

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($CallsLevels->all($UserCompanyId) as $keyResultCallsLevels => $resultCallsLevels)
                            { ?>

                                <option value="<?php echo $resultCallsLevels->call_level_id ?>" <?php echo $resultCallsLevels->call_level_id == $resultCalls->call_level_id ? 'selected' : '';?>>

                                    <?php echo $resultCallsLevels->description; ?>

                                </option>

                            <?php }?>

                        </select>

                    </div>

                </div>

                <div class="col-md-2">

                    <div class="form-group">

                        <label for="call_priority_id">

                            Prioridade:

                        </label>

                        <select name="call_priority_id" id="call_priority_id" class="form-control custom-select">

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($CallsPriorities->all($UserCompanyId) as $keyResultCallsPriorities => $resultCallsPriorities)
                            { ?>

                                <option value="<?php echo $resultCallsPriorities->call_priority_id ?>" <?php echo $resultCallsPriorities->call_priority_id == $resultCalls->call_priority_id ? 'selected' : '';?>>

                                    <?php echo $resultCallsPriorities->description ?>

                                </option>

                            <?php }?>

                        </select>

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

                                        <?php echo $resultCalls->description; ?>

                                    </div>

                                </div>

                            </div>

                        </main>

                    </div>

                </div>

                <div class="col-md-12 text-right">

                    <button type="button" class="btn btn-primary" onclick="sendForm('#formCalls', 'S', true, '', 0, '', '', 'random', 'circle', 'md', true)">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>

                </div>

            </div>

            <input type="hidden" name="call_id" value="<?php echo @(int)$resultCalls->call_id ?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="CALLS"/>
            <input type="hidden" name="ACTION" value="CALLS_SAVE"/>

        </form>

    </div>

</div>

<script type="text/javascript">

    /** Carrego o editor de texto */
    loadCKEditor();

</script>