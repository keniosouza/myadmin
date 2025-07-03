<?php
/** Carregamento da classe de gerar PDF */
require_once('./vendor/library/dompdf/autoload.php');

# Instancia da classe phplot
require_once 'vendor/library/phplot/phplot.php'; 

/** Importação de classes  */
use vendor\model\FinancialMovements;
use vendor\controller\financial_movements\FinancialMovementsValidate;
use vendor\controller\pdf\PdfGenerate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){           

        /** Instânciamento de classes  */
        $FinancialMovements = new FinancialMovements();
        $FinancialMovementsValidate = new FinancialMovementsValidate();
        $PdfGenerate = new PdfGenerate();

        /** Parametros de filtro por company */
        $companyId = isset($_SESSION['USERSCOMPANYID']) ? $_SESSION['USERSCOMPANYID'] : 0;  

        /** Parametros de entrada  */
        $search      = isset($_POST['search'])    ? (string)filter_input(INPUT_POST,'search',  FILTER_SANITIZE_SPECIAL_CHARS)    : '';
        $type        = isset($_POST['type'])      ? (string)filter_input(INPUT_POST,'type',  FILTER_SANITIZE_SPECIAL_CHARS)      : '';
        $status      = isset($_POST['status'])    ? (int)filter_input(INPUT_POST,'status',  FILTER_SANITIZE_SPECIAL_CHARS)       : 0;
        $dateStart   = isset($_POST['dateStart']) ? (string)filter_input(INPUT_POST,'dateStart',  FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $dateEnd     = isset($_POST['dateEnd'])   ? (string)filter_input(INPUT_POST,'dateEnd',  FILTER_SANITIZE_SPECIAL_CHARS)   : '';
        $printType   = isset($_POST['printType']) ? (int)filter_input(INPUT_POST,'printType',  FILTER_SANITIZE_SPECIAL_CHARS)    : 0;
        $orientation = null;

        /** Controles */
        $i=0;
        $total = '0.00';
        $totalGeneral = '0.00';
        $totalReceivables = '0.00';
        $totalOutputs = '0.00';
        $totalEntries = '0.00';
        $qtdeOutputs = '0.00';
        $qtdeEntries = '0.00'; 
        $totalOutputsPaid = '0.00';
        $totalEntriesPaid = '0.00';


        /** Verifica se existe consulta informada para validar os campos */
        

        /** Valida os campos de entrada */
        //$FinancialMovementsValidate->setSearch($search);       
        //$FinancialMovementsValidate->setType($type);
        //$FinancialMovementsValidate->setStatusSearch($status);
        $FinancialMovementsValidate->setDateStart($dateStart);
        $FinancialMovementsValidate->setDateEnd($dateEnd);
        $FinancialMovementsValidate->setPrintType($printType);

        /** Verifico a existência de erros */
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);

        } else {

            switch($FinancialMovementsValidate->getPrintType()){

                case 1 : include('financial_movements_datagrid.php');           break;
                case 2 : include('financial_movements_datagrid.php');           break;
                case 3 : include('financial_movements_worksheet_portrait.php'); break;
                case 5 : include('financial_movements_bar_portrait.php');       break;
                case 6 : include('financial_movements_pie_portrait.php');       break;
                case 7 : include('financial_movements_bar_landscape.php');      break;
                case 8 : include('financial_movements_pie_landscape.php');      break;

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
        'title' => 'Atenção',
        'type' => 'exception',
        'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}