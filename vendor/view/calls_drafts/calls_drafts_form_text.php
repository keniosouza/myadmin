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

/** Operações */
$Main->SessionStart();

/** Tratamento dos dados de entrada */
$CallsDraftsValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));
$CallsDraftsValidate->setCallDraftId(@(int)filter_input(INPUT_POST, 'CALL_DRAFT_ID', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($CallsDraftsValidate->getCallDraftId() > 0) {

    /** Busca de registro */
    $resultCallDraft = $CallsDrafts->get($CallsDraftsValidate->getCallDraftId());

    /** Decodifico o texto */
    $resultCallDraft->text = utf8_decode(base64_decode($resultCallDraft->text));

}

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>

            <i class="fas fa-file-word mr-1"></i>

            Minutas

        </strong>

        /Formulário/

        <button type="button" class="btn btn-primary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=<?php echo utf8_encode(@(string)$resultCallDraft->call_id) ?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12 animate slideIn">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formDrafts">

            <div class="row">

                <div class="col-md-12">

                    <div class="form-group">

                        <label for="text">

                            Texto:

                        </label>

                        <main>

                            <div id="text_toolbar"></div>

                            <div class="row-editor">

                                <div class="editor-container">

                                    <div class="editor" id="text">

                                        <?php echo utf8_encode(@(string)$resultCallDraft->text) ?>

                                    </div>

                                </div>

                            </div>

                        </main>

                    </div>

                </div>

                <div class="col-md-12 text-right">

                    <button type="button" class="btn btn-primary" onclick="sendForm('#formDrafts', 'S', true, '', 0, '', '', 'random', 'circle', 'md', true)">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>

                </div>

            </div>

            <input type="hidden" name="call_id" value="<?php echo utf8_encode(@(int)$resultCallDraft->call_id) ?>"/>
            <input type="hidden" name="call_draft_id" value="<?php echo utf8_encode(@(int)$resultCallDraft->call_draft_id) ?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="CALLS_DRAFTS"/>
            <input type="hidden" name="ACTION" value="CALLS_DRAFTS_SAVE_TEXT"/>

        </form>

    </div>

</div>

<script type="text/javascript">

    /** Carrego o editor de texto */
    loadCKEditor();

</script>