<?php

/** Importação de classes  */
use vendor\model\FinancialBalanceAdjustment;
use vendor\controller\financial_balance_adjustment\FinancialBalanceAdjustmentValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){          

        /** Instânciamento de classes  */
        $FinancialBalanceAdjustment = new FinancialBalanceAdjustment();
        $FinancialBalanceAdjustmentValidate = new FinancialBalanceAdjustmentValidate();

        /** Parametros de entrada  */
        $financialAccountsId = isset($_POST['financial_accounts_id']) ? (int)filter_input(INPUT_POST,'financial_accounts_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Validando os campos de entrada */
        $FinancialBalanceAdjustmentValidate->setFinancialAccountsId($financialAccountsId);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialBalanceAdjustmentValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialBalanceAdjustmentValidate->getErrors(), 0);        

        } else {       

            /** Controles  */
            $account = "";

            /** Verifica se a conta a ser listado o histórico foi informada */
            if($FinancialBalanceAdjustmentValidate->getFinancialAccountsId() > 0){ ?>

                <table id="tableFinancialBalanceAdjustment" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">  
                    
                    <thead>
                        <tr>
                            <th class="text-center">Data</th>
                            <th class="text-center">Atual R$</th>
                            <th class="text-center">Ajuste R$</th>
                            <th class="text-center">Responsável</th>
                            <th class="text-center">Descrição</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php

                    /** Consulta o histórico de ajustes do saldo da conta */
                    $FinancialBalanceAdjustmentResult = $FinancialBalanceAdjustment->Historic($FinancialBalanceAdjustmentValidate->getFinancialAccountsId());            
                
                    /** Lista o histórioo de ajustes efetuados */
                    foreach($FinancialBalanceAdjustmentResult as $FinancialBalanceAdjustmentKey => $Result){     
                        
                        $account =  $Result->description_account;
                    ?>
                    
                        <tr>
                            <td class="text-center"><?php echo date('d/m/Y', strtotime($Result->adjustment_date));?></td>
                            <td class="text-right"><?php echo number_format($Result->previous_value, 2, ',', '.') ;?></td>
                            <td class="text-right"><?php echo number_format($Result->adjusted_value , 2, ',', '.') ;?></td>
                            <td><?php echo $Result->responsible;?></td>
                            <td><?php echo $Result->description;?></td>
                        </tr>


            <?php } ?>

                    </tbody>

                </table> 

                <script type="text/javascript">
                
                $(document).ready(function () {

                    /** Controller table */
                    $('#tableFinancialBalanceAdjustment').DataTable({

                        language: {
                            decimal:       ",",
                            processing:    "Processamento em andamento...",
                            search:        "Pesquisar:",
                            lengthMenu:    "Mostrar _MENU_ registros",
                            info:           "Exibir registros _START_ &agrave; _END_ de _TOTAL_ registros",
                            infoEmpty:      "Exibição de item 0 &agrave; 0 de 0 registros",
                            infoFiltered:   "(filtrado de _MAX_ registros no total)",
                            infoPostFix:    "",
                            loadingRecords: "Carregando...",
                            zeroRecords:    "Nenhum registro para visualizar",
                            emptyTable:     "Sem dados disponíveis na tabela",
                            paginate: {
                                first:      "Primeiro",
                                previous:   "Anterior",
                                next:       "Próximo",
                                last:       "Último"
                            },
                            aria: {
                                sortAscending:  ": ativo para classificar a coluna em ordem crescente",
                                sortDescending: ": ativo para classificar a coluna em ordem decrescente"
                            }
                        },

                        columnDefs: [
                            /*{
                                targets: [ 0 ],
                                orderData: [ 0, 1 ]
                            }, */
                            {
                                targets: [ 1 ],
                                orderData: [ 1, 0 ]
                            },
                        ],

                        lengthMenu : [[5, -1], [5]]        

                        });
                    

                });        
                </script>

            <?php

                /** Pego a estrutura do arquivo */
                $div = ob_get_contents();

                /** Removo o arquivo incluido */
                ob_clean();

                /** Result **/
                $result = array(

                    'cod' => 201,
                    'data' => $div,
                    'title' => $account . ' - Histórico de ajuste de saldo',
                    'width' => 900,/** Largura da janela de visualização */
                    'height' => 600

                );


                sleep(1);

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;       


            }
        }

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
        'message' => '<div class="alert alert-danger" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}