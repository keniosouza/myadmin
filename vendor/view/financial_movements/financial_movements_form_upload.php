<?php

/** Importação de classes  */
use vendor\model\FinancialMovements;

/** Instânciamento de classes  */
$FinancialMovements = new FinancialMovements();

/** Parametros de entrada  */
$financialMovementsId = isset($_POST['financial_movements_id']) ? $Main->antiInjection( filter_input(INPUT_POST,'financial_movements_id', FILTER_SANITIZE_SPECIAL_CHARS) ) : '';

/** Controles  */
$err = 0;
$msg = "";
$list = "";

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){          

        /** Verifica se o movimento foi informado */
        if($financialMovementsId > 0){ 
            
            
            /** Localiza a movimentação informada */
            $FinancialMovementsResult = $FinancialMovements->Get($financialMovementsId);

            ?>


            <form class="w-100" id="frmFinancialMovements" autocomplete="off"> 

                <div class="form-group row">

                    <div class="col-sm-12">

                        <label for="selectFiles"><span class="text-danger">* Tamanho máximo do arquivo 5mb</span></label>
                        <input type="file" id="selectFiles" class="upload filestyle" accept="application/pdf, application/msword, application/vnd.ms-excel, image/*" />
                        <div id="preview"></div>
                        <div id="results" class="row"></div>

                    </div>

                </div> 

                <input type="hidden" name="financial_movements_id" value="<?php echo (int)$FinancialMovementsResult->financial_movements_id;?>"/>
                <input type="hidden" name="TABLE" value="financial_movements"/>
                <input type="hidden" name="ACTION" value="financial_movements_upload_file"/>
                <input type="hidden" name="FOLDER" value="action" />


            </form>


            <script type="text/javascript">

                /** Carrega as mascaras dos campos inputs */
                $(document).ready(function(e) {

                    /** inputs mask */
                    loadMask();  

                    /** Upload files */
                    uploadFiles('action', 'financial_movements', 'financial_movements_upload_file', 0, '', <?php echo (int)$FinancialMovementsResult->financial_movements_id;?>);                

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
                'title' => 'Gerenciando movimentação / ' . ((int)$FinancialMovementsResult->financial_entries_id > 0 ? '<span class="badge badge-success">Entrada</span>' : '<span class="badge badge-danger">Saída</span>'),             
                        
            );  


            /** Envio **/
            echo json_encode($result);

            /** Paro o procedimento **/
            exit;         


        }else{

            /** Informo */
            throw new InvalidArgumentException("<ol><li>Nenhum movimento informado para esta solicitação</li></ol>", 0);
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