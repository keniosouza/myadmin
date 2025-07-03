<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\Highlighters;
use \vendor\model\Drafts;
use \vendor\controller\drafts\DraftsValidate;

/** Instânciamento de classes */
$Main = new Main();
$Highlighters = new Highlighters();
$Drafts = new Drafts();
$DraftsValidate = new DraftsValidate();

/** Operações */
$Main->SessionStart();

/** Tratamento dos dados de entrada */
$DraftsValidate->setDraftId(@(int)filter_input(INPUT_POST, 'DRAFT_ID', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico se existe registro */
if ($DraftsValidate->getDraftId() > 0) {

    /** Busca de registro */
    $resultDraft = $Drafts->get($DraftsValidate->getDraftId());

    /** Decodifico o texto */
    $resultDraft->text = utf8_decode(base64_decode($resultDraft->text));

}

?>

<div class="col-md-6">

    <h5 class="card-title">

        <strong>

            <i class="fas fa-file-word mr-1"></i>

            Minutas

        </strong>

        /Formulário/

        <button type="button" class="btn btn-primary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=DRAFTS&ACTION=DRAFTS_DATAGRID', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formDrafts">

            <div class="row">

                <div class="col-md-12">

                    <div class="form-group">

                        <label for="name">

                            Nome

                        </label>

                        <input id="name" type="text" class="form-control" name="name" value="<?php echo @(string)$resultDraft->name ?>">

                    </div>

                </div>

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

                                        <?php echo utf8_encode(@(string)$resultDraft->text) ?>

                                    </div>

                                </div>

                            </div>

                        </main>

                    </div>

                </div>

                <div class="col-md-12">

                    <div class="form-group">

                        <a class="btn btn-primary btn-block" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">

                            <i class="fas fa-highlighter mr-1"></i>Marcações

                        </a>

                        <div class="collapse" id="collapseExample">

                            <div class="form-group my-2">

                                <input type="text" class="form-control shadow-sm" placeholder="Pesquise por: Nome" id="search" name="search">

                            </div>

                            <table class="table table-bordered table-borderless table-hover bg-white shadow-sm border mt-3" id="search_table">

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
                                foreach ($Highlighters->All(@(int)$_SESSION['USERSCOMPANYID']) as $keyResultHighlighter => $resultHighlighter) {
                                    ?>

                                    <tr class="border-top">

                                        <td id="text_<?php echo utf8_encode($keyResultHighlighter) ?>">

                                            <?php echo utf8_encode($resultHighlighter->name); ?>

                                        </td>

                                    </tr>

                                <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <div class="col-md-12 text-right">

                    <button type="button" class="btn btn-primary" onclick="sendForm('#formDrafts', 'S')">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>

                </div>

            </div>

            <input type="hidden" name="draft_id" value="<?php echo utf8_encode(@(int)$resultDraft->draft_id) ?>"/>
            <input type="hidden" name="FOLDER" value="ACTION"/>
            <input type="hidden" name="TABLE" value="DRAFTS"/>
            <input type="hidden" name="ACTION" value="DRAFTS_SAVE"/>

        </form>

    </div>

</div>

<script type="text/javascript">

    /** Carrego o editor de texto */
    loadCKEditor();

</script>