<?php

/** Importação de classes  */
use vendor\model\Documents;
use vendor\model\FinancialMovements;
use vendor\controller\financial_movements\FinancialMovementsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){          

        /** Instânciamento de classes  */
        $Documents = new Documents();
        $FinancialMovements = new FinancialMovements();
        $FinancialMovementsValidate = new FinancialMovementsValidate();

        /** Parametros de entrada  */
        $financialMovementsId = isset($_POST['id'])   ? filter_input(INPUT_POST,'id', FILTER_SANITIZE_SPECIAL_CHARS)   : '';
        $path                 = isset($_POST['file']) ? filter_input(INPUT_POST,'file', FILTER_SANITIZE_SPECIAL_CHARS) : '';

        /** Validando os campos de entrada */
        $FinancialMovementsValidate->setFinancialMovementsId($financialMovementsId);
        $FinancialMovementsValidate->setPath($path);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);        

        } else {    

            /** Caso o arquivo tenha sido enviado, consulto a movimentação informada */
            $result = $FinancialMovements->Get($FinancialMovementsValidate->getFinancialMovementsId());

            /** Inicio das marcações do arquivo */
            $markings = new \stdClass();    

            /** Descrição do arquivo */
            $markings->descricao = 'Documento movimento financeiro';    

            /** Carrega as marcações do arquivo */
            $markings->codigo_movimentacao_financeira = new \stdClass();
            $markings->codigo_movimentacao_financeira->value = $result->financial_movements_id;

            $markings->data_agendamento = new \stdClass();
            $markings->data_agendamento->value = date('d/m/Y', strtotime($result->movement_date_scheduled));

            /** Tipo movimentação */
            $movementType = 'descricao_'.($result->financial_entries_id > 0 ? 'entrada' : 'saida');

            /** Decrição da movimentação */
            $markings->$movementType = new \stdClass();
            $markings->$movementType->value = $result->description;

            /** Data da movimentaçã0 */
            $markings->data_movimento = new \stdClass();
            $markings->data_movimento->value = $result->movement_date;

            /** Valor da movimentação */
            $markings->valor_movimento = new \stdClass();
            $markings->valor_movimento->value = number_format($result->movement_value, 2, ',', '.');

            /** Tipo da movimentação */
            $markings->tipo = new \stdClass();
            $markings->tipo->value = ($result->financial_entries_id > 0 ? 'Entrada' : 'Saída');

            /** Nome da empresa  */
            $markings->empresa_nome = new \stdClass();
            $markings->empresa_nome->value = $result->company_name;

            /** Documento empresa */
            $markings->empresa_cpf_ou_cnpj = new \stdClass();
            $markings->empresa_cpf_ou_cnpj->value = $Main->formatarCPF_CNPJ($result->document);     

            /** Verifica se a movimentação foi localizada */
            if($result->financial_movements_id > 0){

                /** Salva as informações do arquivo */
                if( $Documents->Save(0, 0, $result->description, $FinancialMovementsValidate->getArchive(), $FinancialMovementsValidate->getExt(), (object)$markings, $result->financial_movements_id) ){

                    /** Informa o resultado positivo **/
                    $result = [

                        'cod' => 200,
                        'title' => 'Atenção',
                        'message' => '<div class="alert alert-success" role="alert">Arquivo cadastrado com sucesso!</div>'

                    ];

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;                 
                                

                }else{/** Caso seja bem sucedido, informo */

                    /** Caso ocorra alguma falha informo */
                    throw new InvalidArgumentException("Não foi possível gravar o arquivo ".$description, 0);
                }

            }else{/** Caso a movimentação não tenha sido informada, informo */

                /** Informo */
                throw new InvalidArgumentException("Nenhuma movimentação financeira localizada para esta solicitação", 0);
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