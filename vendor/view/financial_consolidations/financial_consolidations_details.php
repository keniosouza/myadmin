<?php
/** Importação de classes */
use vendor\model\FinancialMovements;
use vendor\controller\Financial_movements\FinancialMovementsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){  

        /** Instânciamento de classes  */
        $FinancialMovementsValidate = new FinancialMovementsValidate();          

        /** Parametros de entrada  */
        $numeroDocumento = isset($_POST['numero_documento']) ? (string)filter_input(INPUT_POST, 'numero_documento', FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $dataVencimento  = isset($_POST['data_vencimento'])  ? (string)filter_input(INPUT_POST, 'data_vencimento', FILTER_SANITIZE_SPECIAL_CHARS)  : '';
        
        /** Validando os campos de entrada */
        $FinancialMovementsValidate->setReference($numeroDocumento);
        $FinancialMovementsValidate->setMovementDateScheduled($dataVencimento);

        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados ou 
         * efetua o cadastro de um novo*/
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);        

        } else {  
            
            /** Instância da classe */
            $FinancialMovements = new FinancialMovements;

            /** Consulta um item pelo número do documento */
            $FinancialMovementsResults = $FinancialMovements->SearchByDocumentNumber($FinancialMovementsValidate->getReference(), $FinancialMovementsValidate->getMovementDateScheduled());   
        
        }   
        
        ?>

        <ul class="nav nav-tabs" id="pills-tabDetails" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="details-10-tab" data-toggle="pill" href="#details-10" role="tab" aria-controls="details-10" aria-selected="true">Movimentação</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link " id="details-11-tab" data-toggle="pill" href="#details-11" role="tab" aria-controls="details-11" aria-selected="true">Sacado</a>
            </li> 
            <li class="nav-item" role="presentation">
                <a class="nav-link " id="details-12-tab" data-toggle="pill" href="#details-12" role="tab" aria-controls="details-12" aria-selected="true">Consolidação</a>
            </li>                
        </ul>

        <div class="tab-content bg-white p-2" id="pills-tabtabDetailsContent">
            <div class="tab-pane fade active show" id="details-10" role="tabpanel" aria-labelledby="details-10-tab"> 
                
                <div class="container">                    
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Referência</div>
                        <div class="col-9 border-bottom"><?php echo $FinancialMovementsResults->reference;?></div>
                    </div>  
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Valor R$</div>
                        <div class="col-9 border-bottom"><?php echo number_format($FinancialMovementsResults->movement_value, 2, ',', '.');?></div>
                    </div>  
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Agendamento</div>
                        <div class="col-9 border-bottom"><?php echo date('d/m/Y', strtotime($FinancialMovementsResults->movement_date_scheduled));?></div>
                    </div>  
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Descrição</div>
                        <div class="col-9 border-bottom"><?php echo $FinancialMovementsResults->description;?></div>
                    </div>  
                    <div class="row">
                        <div class="col-3 border-right"> <br/></div>
                        <div class="col-9"></div>
                    </div>                                                                                                                                                                   
                </div>                

            </div>
            <div class="tab-pane fade" id="details-11" role="tabpanel" aria-labelledby="details-11-tab"> 

                <div class="container">
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Cód.</div>
                        <div class="col-9 border-bottom"><?php echo $FinancialMovementsResults->client_reference;?></div>
                    </div>
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Sacado</div>
                        <div class="col-9 border-bottom"><?php echo $FinancialMovementsResults->fantasy_name;?></div>
                    </div>
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Responsável</div>
                        <div class="col-9 border-bottom"><?php echo $FinancialMovementsResults->responsible;?></div>
                    </div> 
                    <div class="row">
                        <div class="col-3 border-right border-bottom">CNPJ</div>
                        <div class="col-9 border-bottom"><?php echo $FinancialMovementsResults->document;?></div>
                    </div>                       
                    <div class="row">
                        <div class="col-3 border-right"><br/> </div>
                        <div class="col-9"></div>
                    </div>                                                                                                                                                  
                </div>             
            </div>
            <div class="tab-pane fade" id="details-12" role="tabpanel" aria-labelledby="details-12-tab"> 

                <div class="container">
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Cód.</div>
                        <div class="col-9 border-bottom"><?php echo !empty($FinancialMovementsResults->financial_consolidations_id) ? $Main->setZeros($FinancialMovementsResults->financial_consolidations_id, 3) : '';?></div>
                    </div>
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Data</div>
                        <div class="col-9 border-bottom"><?php echo !empty($FinancialMovementsResults->import_date) ? date('d/m/Y', strtotime($FinancialMovementsResults->import_date)) : '';?></div>
                    </div>
                    <div class="row">
                        <div class="col-3 border-right border-bottom">Responsável</div>
                        <div class="col-9 border-bottom"><?php echo !empty($FinancialMovementsResults->name_first) ? $Main->decryptData($FinancialMovementsResults->name_first) : '';?></div>
                    </div> 
                    <div class="row">
                        <div class="col-3 border-right border-bottom">E-mail</div>
                        <div class="col-9 border-bottom"><?php echo !empty($FinancialMovementsResults->email) ? $FinancialMovementsResults->email : '';?></div>
                    </div>                       
                    <div class="row">
                        <div class="col-3 border-right"><br/> </div>
                        <div class="col-9"></div>
                    </div>                                                                                                                                                  
                </div>             
            </div>            
        </div>            
        

<?php

        /** Pego a estrutura do arquivo */
        $div = ob_get_contents();

        /** Removo o arquivo incluido */
        ob_clean();

        /** Result **/
        $result = array(

            'cod' => 201,
            'data' => $div,
            'title' => 'Detalhes do Título : '.$FinancialMovementsValidate->getReference(),
            'width' => 800
        );  

        /** Envio **/
        echo json_encode($result);

        /** Paro o procedimento **/
        exit;  
        
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