<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\CallsTypes;
use \vendor\controller\calls_types\CallsTypesValidate;

/** Instânciamento de classes */
$Main = new Main();
$CallsTypes = new CallsTypes();
$CallsTypesValidate = new CallsTypesValidate();

/** Tratamento dos dados de entrada */
$CallsTypesValidate->setCallTypeId(@(int)filter_input(INPUT_POST, 'CALL_TYPE_ID', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($CallsTypesValidate->getCallTypeId() > 0) {

    /** Busca de registro */
    $resultCallsTypes = $CallsTypes->get($CallsTypesValidate->getCallTypeId());

}

sleep(1);

?>

<div class="col-md-6">

    <h5 class="card-title">

        <strong>

            Chamados

        </strong>

        / Tipos / Formulário /

        <button type="button" class="btn btn-secondary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS_TYPES&ACTION=CALLS_TYPES_DATAGRID', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formDrafts">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">

                        <label for="description">

                            Descrição:

                        </label>

                        <input id="description" type="text" class="form-control" name="description" value="<?php echo $resultCallsTypes->description; ?>">  
                                                
                    </div>

                    <button type="button" class="btn btn-primary float-right" id="btn-send" onclick="sendForm('#formDrafts', 'N', true, '', 0, '', '', 'random', 'circle', 'md', true)">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>                    

                </div>

            </div>

            <input type="hidden" name="call_type_id" value="<?php echo $resultCallsTypes->call_type_id; ?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="CALLS_TYPES"/>
            <input type="hidden" name="ACTION" value="CALLS_TYPES_SAVE"/>

        </form>

    </div>

</div>