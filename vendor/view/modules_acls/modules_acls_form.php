<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\Modules;
use \vendor\model\ModulesAcls;
use \vendor\controller\modules_acls\ModulesAclsValidate;

/** Instânciamento de classes */
$Main = new Main();
$Modules = new Modules();
$ModulesAcls = new ModulesAcls();
$ModulesAclsValidate = new ModulesAclsValidate();

/** Operações */
$Main->SessionStart();

/** Busco todos os registros */
$resultModules = $Modules->All(@(int)$_SESSION['USERSCOMPANYID']);

/** Tratamento dos dados de entrada */
$ModulesAclsValidate->setModulesAclsId(@(int)filter_input(INPUT_POST, 'modules_acls_id', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($ModulesAclsValidate->getModulesAclsId() > 0) {

    /** Busca de registro */
    $resultModulesAclsValidate = $ModulesAcls->get($ModulesAclsValidate->getModulesAclsId());

}

?>

<div class="col-md-6">

    <h5 class="card-title">

        <strong>

            <i class="fas fa-file-word mr-1"></i>

            Controles de Acesso

        </strong>

        /Formulário/

        <button type="button" class="btn btn-primary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=modules_acls&ACTION=modules_acls_datagrid', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formDrafts">

            <div class="row row-dynamic-input">

                <div class="col-md-12">

                    <div class="form-group">

                        <label for="modules_id">

                            Módulo

                        </label>

                        <select name="modules_id" id="modules_id" class="custom-select">

                            <?php

                            /** Consulta os usuário cadastrados*/
                            foreach ($resultModules as $keyResultModules => $result) { ?>

                                <option value="<?php echo @(int)$result->modules_id ?>" <?php echo @(int)$result->modules_id === @(int)$resultModulesAclsValidate->modules_id ? 'selected' : null ?>>

                                    <?php echo @(string)$result->name ?>

                                </option>

                            <?php } ?>

                        </select>

                    </div>

                </div>

                <div class="col-md-12">

                    <div class="form-group">

                        <label for="Descrição">

                            Nome

                        </label>

                        <input type="text" class="form-control" id="description" name="description" value="<?php echo utf8_encode(@(string)$resultModulesAclsValidate->description) ?>">

                    </div>

                </div>

                <div class="col-md-12">

                    <div class="alert alert-warning" role="alert">

                        <h4 class="alert-heading">

                            Anteção

                        </h4>

                        <p>

                            É necessário cadastrar as seguintes opções padrões

                        </p>

                        <ul>

                            <li>

                                create

                            </li>

                            <li>

                                read

                            </li>

                            <li>

                                update

                            </li>

                            <li>

                                delete

                            </li>

                        </ul>

                    </div>

                </div>

                <?php

                /** Pego o histórico existente */
                $preferences = json_decode($resultModulesAclsValidate->preferences, TRUE);

                /** Consulta os usuário cadastrados*/
                foreach ($preferences as $key => $result) {?>

                    <div class="col-md-3">

                        <div class="form-group">

                            <label for="permission<?php echo $key?>">

                                Nome permissão

                            </label>

                            <input id="permission<?php echo $key?>" type="text" class="form-control" name="permission[]" value="<?php echo $result?>">

                        </div>

                    </div>

                <?php } ?>

            </div>

            <div class="row">

                <div class="col-md-6 text-left">

                    <button type="button" class="btn btn-primary" onclick="addInputModulesAclsForm()">

                        <i class="fas fa-plus-circle mr-1"></i>Adicionar Campo

                    </button>

                </div>

                <div class="col-md-6 text-right">

                    <button type="button" class="btn btn-primary" onclick="sendForm('#formDrafts', 'N')">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>

                </div>

            </div>

            <input type="hidden" name="modules_acls_id" value="<?php echo utf8_encode(@(int)$resultModulesAclsValidate->modules_acls_id) ?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="modules_acls"/>
            <input type="hidden" name="ACTION" value="modules_acls_save"/>

        </form>

    </div>

</div>

<script type="text/javascript">
    
    function addInputModulesAclsForm() {

        /** Id aleatorio */
        let key = Math.random();

        /** Defino a estrutura HTML */
        let html = '<div class="col-md-3">';
            html += '	<div class="form-group">';
            html += '		<label for="permission'+key+'">';
            html += '			Nome permissão';
            html += '		</label>';
            html += '		<input id="permission'+key+'" type="text" class="form-control" name="permission[]">';
            html += '	</div>';
            html += '</div>';

        /** Preencho o HTML dentro da DIV desejad **/
        $('.row-dynamic-input').append(html);
        
    }
    
</script>