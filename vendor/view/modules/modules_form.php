<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\Modules;
use \vendor\controller\modules\ModulesValidate;

/** Instânciamento de classes */
$Main = new Main();
$Modules = new Modules();
$ModulesValidate = new ModulesValidate();

/** Operações */
$Main->SessionStart();

/** Tratamento dos dados de entrada */
$ModulesValidate->setModulesId(@(int)filter_input(INPUT_POST, 'modules_id', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($ModulesValidate->getModulesId() > 0) {

    /** Busca de registro */
    $resultModules = $Modules->get($ModulesValidate->getModulesId());

}

?>

<div class="col-md-6">

    <h5 class="card-title">

        <strong>

            <i class="fas fa-file-word mr-1"></i>

            Módulos

        </strong>

        /Formulário/

        <button type="button" class="btn btn-primary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=modules&ACTION=modules_datagrid', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

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

                        <label for="name">

                            Nome

                        </label>

                        <input id="name" type="text" class="form-control" name="name" value="<?php echo @(string)$resultModules->name ?>">

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group">

                        <label for="description">

                            Descrição

                        </label>

                        <input id="description" type="text" class="form-control" name="description" value="<?php echo @(string)$resultModules->description ?>">

                    </div>

                </div>

                <div class="col-md-12 text-right">

                    <button type="button" class="btn btn-primary" onclick="sendForm('#formDrafts', 'N')">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>

                </div>

            </div>

            <input type="hidden" name="modules_id" value="<?php echo utf8_encode(@(int)$resultModules->modules_id) ?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="MODULES"/>
            <input type="hidden" name="ACTION" value="MODULES_SAVE"/>

        </form>

    </div>

</div>