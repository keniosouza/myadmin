<?php

/** Importação de classes  */
use vendor\model\FinancialCategories;
use vendor\controller\financial_categories\FinancialCategoriesValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $FinancialCategories = new FinancialCategories();
        $FinancialCategoriesValidate = new FinancialCategoriesValidate();

        /** Parametros de entrada  */
        $financialCategoriesId = isset($_POST['financial_categories_id']) ? (int)filter_input(INPUT_POST,'financial_categories_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Validando os campos de entrada */
        $FinancialCategoriesValidate->setFinancialCategoriesId($financialCategoriesId);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialCategoriesValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialCategoriesValidate->getErrors(), 0);        

        } else { 
            
            /** Verifica se o ID da conta foi informado */
            if($FinancialCategoriesValidate->getFinancialCategoriesId() > 0){

                /** Consulta os dados da conta */
                $FinancialCategoriesResult = $FinancialCategories->Get($FinancialCategoriesValidate->getFinancialCategoriesId());

            }else{/** Caso o ID da conta não tenha sido informado, carrego os campos como null */

                /** Carrega os campos da tabela */
                $FinancialCategoriesResult = $FinancialCategories->Describe();

            }  
            
        }

    ?>


        <form class="w-100" id="frmFinancialCategories" autocomplete="off"> 

            <div class="form-group row">

                <div class="col-sm-12 ">
                    <label for="description">Descrição:</label>
                    <input type="text" class="form-control form-control" id="description" name="description" maxlength="160" value="<?php echo $FinancialCategoriesResult->description;?> ">
                </div> 

                <div class="col-sm-6 ">
                    <label for="reference">Reference:</label>
                    <input type="text" class="form-control form-control" id="reference" name="reference" maxlength="10" value="<?php echo $FinancialCategoriesResult->reference;?> ">
                </div>                 
                
                <div class="col-sm-6 ">
                    <label for="type">Tipo:</label>
                    <select class="form-control form-control" id="type" name="type">
                        <option value="" selected>Selecione</option>
                        <option value="S" <?php echo $FinancialCategoriesResult->type  === 'S' ? 'selected' : '';?>>Saída</option>
                        <option value="E" <?php echo $FinancialCategoriesResult->type  === 'E' ? 'selected' : '';?>>Entrada</option>
                    </select>
                </div>              

            </div>

            <div class="col-sm-12 text-center p-2" id="sendFinancialCategories"></div>
            
            <input type="hidden" name="financial_categories_id" value="<?php echo (int)$FinancialCategoriesResult->financial_categories_id;?>"/>
            <input type="hidden" name="TABLE" value="financial_categories"/>
            <input type="hidden" name="ACTION" value="financial_categories_save"/>
            <input type="hidden" name="FOLDER" value="action" />

        </form>

        <script type="text/javascript">

            /** Carrega as mascaras dos campos inputs */
            $(document).ready(function(e) {

                /** inputs mask */
                loadMask();  

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
            'title' => ((int)$FinancialCategoriesResult->financial_categories_id == 0 ? 'Cadastrar Nova Categoria' : 'Editar Categoria'), 
            'func' => 'sendForm(\'#frmFinancialCategories\', \'\', true, \'\', 0, \'#sendFinancialCategories\', \''.((int)$FinancialCategoriesResult->financial_categories_id == 0 ? 'Cadastrando categoria financeira' : 'Atualizando categoria financeira').'\', \'random\', \'circle\', \'sm\', true)'
                    
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