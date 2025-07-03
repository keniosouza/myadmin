<?php

/** Importação de classes  */
use vendor\model\Documents;
use vendor\controller\documents\DocumentsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $Documents = new Documents();
        $DocumentsValidate = new DocumentsValidate();

        /** Parametros de entrada */
        $clientsId = isset($_POST['clients_id']) ? (int)$Main->antiInjection(filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT)) : 0;

        /** Valida o campo  */
        $DocumentsValidate->setClientsId($clientsId);          

        /** Consulta a quantidade de registros */
        $NumberRecords = $Documents->Count(null, null, null, $clientsId);

        /** Verifica se existem documentos
         *  a serem listados */
        if($NumberRecords > 0){
    ?>

            <div class="col-lg-12 mb-4">  
                
                <div class="card shadow mb-12">
                    
                    <div class="card-header">   

                        <div class="row">                            

                            <div class="col-md-9 mb-2">

                                <h4>Documentos</h4>

                            </div>

                            <div class="col-md-3 text-right mb-2">

                                <button type="button" class="btn btn-secondary btn-sm float-right" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_form&clients_id=<?php echo $clientsId;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">
                                    <i class="fas fa-plus-circle mr-1"></i>Cadastrar documento
                                </button>                      

                            </div>

                        </div>
                        
                    </div> 
                    
                    <div class="card-body">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm mb-4" id="tableDocuments" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Data</th>
                                    <th class="text-center">Descrição</th>
                                    <th class="text-center">Categoria</th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                                <tbody>
                                
                            <?php  
                            
                                /** Consulta os usuário cadastrados*/
                                $DocumentsResult = $Documents->All($start, $max, (int)$documentsCategorysId, (string)$tag, (string)$label, $clientsId);
                                foreach($DocumentsResult as $DocumentsKey => $Result){ 
                            ?>
                                
                                <tr>
                                    <td class="text-center" width="60"><?php echo $Main->setZeros($Result->documents_id, 3);?></td>
                                    <td class="text-center" width="60"><?php echo date("d/m/Y", strtotime($Result->date_register));?></td>  
                                    <td class="text-left"><?php echo $Result->description;?></td>                                 
                                    <td class="text-left"><?php echo $Result->categorys;?></td>   
                                    <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_view&documents_id=<?php echo $Result->documents_id;?>', '#loadContent', true, '', '', '', 'Carregando informações do documento', 'blue', 'circle', 'sm', true)"><i class="fa fa-search-plus" aria-hidden="true"></i></button></td> 
                                    <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_details&documents_id=<?php echo $Result->documents_id;?>', '#loadContent', true, '', '', '', 'Carregando informações do documento', 'blue', 'circle', 'sm', true)"><i class="fa fa-info" aria-hidden="true"></i></button></td>
                                    <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_form&documents_id=<?php echo $Result->documents_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="fas fa-edit mr-1"></i></button></td>
                                </tr>                                 

                            <?php } ?> 
                                                                
                            </tbody>                            

                        </table>     

                    </div>

                </div>

            </div>
<?php

        }else{ 

            /** Informo */
            throw new InvalidArgumentException($clientsId > 0 ? 'Não há documentos cadastrados <button type="button" class="btn btn-secondary btn-sm float-right ml-2" onclick="request(\'FOLDER=view&TABLE=documents&ACTION=documents_form&clients_id=<?php echo $clientsId;?>\', \'#loadContent\', true, \'\', \'\', \'\', \'Preparando formulário\', \'blue\', \'circle\', \'sm\', true)"><i class="fas fa-plus-circle mr-1"></i>Cadastrar documento</button>  ' : '', 0);             
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