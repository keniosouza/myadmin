<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\Drafts;
use \vendor\model\CallsDrafts;
use \vendor\controller\calls_drafts\CallsDraftsValidate;

/** Instânciamento de classes */
$Main = new Main();
$Drafts = new Drafts();
$CallsDrafts = new CallsDrafts();
$CallsDraftsValidate = new CallsDraftsValidate();

/** Tratamento dos dados de entrada */
$CallsDraftsValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));
$CallsDraftsValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);

/** Verifico se existe registro */
if ($CallsDraftsValidate->getCompanyId() > 0) {

    /** Busca de registro */
    $resultDrafts = $Drafts->AllNoLimit(@(int)$_SESSION['USERSCOMPANYID']);

}

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>

            <i class="fas fa-file-word mr-1"></i>

            Chamados

        </strong>

        /Detalhes/Documentos/Formulário/

        <button type="button" class="btn btn-primary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=<?php echo @(string)$CallsDraftsValidate->getCallId()?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12 animate slideIn">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formDrafts">

            <div class="form-group mb-2">

                <input type="text" class="form-control" placeholder="Pesquise por: Nome" id="search" name="search">

            </div>

            <table class="table table-bordered table-borderless table-hover bg-white shadow-sm border" id="search_table">

                <thead id="search_table_head">
                <tr>

                    <th>

                        Nome

                    </th>

                </tr>

                </thead>

                <tbody>

                <?php

                /** Consulta os usuário cadastrados*/
                foreach ($resultDrafts as $keyResultDrafts => $result)
                {?>

                    <tr class="border-top">

                        <td>

                            <div class="form-group">

                                <div class="custom-control custom-switch">

                                    <input type="checkbox" class="custom-control-input" id="customSwitch<?php echo @(int)$keyResultDrafts?>" value="<?php echo @(int)$result->draft_id?>" name="call_draft_id[]">

                                    <label class="custom-control-label" for="customSwitch<?php echo @(int)$keyResultDrafts?>">

                                        <?php echo @(string)$result->name?>

                                    </label>

                                </div>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

            <div class="col-md-12 text-right">

                <button type="button" class="btn btn-primary" onclick="sendForm('#formDrafts', 'S', true, '', 0, '', '', 'random', 'circle', 'md', true)">

                    <i class="far fa-paper-plane mr-1"></i>Salvar

                </button>

            </div>

            <input type="hidden" name="call_id" value="<?php echo @(string)$CallsDraftsValidate->getCallId()?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="CALLS_DRAFTS"/>
            <input type="hidden" name="ACTION" value="CALLS_DRAFTS_SAVE"/>

        </form>

    </div>

</div>

<script type="text/javascript">

    /** Carrego o LiveSearch */
    loadLiveSearch();

</script>