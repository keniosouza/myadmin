<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\CallsLevels;
use \vendor\controller\calls_levels\CallsLevelsValidate;

/** Instânciamento de classes */
$Main = new Main();
$CallsLevels = new CallsLevels();
$CallsLevelsValidate = new CallsLevelsValidate();

/** Tratamento dos dados de entrada */
$CallsLevelsValidate->setCallLevelId(@(int)filter_input(INPUT_POST, 'CALL_LEVEL_ID', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($CallsLevelsValidate->getCallLevelId() > 0) {

    /** Busca de registro */
    $resultCallsLevels = $CallsLevels->get($CallsLevelsValidate->getCallLevelId());

}

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>Chamados</strong>

        / Níveis / Formulário /

        <button type="button" class="btn btn-secondary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS_LEVELS&ACTION=CALLS_LEVELS_DATAGRID', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12 animate slideIn">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formCallsLevels">

            <div class="row">

                <div class="col-md-12">

                    <div class="form-group">

                        <label for="description">

                            Descrição:

                        </label>

                        <input id="description" type="text" class="form-control" name="description" value="<?php echo utf8_encode(@(string)$resultCallsLevels->description) ?>">

                    </div>

                </div>

                <div class="col-md-12 text-right">

                    <button type="button" class="btn btn-primary" onclick="sendForm('#formCallsLevels', 'N', true, '', 0, '', '', 'random', 'circle', 'md', true)">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>

                </div>

            </div>

            <input type="hidden" name="call_level_id" value="<?php echo utf8_encode(@(int)$resultCallsLevels->call_level_id) ?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="CALLS_LEVELS"/>
            <input type="hidden" name="ACTION" value="CALLS_LEVELS_SAVE"/>

        </form>

    </div>

</div>