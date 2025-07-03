<?php

/** Importação de classes */
use vendor\model\FinancialReadjustments;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes */
    $FinancialReadjustments = new FinancialReadjustments();

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parâmetros de paginação **/
    $start = isset($_POST['start'])  ? (int)filter_input(INPUT_POST,'start',  FILTER_SANITIZE_NUMBER_INT)  : 0;
    $page  = isset($_POST['page'])   ? (int)filter_input(INPUT_POST,'page',  FILTER_SANITIZE_NUMBER_INT)   : 0;
    $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20; 

    /** Consulta a quantidade de registros */
    $NumberRecords = $FinancialReadjustments->Count();

    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                
                <div class="card-header">

                    <div class="row">

                        <div class="col-md-8">

                            <h5>

                                Reajustes

                            </h5>

                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_readjustments&ACTION=financial_readjustments_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo reajuste">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>
                       
                        </div>

                    </div>

                </div>                    

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white shadow-sm table-sm">

                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Descrição</th>
                                    <th class="text-center">Ano</th>
                                    <th class="text-center">Mês</th>
                                    <th class="text-center">Valor %</th>
                                    <th class="text-center">Situação</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php

                            /** Consulta a quantidade de registros */
                            $financialReadjustmentsResult = $FinancialReadjustments->All($start, $max);

                            /** Consulta os usuário cadastrados*/
                            foreach ($financialReadjustmentsResult as $resultKey => $result)
                            {?>

                                <tr class="<?php echo $result->status == 3 ? 'text-danger' : ''; ?>">

                                    <td class="text-center" width="60"><?php echo $Main->setZeros($result->financial_readjustment_id, 3); ?></td>
                                    <td><?php echo $result->description; ?></td>
                                    <td class="text-center"><?php echo $result->year; ?></td>
                                    <td class="text-center"><?php echo $Main->returnMonth($result->month); ?></td>
                                    <td class="text-right"><?php echo number_format($result->readjustment, 4, ',', '.'); ?></td>
                                    <td class="text-center" width="60"><?php echo $result->status == 1 ? "Ativo" : "Inativo"; ?></td>
                                    <td class="text-center" width="20"><a type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=financial_readjustments&ACTION=financial_readjustments_form&financial_readjustment_id=<?php echo $result->financial_readjustment_id; ?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="far fa-edit mr-1"></i></a></td>

                                </tr>

                            <?php } ?>

                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="7">

                                        <?php echo $NumberRecords > $max ? $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=financial_readjustments_datagrid&TABLE=financial_readjustments', 'Aguarde', '') : ''; ?>                                    

                                    </td>
                                </tr>
                            </tfoot>                        

                        </table>

                    </div>

                </div>

            </div>

        </div>                    

    <?php

    }else{//Caso não tenha registros cadastrados, informo ?>

        <div class="col-lg-12">
        
            <!-- Informo -->
            <div class="card shadow mb-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Atenção</h6>
                </div>
                <div class="card-body">
        
                    <div class="row">
        
                        <div class="col-md-8 text-right">
                            <h4>Não foram cadastrados reajustes.</h4>
                        </div>
        
                        <div class="col-md-4 text-right">
        
                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_readjustments&ACTION=financial_readjustments_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">
        
                                <i class="fas fa-plus-circle mr-1"></i>Cadastrar novo reajuste
        
                            </button>
        
                        </div>
        
                    </div>
        
                </div>
            </div>
        
        </div>
        
    <?php } 

/** Caso o token de acesso seja inválido, informo */
}else{

    /** Informa que o usuário precisa efetuar autenticação junto ao sistema */
    $authenticate = true;    

    /** Informo */
    throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
}