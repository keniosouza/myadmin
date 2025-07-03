<?php

/** Importação de classes  */
use vendor\model\Clients;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $Clients = new Clients();

        /** Parametros de entrada */
        $clientsId = isset($_POST['clients_id']) ? $Main->antiInjection($_POST['clients_id']) : 0;

        /** Verifica se o ID do projeto foi informado */
        if($clientsId > 0){

            /** Consulta os dados do controle de acesso */
            $ClientsResult = $Clients->Get($clientsId);

        }else{/** Caso o ID do controle de acesso não tenha sido informado, carrego os campos como null */

            /** Carrega os campos da tabela */
            $ClientsResult = $Clients->Describe();

        }


    ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                        
                        <div class="col-md-4">
                            
                            <h5 class="card-title"><?php echo $clientsId > 0 ? 'Editando dados do cliente' : 'Cadastrar novo cliente';?></h5>
                        
                        </div>

                        <div class="col-md-8 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=clients&ACTION=clients_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo cliente">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>


                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=clients&ACTION=clients_datagrid', '#loadContent', true, '', '', '', 'Carregando clientes cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar clientes cadastrados">

                                <i class="fas fa-plus-circle mr-1"></i>Clientes Cadastrados

                            </button>                        

                        </div>
                    
                    </div>            

                </div>


                <div class="card-body">

                    <form class="user" id="frmClients" autocomplete="off">

                        
                        <div class="form-group row mb-5">    


                            <div class="col-md-12 mb-9">Informe se o cliente é pessoa jurídica ou física: <span class="text-danger">* Obrigatório</span></div>
                            
                            <div class="col-md-4">                            

                                <div class="custom-control custom-switch">

                                    <input type="radio" class="custom-control-input" id="type_legal" name="type" value="J" <?php echo $ClientsResult->type == 'J' || empty($ClientsResult->type) ? 'checked' : '';?>>
                                    <label class="custom-control-label" for="type_legal">

                                        Jurídica

                                    </label>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="custom-control custom-switch">

                                    <input type="radio" class="custom-control-input" id="type_physics" name="type" value="F" <?php echo $ClientsResult->type == 'F' ? 'checked' : '';?>>
                                    <label class="custom-control-label" for="type_physics">

                                        Física

                                    </label>

                                </div>

                            </div> 
                            
                        </div>
                        
                        <div class="form-group row">
                            
                            <div class="col-sm-6 mb-2">

                                <label for="client_name">Razão Social / Nome: <span class="text-danger">* Obrigatório</span></label>
                                <input type="text" class="form-control form-control" maxlength="255" id="client_name" name="client_name" value="<?php echo $ClientsResult->client_name;?>" placeholder="Informe a razão social da empresa">
                            </div>

                            <div class="col-sm-4 mb-2">

                                <label for="fantasy_name">Nome Fantasia:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="fantasy_name" name="fantasy_name" value="<?php echo $ClientsResult->fantasy_name;?>" placeholder="Informe o nome fantasia da empresa">
                            </div>
                            
                            <div class="col-sm-2 mb-2">

                                <label for="fantasy_name">Referência:</label>
                                <input type="text" class="form-control form-control number" maxlength="20" id="reference" name="reference" value="<?php echo $ClientsResult->reference;?>" placeholder="Informe o código referência da empresa">
                            </div>                             
                                                                        
                        </div> 

                        <div class="form-group row">
                            
                            <div class="col-sm-3 mb-2">

                                <label for="document">CPF / CNPJ: <span class="text-danger">* Obrigatório</span></label>
                                <input type="text" class="form-control form-control number" maxlength="14" id="document" name="document" value="<?php echo $ClientsResult->document;?>" placeholder="Informe CPF/CNPJ">
                            </div> 

                            <div class="col-sm-3 mb-2">

                                <label for="zip_code">CEP:</label>
                                <input type="text" class="form-control form-control postal_code" maxlength="9" id="zip_code" name="zip_code" value="<?php echo $ClientsResult->zip_code;?>" placeholder="Informe o CEP">
                            </div> 
                            
                            <div class="col-sm-4 mb-2">

                                <label for="adress">Endereço:</label>
                                <input type="text" class="form-control form-control" maxlength="255" id="adress" name="adress" value="<?php echo $ClientsResult->adress;?>" placeholder="Informe o endereço">
                            </div> 
                            
                            <div class="col-sm-2 mb-2">

                                <label for="number">Número:</label>
                                <input type="text" class="form-control form-control" maxlength="10" id="number" name="number" value="<?php echo $ClientsResult->number;?>" placeholder="Informe o número">
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            
                            <div class="col-sm-3 mb-2">

                                <label for="complement ">Complemento:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="complement" name="complement" value="<?php echo $ClientsResult->complement;?>" placeholder="Informe o complemento">
                            </div>   
                            
                            <div class="col-sm-3 mb-2">

                                <label for="district">Bairro:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="district" name="district" value="<?php echo $ClientsResult->district;?>" placeholder="Informe o bairro">
                            </div> 
                            
                            <div class="col-sm-3 mb-2">

                                <label for="city">Cidade:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="city" name="city" value="<?php echo $ClientsResult->city ;?>" placeholder="Informe a cidade">
                            </div> 
                            
                            <div class="col-sm">

                                <label for="state_initials">Estado:</label>

                                <select class="form-control form-control" id="state_initials " name="state_initials">
                                        <option value="" selected>Selecione</option>
                                        <option value="AC" <?php echo $ClientsResult->state_initials === 'AC' ? 'selected' : '';?>>AC</option>
                                        <option value="AL" <?php echo $ClientsResult->state_initials === 'AL' ? 'selected' : '';?>>AL</option>
                                        <option value="AP" <?php echo $ClientsResult->state_initials === 'AP' ? 'selected' : '';?>>AP</option>
                                        <option value="AM" <?php echo $ClientsResult->state_initials === 'AM' ? 'selected' : '';?>>AM</option>
                                        <option value="BA" <?php echo $ClientsResult->state_initials === 'BA' ? 'selected' : '';?>>BA</option>
                                        <option value="CE" <?php echo $ClientsResult->state_initials === 'CE' ? 'selected' : '';?>>CE</option>
                                        <option value="DF" <?php echo $ClientsResult->state_initials === 'DF' ? 'selected' : '';?>>DF</option>
                                        <option value="ES" <?php echo $ClientsResult->state_initials === 'ES' ? 'selected' : '';?>>ES</option>
                                        <option value="GO" <?php echo $ClientsResult->state_initials === 'GO' ? 'selected' : '';?>>GO</option>
                                        <option value="MA" <?php echo $ClientsResult->state_initials === 'MA' ? 'selected' : '';?>>MA</option>
                                        <option value="MT" <?php echo $ClientsResult->state_initials === 'MT' ? 'selected' : '';?>>MT</option>
                                        <option value="MS" <?php echo $ClientsResult->state_initials === 'MS' ? 'selected' : '';?>>MS</option>
                                        <option value="MG" <?php echo $ClientsResult->state_initials === 'MG' ? 'selected' : '';?>>MG</option>
                                        <option value="PA" <?php echo $ClientsResult->state_initials === 'PA' ? 'selected' : '';?>>PA</option>
                                        <option value="PB" <?php echo $ClientsResult->state_initials === 'PB' ? 'selected' : '';?>>PB</option>
                                        <option value="PR" <?php echo $ClientsResult->state_initials === 'PR' ? 'selected' : '';?>>PR</option>
                                        <option value="PE" <?php echo $ClientsResult->state_initials === 'PE' ? 'selected' : '';?>>PE</option>
                                        <option value="PI" <?php echo $ClientsResult->state_initials === 'PI' ? 'selected' : '';?>>PI</option>
                                        <option value="RJ" <?php echo $ClientsResult->state_initials === 'RJ' ? 'selected' : '';?>>RJ</option>
                                        <option value="RN" <?php echo $ClientsResult->state_initials === 'RN' ? 'selected' : '';?>>RN</option>
                                        <option value="RS" <?php echo $ClientsResult->state_initials === 'RS' ? 'selected' : '';?>>RS</option>
                                        <option value="RO" <?php echo $ClientsResult->state_initials === 'RO' ? 'selected' : '';?>>RO</option>
                                        <option value="RR" <?php echo $ClientsResult->state_initials === 'RR' ? 'selected' : '';?>>RR</option>
                                        <option value="SC" <?php echo $ClientsResult->state_initials === 'SC' ? 'selected' : '';?>>SC</option>
                                        <option value="SP" <?php echo $ClientsResult->state_initials === 'SP' ? 'selected' : '';?>>SP</option>
                                        <option value="SE" <?php echo $ClientsResult->state_initials === 'SE' ? 'selected' : '';?>>SE</option>
                                        <option value="TO" <?php echo $ClientsResult->state_initials === 'TO' ? 'selected' : '';?>>TO</option>
                                </select>                        

                            </div>  
                            
                            <div class="col-sm">

                                <label for="active">Ativo:</label>

                                <select class="form-control form-control" id="active" name="active">
                                        <option value="S" <?php echo $ClientsResult->active  === 'S' ? 'selected' : '';?>>Sim</option>
                                        <option value="N" <?php echo $ClientsResult->active  != 'S' ? 'selected' : '';?>>Não</option>
                                </select>                        

                            </div>                          

                        </div>   
                        
                        <div class="form-group row">

                            <div class="col-sm-4 mb-2">

                                <label for="responsible">Responsável: <span class="text-danger">* Obrigatório</span></label>
                                <input type="text" class="form-control form-control" maxlength="160" id="responsible" name="responsible" value="<?php echo $ClientsResult->responsible;?>" placeholder="Informe o nome do responsável da empresa">
                            </div>

                            <div class="col-sm-2 mb-2">

                                <label for="responsible_document">Responsável CPF:</label>
                                <input type="text" class="form-control form-control cpf" maxlength="20" id="responsible_document" name="responsible_document" value="<?php echo $ClientsResult->responsible_document;?>" placeholder="999.999.999-99">
                            </div>                             

                            <div class="col-sm-3 mb-2">

                                <label for="email">E-mail:</label>
                                <input type="text" class="form-control form-control" maxlength="200" id="email" name="email" value="<?php echo $ClientsResult->email;?>" placeholder="Informe o e-mail da empresa">
                            </div>

                            <div class="col-sm-1 mb-2">

                                <label for="contract_date">Data contrato:</label>
                                <input type="text" class="form-control form-control date" maxlength="11" id="contract_date" name="contract_date" value="<?php echo isset($ClientsResult->contract_date) ? date('d/m/Y', strtotime($ClientsResult->contract_date)) : '';?>" placeholder="99/99/9999">
                            </div> 
                            
                            <div class="col-sm-1 mb-2">

                                <label for="computers">Estações:</label>
                                <input type="text" class="form-control form-control number" maxlength="11" id="computers" name="computers" value="<?php echo $ClientsResult->computers;?>" placeholder="0">
                            </div>   
                            
                            <div class="col-sm-1 mb-2">

                                <label for="servers">Servidores:</label>
                                <input type="text" class="form-control form-control number" maxlength="11" id="servers" name="servers" value="<?php echo $ClientsResult->servers;?>" placeholder="0">
                            </div>                             
                           
                        </div>
                        
                        <input type="hidden" name="TABLE" value="clients" />
                        <input type="hidden" name="ACTION" value="clients_save" />
                        <input type="hidden" name="FOLDER" value="action" />
                        <input type="hidden" name="clients_id" value="<?php echo $ClientsResult->clients_id;?>" />

                        <div class="col-sm-12">
                                
                            <label for="btn-save"></label>
                            <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmClients', '', true, '', 0, '', '<?php echo $ClientsResult->clients_id> 0 ? 'Atualizando cadastro' : 'Cadastrando novo cliente';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$clientsId > 0 ? 'Salvar alterações do cliente' : 'Cadastrar novo cliente') ?></a>                               
                        </div>                     

                    </form>

                    <div class="col-lg-12"> 

                        <br/>
                        <!-- Content Row -->
                        <div class="row" id="loadProducts"></div>        

                    </div> 
                    
                    <div class="col-lg-12"> 

                        <!-- Content Row -->
                        <div class="row" id="loadDocuments"></div>        

                    </div>   
                    
                    <div class="col-lg-12"> 

                        <!-- Content Row -->
                        <div class="row" id="loadUsers"></div>        

                    </div>                     

                </div>

            </div>

        </div>


        <script type="text/javascript">

        /** Carrega as mascaras dos campos inputs */
        $(document).ready(function(e) {

            /** inputs mask */
            loadMask();

            /** tooltips */
            $('[data-toggle="tooltip"]').tooltip();  

            /** Carrega os produtos do cliente informado */
            request('FOLDER=view&TABLE=client_products&ACTION=client_products_datagrid&clients_id=<?php echo $ClientsResult->clients_id;?>', '', true, '', '', '#loadProducts', 'Carregando produtos...', 'blue', 'circle', 'sm', true);            

            /** Carrega os documentos do cliente informado */
            request('FOLDER=view&TABLE=clients&ACTION=clients_documents_datagrid&clients_id=<?php echo $ClientsResult->clients_id;?>', '', true, '', '', '#loadDocuments', 'Carregando Documentos...', 'blue', 'circle', 'sm', true);                                                

            /** Carrega os documentos do cliente informado */
            request('FOLDER=view&TABLE=users&ACTION=users_datagrid&clients_id=<?php echo $ClientsResult->clients_id;?>', '', true, '', '', '#loadUsers', 'Carregando Usuários...', 'blue', 'circle', 'sm', true);                                                            

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
        'title' => 'Atenção',
        'type' => 'exception',
        'authenticate' => $authenticate		

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}