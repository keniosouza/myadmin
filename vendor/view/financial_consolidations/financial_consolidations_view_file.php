<?php
/** Importação de classes */
use vendor\model\FinancialConsolidations;
use vendor\model\FinancialMovements;
use vendor\controller\Financial_consolidations\FinancialConsolidationsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){  

        /** Instânciamento de classes  */
        $FinancialConsolidationsValidate = new FinancialConsolidationsValidate();          

        /** Parametros de entrada  */
        $financialConsolidationsId = isset($_POST['financial_consolidations_id']) ? (int)filter_input(INPUT_POST, 'financial_consolidations_id', FILTER_SANITIZE_NUMBER_INT) : 0;
        
        /** Validando os campos de entrada */
        $FinancialConsolidationsValidate->setFinancialConsolidationsId($financialConsolidationsId);

        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados ou 
         * efetua o cadastro de um novo*/
        if (!empty($FinancialConsolidationsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialConsolidationsValidate->getErrors(), 0);        

        } else {  

            /** Instância da classe */
            $FinancialMovements = new FinancialMovements;
            $FinancialConsolidations = new FinancialConsolidations;

            /** Localizada a consolidação informada */
            $FinancialConsolidationsResults = $FinancialConsolidations->Get($FinancialConsolidationsValidate->getFinancialConsolidationsId()); 

            /** Consulta as movimentações da respectiva consolidação informada */
            $FinancialMovementsResult = $FinancialMovements->GetConsolidated($FinancialConsolidationsValidate->getFinancialConsolidationsId());
                
        }   

        /** Controles */
        $total = null;
        $cont = 0;
        
        ?>

        <ul class="nav nav-tabs" id="pills-tabDetails" role="tablist">
        <li class="nav-item" role="presentation">
                <a class="nav-link active" id="details-9-tab" data-toggle="pill" href="#details-9" role="tab" aria-controls="details-9" aria-selected="true">Consolidados</a>
            </li>                 
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="details-10-tab" data-toggle="pill" href="#details-10" role="tab" aria-controls="details-10" aria-selected="true">Inconsistencias</a>
            </li>             
            <li class="nav-item" role="presentation">
                <a class="nav-link " id="details-11-tab" data-toggle="pill" href="#details-11" role="tab" aria-controls="details-11" aria-selected="true">Arquivo CNAB 240</a>
            </li>              
        </ul>

        <div class="tab-content bg-white p-2" id="pills-tabtabDetailsContent">

            <div class="tab-pane fade active show" id="details-9" role="tabpanel" aria-labelledby="details-9-tab"> 

                <div class="overflow-auto p-2" style="height: 277px;">

                    <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm p-4">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Referência</th>
                                <th scope="col" class="text-center">Data Pag.</th>
                                <th scope="col" class="text-center">Cliente</th>
                                <th scope="col" class="text-center">Descrição</th>
                                <th scope="col" class="text-center">Valor R$</th>
                            </tr>
                        </thead>
                        <tbody>            

                    <?php

                        foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){   
                            
                            /** Totaliza os consolidados */
                            $total += $Result->movement_value_paid;
                            $cont++;
                            
                            ?> 

                            <tr>
                                <td class="text-center"><?php echo $Result->reference;?></td>
                                <td class="text-center"><?php echo date('d/m/Y', strtotime($Result->movement_date_paid));?></td>
                                <td class="text-left"><?php echo $Result->fantasy_name;?></td>
                                <td class="text-left"><?php echo $Result->description;?></td>
                                <td class="text-right"><?php echo number_format($Result->movement_value_paid, 2, ',', '.');?></td>
                            </tr>                        
                            
                    <?php

                        }

                    ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" align="right">Total de itens: <?php echo $cont;?> - Total Geral R$ <?php echo number_format($total, 2, ',', '.');?></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
           
            </div>        
            
            <div class="tab-pane fade" id="details-10" role="tabpanel" aria-labelledby="details-10-tab" style="max-height: 400px; overflow: scroll;"> 

                <div class="overflow-auto p-2" style="height: 277px;">

                    <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm p-4">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Título</th>
                                <th scope="col" class="text-center">Valor R$</th>
                                <th scope="col" class="text-center">Vencimento</th>
                                <th scope="col" class="text-center">Data Crédito</th>
                                <th scope="col" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php

                            /** Carrega o json com as inconsistências */
                            $inconsistencies = json_decode($FinancialConsolidationsResults->inconsistencies, true);
                            $cont = 0;

                            /** Lista os itens */
                            for($i=0; $i<1000; $i++){ 
                                
                                /** Verifico se existem documentos a serem listados */
                                if(!empty($inconsistencies[$i]['numero_documento'])){

                                    $cont++;
                                
                                ?>

                            <tr>
                                <td class="text-center"><?php echo $inconsistencies[$i]['numero_documento'];?></td>
                                <td class="text-center"><?php echo $inconsistencies[$i]['valor_titulo'];?></td>
                                <td class="text-center"><?php echo $inconsistencies[$i]['vencimento'];?></td>
                                <td class="text-center"><?php echo $inconsistencies[$i]['data_credito'];?></td>
                                <td><?php echo $inconsistencies[$i]['status'];?></td>
                            </tr>

                        <?php }} ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" align="right">Total de itens: <?php echo $cont;?></td>
                            </tr>
                        </tfoot>                        
                    </table>   
                    
                </div>
                                    
            </div>
            
            <div class="tab-pane fade" id="details-11" role="tabpanel" aria-labelledby="details-11-tab"> 

                <textarea class="w-100 border" rows="15"><?php echo mb_convert_encoding($FinancialConsolidationsResults->file_consolidation, 'UTF-8', 'ISO-8859-1');?></textarea>                                                                                                      
           
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
            'title' => 'Detalhes da Consolidação : '.$Main->setZeros($FinancialConsolidationsValidate->getFinancialConsolidationsId(),3).' - '.date('d/m/Y', strtotime($FinancialConsolidationsResults->import_date)),
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
