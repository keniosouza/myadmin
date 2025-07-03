<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\CallsPriorities;
use \vendor\controller\calls_priorities\CallsPrioritiesValidate;

/** Instânciamento de classes */
$Main = new Main();
$CallsPriorities = new CallsPriorities();
$CallsPrioritiesValidate = new CallsPrioritiesValidate();

/** Operações */
$Main->SessionStart();

/** Tratamento dos dados de entrada */
$CallsPrioritiesValidate->setCallPriorityId(@(int)filter_input(INPUT_POST, 'CALL_PRIORITY_ID', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($CallsPrioritiesValidate->getCallPriorityId() > 0) {

    /** Busca de registro */
    $resultCallsPriority = $CallsPriorities->get($CallsPrioritiesValidate->getCallPriorityId());

}

sleep(1);

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>Chamados</strong>

        / Prioridades / Formulário /

        <button type="button" class="btn btn-secondary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS_PRIORITIES&ACTION=CALLS_PRIORITIES_DATAGRID', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12 animate slideIn">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formCallsPriorities">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">

                        <label for="description">

                            Descrição:

                        </label>

                        <input id="description" type="text" class="form-control" name="description" value="<?php echo $resultCallsPriority->description; ?>">

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group">

                        <label for="priority">

                            Prioridade:

                        </label>

                        <input id="priority" type="text" class="form-control number" name="priority" value="<?php echo $resultCallsPriority->priority; ?>">

                    </div>

                </div>                

                <div class="col-md-12 text-right">

                    <button type="button" class="btn btn-primary" onclick="sendForm('#formCallsPriorities', 'N', true, '', 0, '', '', 'random', 'circle', 'md', true)">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>

                </div>

            </div>

            <input type="hidden" name="call_priority_id" value="<?php echo $resultCallsPriority->call_priority_id; ?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="CALLS_PRIORITIES"/>
            <input type="hidden" name="ACTION" value="CALLS_PRIORITIES_SAVE"/>

        </form>

    </div>

</div>