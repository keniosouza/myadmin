<?php

/** Importação de classes  */
use vendor\model\Products;
use vendor\model\ClientProducts;
use vendor\controller\client_products\ClientProductsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $Products = new Products();
        $ClientProducts = new ClientProducts();
        $ClientProductsValidate = new ClientProductsValidate();

        /** Parametros de entrada  */
        $clientProductId = isset($_POST['client_product_id']) ? (int)filter_input(INPUT_POST,'client_product_id', FILTER_SANITIZE_NUMBER_INT) : 0;
        $clientsId       = isset($_POST['clients_id'])        ? (int)filter_input(INPUT_POST,'clients_id', FILTER_SANITIZE_NUMBER_INT)        : 0;

        /** Validando os campos de entrada */
        $ClientProductsValidate->setClientProductId($clientProductId);
        $ClientProductsValidate->setClientsId($clientsId);

        /** Verifica se o movimento foi informado */
        if($ClientProductsValidate->getClientProductId() > 0){ 
                            
            /** Localiza a movimentação informada */
            $ClientProductsResult = $ClientProducts->Get($ClientProductsValidate->getClientProductId());

        }else{

            /** Localiza a movimentação informada */
            $ClientProductsResult = $ClientProducts->Describe();
        }
        ?>

        <form class="w-100" id="frmClientProducts" autocomplete="off"> 

            <div class="form-group row">

                <div class="col-sm-12 ">
                    <label for="produtct_id">Produto:</label>
                    <select class="form-control form-control" id="produtct_id " name="produtct_id">
                        <option value="" selected>Selecione</option>

                        <?php 
                            $ProductsResult = $Products->All(0 ,0);
                            foreach($ProductsResult as $ProductsKey => $Result){ 
                        ?>
                        <option value="<?php echo $Result->products_id;?>" <?php echo $Result->products_id === $ClientProductsResult->products_id ? 'selected' : '';?>><?php echo $Result->description;?></option>

                        <?php } ?>
                    </select>  
                </div>             

            </div>                        

            <div class="form-group row">

                <div class="col-sm-6">
                    <label for="readjustment">Mês reajuste: </label>
                    <select class="form-control form-control" id="readjustment " name="readjustment">
                        <option value="" selected>Selecione</option>
                        <option value="janeiro" <?php echo $ClientProductsResult->readjustment == 'janeiro' ? 'selected' : '';?>>janeiro</option>
                        <option value="fevereiro" <?php echo $ClientProductsResult->readjustment == 'fevereiro' ? 'selected' : '';?>>fevereiro</option>
                        <option value="março" <?php echo $ClientProductsResult->readjustment == 'março' ? 'selected' : '';?>>março</option>
                        <option value="abril" <?php echo $ClientProductsResult->readjustment == 'abril' ? 'selected' : '';?>>abril</option>
                        <option value="maio" <?php echo $ClientProductsResult->readjustment == 'maio' ? 'selected' : '';?>>maio</option>
                        <option value="junho" <?php echo $ClientProductsResult->readjustment == 'junho' ? 'selected' : '';?>>junho</option>
                        <option value="julho" <?php echo $ClientProductsResult->readjustment == 'julho' ? 'selected' : '';?>>julho</option>
                        <option value="agosto" <?php echo $ClientProductsResult->readjustment == 'agosto' ? 'selected' : '';?>>agosto</option>
                        <option value="setembro" <?php echo $ClientProductsResult->readjustment == 'setembro' ? 'selected' : '';?>>setembro</option>
                        <option value="outubro" <?php echo $ClientProductsResult->readjustment == 'outubro' ? 'selected' : '';?>>outubro</option>
                        <option value="novembro" <?php echo $ClientProductsResult->readjustment == 'novembro' ? 'selected' : '';?>>novembro</option>
                        <option value="dezembro" <?php echo $ClientProductsResult->readjustment == 'dezembro' ? 'selected' : '';?>>dezembro</option>
                    </select>                                    
                </div>   

                <div class="col-sm-6">
                    <label for="maturity">Dia Reajuste: </label>
                    <select class="form-control form-control" id="maturity " name="maturity">
                        <option value="" selected>Selecione</option>
                        <?php for($i=1; $i<=31; $i++){?>
                            <option value="<?php echo $i;?>" <?php echo $ClientProductsResult->maturity == $i ? 'selected' : '';?>><?php echo $i;?></option>
                        <?php } ?>
                    </select>                                     
                </div> 
                                

            </div> 

            <div class="form-group row">

                <div class="col-sm-6">
                    <label for="product_value">Valor R$: </label>
                    <input type="text" class="form-control form-control price" id="product_value" name="product_value" value="<?php echo isset($ClientProductsResult->product_value) ? number_format($ClientProductsResult->product_value, 2, ',', '.') : '';?> ">
                </div> 
                
                <div class="col-sm-6">
                    <label for="date_contract">Data Contrato: </label>
                    <input type="text" class="form-control form-control date" id="date_contract" name="date_contract" value="<?php echo isset($ClientProductsResult->date_contract) ? strtotime(date('d/m/Y', $ClientProductsResult->date_contract)) : '';?>" placeholder="__/__/____">
                </div>                          

            </div> 

            <div class="form-group row">                    

                <div class="col-sm-12">
                    <label for="note">Descrição: </label>
                    <textarea class="form-control form-control" id="description" name="description" maxlength="300" placeholder="Exemplo: Software de gerenciamento cartorário"><?php echo isset($ClientProductsResult->description) ? $ClientProductsResult->description : '';?></textarea>
                </div>

                <?php if((int)$ClientProductsResult->movement_user_confirmed > 0){?>
                
                    <div class="col-sm-12">
                        <br/>
                        Confirmação: <?php echo $Main->decryptData($ClientProductsResult->user_confirmed_name_first) ;?> <?php echo $Main->decryptData($ClientProductsResult->user_confirmed_name_last) ;?>
                    </div>
                
                <?php } ?>

                <div class="col-sm-12 text-center p-2" id="sendMovement"></div>                

            </div>

            <input type="hidden" name="client_product_id" value="<?php echo $ClientProductsValidate->getClientProductId();?>"/>
            <input type="hidden" name="clients_id" value="<?php echo $ClientProductsValidate->getClientsId();?>"/>
            <input type="hidden" name="TABLE" value="client_products"/>
            <input type="hidden" name="ACTION" value="client_products_save"/>
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
            'title' => 'Gerenciando produto', 
            'func' => 'sendForm(\'#frmClientProducts\', \'\', true, \'\', 0, \'#sendMovement\', \'Atualizando movimentação\', \'random\', \'circle\', \'sm\', true)'
                    
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