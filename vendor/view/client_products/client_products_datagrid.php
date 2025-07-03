<?php

/** Importação de classes  */
use vendor\model\ClientProducts;
use vendor\controller\client_produtcs\ClientProductsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $ClientProducts = new ClientProducts();
        $ClientProducts = new ClientProducts();

        /** Parametros de entrada */
        $clientsId = isset($_POST['clients_id']) ? (int)$Main->antiInjection(filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT)) : 0;

        /** Verifica se existem orçamentos a serem listados */
        if($ClientProducts->Count($clientsId) > 0){
    ?>

        <div class="col-lg-12 mb-4">   
            
            <div class="card shadow mb-12">
                    
                <div class="card-header">          

                    <div class="row">

                        <div class="col-md-9 mb-2">

                            <h4>Produtos</h4>

                        </div>            
                        <div class="col-md-3 text-right mb-2">

                            <button type="button" class="btn btn-secondary btn-sm" onclick="request('FOLDER=view&TABLE=client_products&ACTION=client_products_form&clients_id=<?php echo $clientsId;?>', '', true, '', '', '', 'Preparando formulário...', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar clientes cadastrados">

                                <i class="fas fa-plus-circle mr-1"></i>Cadastrar Produto

                            </button>                        

                        </div>
                    </div>

                </div>

                <div class="card-body">

                    <table class="table caption-top table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm mb-4" id="tblClientProducts">

                        <thead>
                            <tr>
                                <th class="text-center">Produto</th>
                                <th class="text-center">Referência</th>
                                <th class="text-center">Descrição</th>
                                <th class="text-center">Reajuste</th>
                                <th class="text-center">Vencimento</th>
                                <th class="text-center" colspan="2">Valor R$</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                                $ClientProductsResult = $ClientProducts->All($clientsId);
                                foreach($ClientProductsResult as $ClientsKey => $Result){ 

                                    $total += $Result->product_value;
                                    
                                    /** Message para excluir */
                                    $messageDelete = '<b>Deseja realmente excluir o produto '.$Result->description.'?</b>';

                                    /** Data para excluir */
                                    $dataDelete = 'TABLE=client_products&ACTION=client_products_delete&FOLDER=action&client_product_id='.$Result->client_product_id.'&clients_id='.$Result->clients_id;
                            ?>

                            <tr onclick="prepareBudget('#<?php echo $Result->client_product_id;?>', 'products', <?php echo $Result->products_id;?>, 0)" id="<?php echo $Result->client_product_id;?>">
                                <td><?php echo $Result->product;?></td>
                                <td class="text-center" width="65"><?php echo $Result->reference;?></td>
                                <td><?php echo $Result->description;?></td> 
                                <td class="text-center" width="90"><?php echo $Result->readjustment;?></td>                   
                                <td class="text-center" width="90"><?php echo $Main->setzeros($Result->maturity, 2);?></td>  
                                <td class="text-right" width="120"><?php echo number_format($Result->product_value, 2, ',', '.');?></td> 
                                <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="questionModal('<?php echo $dataDelete;?>', '<?php echo $messageDelete;?>')" data-toggle="tooltip" data-placement="left" title="Excluir produto"><i class="far fa-trash-alt"></i></button></td>
                                <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=client_products&ACTION=client_products_form&client_product_id=<?php echo $Result->client_product_id;?>&clients_id=<?php echo $Result->clients_id;?>', '', true, '', '', '', 'Preparando formulário...', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Editar produto"><i class="far fa-edit"></i></button></td>
                            </tr>

                            <?php } ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-right"><b>Total R$ <?php echo number_format($total, 2, ',', '.');?></b></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </div>
<?php

        }else{ 

            /** Informo */
            throw new InvalidArgumentException($clientsId > 0 ? 'Não há produtos cadastrados <button type="button" class="btn btn-secondary btn-sm" onclick="request(\'FOLDER=view&TABLE=client_products&ACTION=client_products_form&clients_id='.$clientsId.'\', \'\', true, \'\', \'\', \'\', \'Preparando formulário...\', \'blue\', \'circle\', \'sm\', true)" data-toggle="tooltip" data-placement="left" title="Carregar clientes cadastrados"><i class="fas fa-plus-circle mr-1"></i>Cadastrar Produto</button> ' : 'Para iniciar, selecione um cliente', 0);             
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
        'message' => '<div class="alert alert-danger mt-2" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Atenção',
        'type' => 'exception',
        'authenticate' => $authenticate		

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}