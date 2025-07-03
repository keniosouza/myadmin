<?php

/** Importação de classes  */
use vendor\model\Company;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){       

        /** Instânciamento de classes  */
        $Company = new Company();

        /** Parametros de entrada */
        $companyId = isset($_POST['company_id']) ? $Main->antiInjection($_POST['company_id']) : 0;

        /** Verifica se o ID do projeto foi informado */
        if($companyId > 0){

            /** Consulta os dados da empresa */
            $CompanyResult = $Company->Get($companyId);

        }else{/** Caso o ID da empresa não tenha sido informado, carrego os campos como null */

            /** Carrega os campos da tabela */
            $CompanyResult = $Company->Describe();

        }

        /** Controles  */
        $err = 0;
        $msg = "";



    ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                        
                        <div class="col-md-4">
                            
                            <h5 class="card-title"><?php echo $companyId > 0 ? 'Editando dados da empresa' : 'Cadastrar nova empresa';?></h5>
                        
                        </div>

                        <div class="col-md-8 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=company&ACTION=company_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova empresa">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>


                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=company&ACTION=company_datagrid', '#loadContent', true, '', '', '', 'Carregando empresas cadastradas', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar empresas cadastradas">

                                <i class="fas fa-plus-circle mr-1"></i>Empresas Cadastradas

                            </button>                        

                        </div>
                    
                    </div>            

                </div>


                <div class="card-body">

                    <form class="user" id="frmCompany" autocomplete="off">
                        
                        <div class="form-group row">
                            
                            <div class="col-sm-6 mb-2">

                                <label for="company_name">Razão Social:</label>
                                <input type="text" class="form-control form-control" maxlength="255" id="company_name" name="company_name" value="<?php echo $CompanyResult->company_name;?>" placeholder="Informe a razão social da empresa">
                            </div>

                            <div class="col-sm-6 mb-2">

                                <label for="fantasy_name">Nome Fantasia:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="fantasy_name" name="fantasy_name" value="<?php echo $CompanyResult->fantasy_name;?>" placeholder="Informe o nome fantasia da empresa">
                            </div>                        
                                                                        
                        </div> 

                        <div class="form-group row">
                            
                            <div class="col-sm-3 mb-2">

                                <label for="document">CPF / CNPJ:</label>
                                <input type="text" class="form-control form-control number" maxlength="14" id="document" name="document" value="<?php echo $Main->formatarCPF_CNPJ($CompanyResult->document);?>" placeholder="Informe CPF/CNPJ">
                            </div> 

                            <div class="col-sm-3 mb-2">

                                <label for="zip_code">CEP:</label>
                                <input type="text" class="form-control form-control postal_code" maxlength="9" id="zip_code" name="zip_code" value="<?php echo $CompanyResult->zip_code;?>" placeholder="Informe o CEP">
                            </div> 
                            
                            <div class="col-sm-4 mb-2">

                                <label for="adress">Endereço:</label>
                                <input type="text" class="form-control form-control" maxlength="255" id="adress" name="adress" value="<?php echo $CompanyResult->adress;?>" placeholder="Informe o endereço">
                            </div> 
                            
                            <div class="col-sm-2 mb-2">

                                <label for="number">Número:</label>
                                <input type="text" class="form-control form-control" maxlength="10" id="number" name="number" value="<?php echo $CompanyResult->number;?>" placeholder="Informe o número">
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            
                            <div class="col-sm-3 mb-2">

                                <label for="complement ">Complemento:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="complement" name="complement" value="<?php echo $CompanyResult->complement;?>" placeholder="Informe o complemento">
                            </div>   
                            
                            <div class="col-sm-3 mb-2">

                                <label for="district">Bairro:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="district" name="district" value="<?php echo $CompanyResult->district;?>" placeholder="Informe o bairro">
                            </div> 
                            
                            <div class="col-sm-3 mb-2">

                                <label for="city ">Cidade:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="city " name="city " value="<?php echo $CompanyResult->city ;?>" placeholder="Informe a cidade">
                            </div> 
                            
                            <div class="col-sm">

                                <label for="state_initials">Estado:</label>

                                <select class="form-control form-control" id="state_initials " name="state_initials">
                                        <option value="" selected>Selecione</option>
                                        <option value="AC" <?php echo $CompanyResult->state_initials === 'AC' ? 'selected' : '';?>>AC</option>
                                        <option value="AL" <?php echo $CompanyResult->state_initials === 'AL' ? 'selected' : '';?>>AL</option>
                                        <option value="AP" <?php echo $CompanyResult->state_initials === 'AP' ? 'selected' : '';?>>AP</option>
                                        <option value="AM" <?php echo $CompanyResult->state_initials === 'AM' ? 'selected' : '';?>>AM</option>
                                        <option value="BA" <?php echo $CompanyResult->state_initials === 'BA' ? 'selected' : '';?>>BA</option>
                                        <option value="CE" <?php echo $CompanyResult->state_initials === 'CE' ? 'selected' : '';?>>CE</option>
                                        <option value="DF" <?php echo $CompanyResult->state_initials === 'DF' ? 'selected' : '';?>>DF</option>
                                        <option value="ES" <?php echo $CompanyResult->state_initials === 'ES' ? 'selected' : '';?>>ES</option>
                                        <option value="GO" <?php echo $CompanyResult->state_initials === 'GO' ? 'selected' : '';?>>GO</option>
                                        <option value="MA" <?php echo $CompanyResult->state_initials === 'MA' ? 'selected' : '';?>>MA</option>
                                        <option value="MT" <?php echo $CompanyResult->state_initials === 'MT' ? 'selected' : '';?>>MT</option>
                                        <option value="MS" <?php echo $CompanyResult->state_initials === 'MS' ? 'selected' : '';?>>MS</option>
                                        <option value="MG" <?php echo $CompanyResult->state_initials === 'MG' ? 'selected' : '';?>>MG</option>
                                        <option value="PA" <?php echo $CompanyResult->state_initials === 'PA' ? 'selected' : '';?>>PA</option>
                                        <option value="PB" <?php echo $CompanyResult->state_initials === 'PB' ? 'selected' : '';?>>PB</option>
                                        <option value="PR" <?php echo $CompanyResult->state_initials === 'PR' ? 'selected' : '';?>>PR</option>
                                        <option value="PE" <?php echo $CompanyResult->state_initials === 'PE' ? 'selected' : '';?>>PE</option>
                                        <option value="PI" <?php echo $CompanyResult->state_initials === 'PI' ? 'selected' : '';?>>PI</option>
                                        <option value="RJ" <?php echo $CompanyResult->state_initials === 'RJ' ? 'selected' : '';?>>RJ</option>
                                        <option value="RN" <?php echo $CompanyResult->state_initials === 'RN' ? 'selected' : '';?>>RN</option>
                                        <option value="RS" <?php echo $CompanyResult->state_initials === 'RS' ? 'selected' : '';?>>RS</option>
                                        <option value="RO" <?php echo $CompanyResult->state_initials === 'RO' ? 'selected' : '';?>>RO</option>
                                        <option value="RR" <?php echo $CompanyResult->state_initials === 'RR' ? 'selected' : '';?>>RR</option>
                                        <option value="SC" <?php echo $CompanyResult->state_initials === 'SC' ? 'selected' : '';?>>SC</option>
                                        <option value="SP" <?php echo $CompanyResult->state_initials === 'SP' ? 'selected' : '';?>>SP</option>
                                        <option value="SE" <?php echo $CompanyResult->state_initials === 'SE' ? 'selected' : '';?>>SE</option>
                                        <option value="TO" <?php echo $CompanyResult->state_initials === 'TO' ? 'selected' : '';?>>TO</option>
                                </select>                        

                            </div>  
                            
                            <div class="col-sm">

                                <label for="active">Ativo:</label>

                                <select class="form-control form-control" id="active" name="active">
                                        <option value="S" <?php echo $CompanyResult->active  === 'S' ? 'selected' : '';?>>Sim</option>
                                        <option value="N" <?php echo $CompanyResult->active  != 'S' ? 'selected' : '';?>>Não</option>
                                </select>                        

                            </div>                          

                        </div>                    
                        
                        <input type="hidden" name="TABLE" value="company" />
                        <input type="hidden" name="ACTION" value="company_save" />
                        <input type="hidden" name="FOLDER" value="action" />
                        <input type="hidden" name="company_id" value="<?php echo $companyId;?>" />

                        <div class="col-sm-12">
                                
                            <label for="btn-save"></label>
                            <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmCompany', '', true, '', 0, '', '<?php echo $companyId > 0 ? 'Atualizando cadastro' : 'Cadastrando nova empresa';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$companyId > 0 ? 'Salvar alterações da empresa' : 'Cadastrar nova empresa') ?></a>                               
                        </div>                     

                    </form>

                </div>

            </div>

        </div>

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