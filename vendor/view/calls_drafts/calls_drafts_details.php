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

    <div class="card shadow-sm">

        <div class="card-body">

            <ul class="nav nav-pills nav-fill mb-2" id="pills-tab" role="tablist">

                <li class="nav-item nav-link-pill" role="presentation">

                    <a class="nav-link active" id="pills-texto-tab" data-toggle="pill" href="#pills-texto" role="tab" aria-controls="pills-texto" aria-selected="true">

                        <i class="far fa-file-alt mr-1"></i>Texto

                    </a>

                </li>

                <li class="nav-item nav-link-pill mx-1" role="presentation">

                    <a class="nav-link" id="pills-historico-tab" data-toggle="pill" href="#pills-historico" role="tab" aria-controls="pills-historico" aria-selected="false">

                        <i class="fas fa-history mr-1"></i>Histórico

                    </a>

                </li>

            </ul>

            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade active show" id="pills-texto" role="tabpanel" aria-labelledby="pills-texto-tab">

                    <div class="card-text border p-2 shadow-sm rounded text-break">

                        <?php echo utf8_encode(@(string)$resultCallDraft->text)?>

                    </div>

                </div>

                <div class="tab-pane fade show" id="pills-historico" role="tabpanel" aria-labelledby="pills-historico-tab">

                    <div class="main-card card shadow-sm">

                        <div class="card-body">

                            <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">

                                <?php

                                /** Pego o histórico existente */
                                $history = json_decode($resultCallDraft->history, TRUE);

                                /** Listo os acessos realizados */
                                foreach ($history as $keyResultHistory => $resultHistory)
                                { ?>

                                    <div class="vertical-timeline-item vertical-timeline-element">

                                        <div>

                                                <span class="vertical-timeline-element-icon bounce-in">

                                                    <i class="badge badge-dot badge-dot-xl <?php echo @(string)$resultHistory['class']?>"> </i>

                                                </span>

                                            <div class="vertical-timeline-element-content bounce-in">

                                                <h4 class="timeline-title">

                                                    <?php echo @(string)$resultHistory['title']?> - <?php echo @(string)$resultHistory['user']?>

                                                </h4>

                                                <p>

                                                    <?php echo @(string)$resultHistory['description']?>

                                                    <a href="javascript:void(0);" data-abc="true">

                                                        <?php echo @(string)$resultHistory['date']?>

                                                    </a>

                                                </p>

                                                <span class="vertical-timeline-element-date">

                                                    <?php echo @(string)$resultHistory['time']?>

                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                <?php }?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>