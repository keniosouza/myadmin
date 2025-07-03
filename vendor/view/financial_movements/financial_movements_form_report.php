<?php

/** Importação de classes  */
use vendor\model\FinancialMovements;
use vendor\controller\financial_movements\FinancialMovementsValidate;

try{ 

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        
    
        /** Instânciamento de classes  */
        $FinancialMovements = new FinancialMovements();
        $FinancialMovementsValidate = new FinancialMovementsValidate();    
        
        
        ?>

        <div class="col-lg-8">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <h5 class="card-title">Gerar Relatório de Movimentações Financeiras</h5>                

                </div>

                <div class="card-body">

                    <form id="frmSearchFinancialMovements">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <!--<label for="fantasy_name">Pesquisa: </label>-->
                                    <input type="text" class="form-control w-100" id="search" name="search" value="<?php echo $FinancialMovementsValidate->getSearch();?>" placeholder="Informe sua pesquisa" data-toggle="tooltip" data-placement="top" title="Informe sua consulta" >
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <!--<label for="active">Tipo: </label>-->
                                    <select class="form-control form-control w-100" id="type" name="type">
                                        <option value="" selected>Selecione o tipo</option>    
                                        <option value="E" <?php echo $FinancialMovementsValidate->getType() == 'E' ? 'selected' : '';?>>Entradas</option>
                                        <option value="S" <?php echo $FinancialMovementsValidate->getType() == 'S' ? 'selected' : '';?>>Saídas</option>                            
                                    </select> 
                                </div>
                            </div> 

                            <div class="col-md-3">
                                <div class="form-group">
                                    <!--<label for="status">Pago: </label>-->
                                    <select class="form-control form-control w-100" id="status" name="status">
                                        <option value="" selected>Selecione Pago</option>    
                                        <option value="1" <?php echo $FinancialMovementsValidate->getStatusSearch() == 1 ? 'selected' : '';?>>Não</option>
                                        <option value="2" <?php echo $FinancialMovementsValidate->getStatusSearch() == 2 ? 'selected' : '';?>>Sim</option>
                                    </select> 
                                </div> 
                            </div>                                      

                            <div class="col-md-3">
                                <div class="form-group">
                                    <!--<label for="fantasy_name">Pesquisa: </label>-->
                                    <input type="text" class="form-control date w-100" id="dateStart" name="dateStart" value="<?php echo !empty($FinancialMovementsValidate->getDateStart()) ? date('d/m/Y',strtotime($FinancialMovementsValidate->getDateStart())) : '';?>" placeholder="Data inicial" data-required="S" data-toggle="tooltip" data-placement="top" title="Informe a data inicial">
                                </div> 
                            </div> 

                            <div class="col-md-3">
                                <div class="form-group">
                                    <!--<label for="fantasy_name">Pesquisa: </label>-->
                                    <input type="text" class="form-control date w-100" id="dateEnd" name="dateEnd" value="<?php echo !empty($FinancialMovementsValidate->getDateEnd()) ? date('d/m/Y',strtotime($FinancialMovementsValidate->getDateEnd())) : '';?>" placeholder="Data final" data-required="S" data-toggle="tooltip" data-placement="top" title="Informe a data final">
                                </div> 
                            </div>  
                            
                        </div>

                        <div class="row">

                            <div class="col-md-2 text-center">
                                <div class="form-group">                            

                                    <div class="custom-control custom-switch">
                                        <input type="radio" class="custom-control-input" id="printType1" name="printType" value="1">
                                        <label class="custom-control-label" for="printType1"><img class="img-fluid" src="img/report_file_portrait.png"/></label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2 text-center">
                                <div class="form-group">

                                    <div class="custom-control custom-switch">    
                                        <input type="radio" class="custom-control-input" id="printType2" name="printType" value="2">
                                        <label class="custom-control-label" for="printType2"><img class="img-fluid" src="img/report_file_landscape.png"/></label>
                                    </div>

                                </div>
                            </div> 

                            <div class="col-md-2 text-center">
                                <div class="form-group">

                                    <div class="custom-control custom-switch">
                                        <input type="radio" class="custom-control-input" id="printType3" name="printType" value="3">
                                        <label class="custom-control-label" for="printType3"><img class="img-fluid" src="img/report_file_worksheet_portrait.png"/></label>
                                    </div>

                                </div>
                            </div>  
                            
                            <!--<div class="col-md-2 text-center">
                                <div class="form-group">

                                    <div class="custom-control custom-switch">
                                        <input type="radio" class="custom-control-input" id="printType4" name="printType" value="4">
                                        <label class="custom-control-label" for="printType4"><img class="img-fluid" src="img/report_file_worksheet_landscape.png"/></label>
                                    </div>

                                </div>
                            </div>-->                     

                            <div class="col-md-2 text-center">
                                <div class="form-group">

                                    <div class="custom-control custom-switch">
                                        <input type="radio" class="custom-control-input" id="printType5" name="printType" value="5">
                                        <label class="custom-control-label" for="printType5"><img class="img-fluid" src="img/report_file_bar_portrait.png"/></label>
                                    </div>

                                </div>
                            </div> 

                            <div class="col-md-2 text-center">
                                <div class="form-group">

                                    <div class="custom-control custom-switch">
                                        <input type="radio" class="custom-control-input" id="printType7" name="printType" value="7">
                                        <label class="custom-control-label" for="printType7"><img class="img-fluid" src="img/report_file_bar_landscape.png"/></label>
                                    </div>

                                </div>
                            </div>                      

                            <div class="col-md-2 text-center">
                                <div class="form-group">

                                    <div class="custom-control custom-switch">
                                        <input type="radio" class="custom-control-input" id="printType6" name="printType" value="6">
                                        <label class="custom-control-label" for="printType6"><img class="img-fluid" src="img/report_file_pie_portrait.png"/></label>
                                    </div>

                                </div>
                            </div>                                              
                            
                            <div class="col-md-2 text-center">
                                <div class="form-group">

                                    <div class="custom-control custom-switch">
                                        <input type="radio" class="custom-control-input" id="printType8" name="printType" value="8">
                                        <label class="custom-control-label" for="printType8"><img class="img-fluid" src="img/report_file_pie_landscape.png"/></label>
                                    </div>

                                </div>
                            </div>                     

                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                <button class="btn btn-info w-100" type="button" onclick="validateForm('#frmSearchFinancialMovements', 'Consultando por favor aguarde...', '', 'S')">Gerar Relatório</button>
                            </div>  
                            
                        </div>            

                        <input type="hidden" name="TABLE" value="financial_movements"/>
                        <input type="hidden" name="ACTION" value="financial_movements"/>
                        <input type="hidden" name="FOLDER" value="print" />                                        

                    </form> 

                </div>

            </div>

        </div>  

    <script type="text/javascript">

        /** Carrega as mascaras dos campos inputs */
        $(document).ready(function(e) {

            /** inputs mask */
            loadMask();  

        });

    </script>  

<?php

    /** Caso o token de acesso seja inválido, informo */
    }else{
		
		/** Informa que o usuário precisa efetuar autenticação junto ao sistema */
		$authenticate = true;			

        /** Informo */
        throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
    } 

}catch(Exception $exception){

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => $exception->getMessage(),
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}