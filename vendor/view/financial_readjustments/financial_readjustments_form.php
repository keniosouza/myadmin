<?php
/** Importação de classes  */
use vendor\model\FinancialReadjustments;
use vendor\controller\financial_readjustments\FinancialReadjustmentsValidate;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes  */
    $FinancialReadjustments = new FinancialReadjustments();
    $FinancialReadjustmentsValidate = new FinancialReadjustmentsValidate();

    /** Parametros de entrada */
    $financialReadjustmentId = isset($_POST['financial_readjustment_id'])   ? (int)filter_input(INPUT_POST, 'financial_readjustment_id', FILTER_SANITIZE_NUMBER_INT)   : 0;

    /** Validando os campos de entrada */
    $FinancialReadjustmentsValidate->setFinancialReadjustmentId($financialReadjustmentId);

    /** Verifica se o ID do usuário foi informado */
    if ($FinancialReadjustmentsValidate->getFinancialReadjustmentId() > 0) {

        /** Consulta os dados do usuário */
        $FinancialReadjustmentsResult = $FinancialReadjustments->Get($FinancialReadjustmentsValidate->getFinancialReadjustmentId());

    } else {
        /** Caso o ID do usuário não tenha sido informado, carrego os campos como null */

        /** Carrega os campos da tabela */
        $FinancialReadjustmentsResult = $FinancialReadjustments->Describe();

    } ?>

    <div class="col-md-12">

        <div class="card shadow mb-12">
                    
            <div class="card-header">

                <div class="row">
                        
                    <div class="col-md-8">        

                        <h5>

                        <?php echo $FinancialReadjustmentsResult->financial_readjustment_id > 0 ? 'Editar reajuste' : 'Cadastrar novo reajuste';?>
                    
                        </h5>

                    </div>
                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=financial_readjustments&ACTION=financial_readjustments_datagrid', '#loadContent', true, '', '', '', 'Carregando reajustes cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar reajustes cadastrados">

                            <i class="fas fa-plus-circle mr-1"></i>Reajustes Cadastrados

                        </button>                

                    </div>

                </div>

            </div>

            <div class="card-body">

                <form class="user" id="frmReadjustments">

                    <div class="form-group row">

                        <div class="col-sm-4 mb-2">

                            <label for="description">

                                Descrição: <span class="text-danger">* Obrigatório</span>

                            </label>

                            <input type="text" class="form-control form-control" maxlength="60" id="description" name="description" value="<?php echo !empty($FinancialReadjustmentsResult->description) ? $FinancialReadjustmentsResult->description : '';?>">

                        </div>

                        <div class="col-sm-4">

                            <label for="year">

                                Ano: <span class="text-danger">* Obrigatório</span>


                            </label>

                            <input type="number" min="2023" max="2099" step="1" value="2024" class="form-control form-control number" maxlength="4" id="year" name="year" value="<?php echo !empty($FinancialReadjustmentsResult->year) ? $FinancialReadjustmentsResult->year : '';?>">

                        </div>

                        <div class="col-sm-4">

                            <label for="month">

                                Mês: <span class="text-danger">* Obrigatório</span>


                            </label>

                            <select class="form-control form-control" id="month" name="month">
                                <option value="" selected>Selecione</option>
                                <option value="1" <?php echo (int)$FinancialReadjustmentsResult->month == 1 || (int)date('m') == 1 ? 'selected' : '';?>>Janeiro</option>
                                <option value="2" <?php echo (int)$FinancialReadjustmentsResult->month == 2 || (int)date('m') == 2 ? 'selected' : '';?>>Fevereiro</option>
                                <option value="3" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 3 ? 'selected' : '';?>>Março</option>
                                <option value="4" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 4 ? 'selected' : '';?>>Abril</option>
                                <option value="5" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 5 ? 'selected' : '';?>>Maio</option>
                                <option value="6" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 6 ? 'selected' : '';?>>Junho</option>
                                <option value="7" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 7 ? 'selected' : '';?>>Julho</option>
                                <option value="8" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 8 ? 'selected' : '';?>>Agosto</option>
                                <option value="9" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 9 ? 'selected' : '';?>>Setembro</option>
                                <option value="10" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 10 ? 'selected' : '';?>>Outubro</option>
                                <option value="11" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 11 ? 'selected' : '';?>>Novembro</option>
                                <option value="12" <?php echo (int)$FinancialReadjustmentsResult->month == 3 || (int)date('m') == 12 ? 'selected' : '';?>>Dezembro</option>                                
                            </select>                            

                        </div>

                    </div>

                    <div class="form-group row">

                        <div class="col-sm-3">

                            <label for="readjustment">

                                Valor reajuste: <span class="text-danger">* Obrigatório</span>


                            </label>

                            <input type="text" class="form-control form-control percentage" maxlength="10" id="readjustment" name="readjustment" value="<?php echo isset($FinancialReadjustmentsResult->readjustment) ? number_format($FinancialReadjustmentsResult->readjustment, 4, ',', '.') : '';?>">

                        </div>

                        <div class="col-sm-3 ">

                            <label for="status">Situação: <span class="text-danger">* Obrigatório</span></label>

                            <select class="form-control form-control" id="status" name="status">
                                <option value="" selected>Selecione</option>
                                <option value="1" <?php echo (int)$FinancialReadjustmentsResult->status == 1 ? 'selected' : '';?>>Ativo</option>
                                <option value="2" <?php echo (int)$FinancialReadjustmentsResult->status == 2 ? 'selected' : '';?>>Inativo</option>
                                <option value="3" <?php echo (int)$FinancialReadjustmentsResult->status == 3 ? 'selected' : '';?>>Excluído</option>
                            </select>

                        </div>

                    </div>

                    <div class="form-group row text-center">

                        <div class="col-sm-12">

                            <button type="button" class="btn btn-primary btn-user btn-block mb-0" onclick="sendForm('#frmReadjustments', '', true, '', 0, '', '<?php echo $FinancialReadjustmentsValidate->getFinancialReadjustmentId() > 0 ? 'Atualizando cadastro' : 'Cadastrando novo reajuste';?>', 'random', 'circle', 'sm', true)">

                                <i class="far fa-save"></i> <?php echo ((int)$FinancialReadjustmentsValidate->getFinancialReadjustmentId() > 0 ? 'Salvar alterações do reajuste' : 'Cadastrar novo reajuste') ?>

                            </button>

                        </div>

                    </div>

                    <input type="hidden" name="TABLE" value="financial_readjustments" />
                    <input type="hidden" name="ACTION" value="financial_readjustments_save" />
                    <input type="hidden" name="FOLDER" value="action" />
                    <input type="hidden" name="financial_readjustments_id" value="<?php echo $FinancialReadjustmentsValidate->getFinancialReadjustmentId();?>" />
                    
                </form>

            </div>

        </div>

    </div>

<?php

/** Caso o token de acesso seja inválido, informo */
}else{
	
    /** Informa que o usuário precisa efetuar autenticação junto ao sistema */
    $authenticate = true;   	

    /** Informo */
    throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
}