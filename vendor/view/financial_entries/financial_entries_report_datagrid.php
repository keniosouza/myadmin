<?php

/** Importação de classes  */
use vendor\model\FinancialEntries;
use vendor\model\FinancialAccounts;
use vendor\controller\financial_entries\FinancialEntriesValidate;
use vendor\model\FinancialCategories;
use vendor\model\Clients;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){  

    /** Instânciamento de classes  */
    $FinancialEntries = new FinancialEntries();
    $FinancialAccounts = new FinancialAccounts();
    $FinancialCategories = new FinancialCategories();
    $FinancialEntriesValidate = new FinancialEntriesValidate();
    $Clients = new Clients();

    ?>

    <div class="col-md-12">

        <div class="card shadow-sm border">

            <div class="card-header">

                <div class="row">

                    <div class="col-md-6">

                    <h5>

                        Emissão de relatórios

                    </h5>

                    </div>

                    <div class="col-md-6 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_datagrid', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova entrada">

                            <i class="fas fa-plus-circle mr-1"></i>Voltar

                        </button>


                        <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_datagrid', '#loadContent', true, '', '', '', 'Carregando entradas cadastradas', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar contas cadastradas">

                            <i class="fas fa-plus-circle mr-1"></i>Entradas Cadastradas

                        </button>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <form id="frmFinancialEntriesReport">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label for="description">

                                    Descrição

                                </label>

                                <input type="text" class="form-control" id="description" name="description">
                                
                            </div>

                        </div>

                        <div class="col-md-12 text-right">

                            <a type="button" class="btn btn-primary" onclick="sendForm('#frmFinancialEntriesReport')">

                                Buscar

                            </a>

                        </div>

                    </div>

                    <input type="hidden" name="TABLE" value="financial_entries" />
                    <input type="hidden" name="ACTION" value="financial_entries_report_search" />
                    <input type="hidden" name="FOLDER" value="action" />

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