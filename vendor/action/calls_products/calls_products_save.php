<?php

/** Importação de classes */
use vendor\model\CallsProducts;
use vendor\controller\calls_products\CallsProductsValidate;

/** Instânciamento de classes */
$CallsProducts = new CallsProducts();
$CallsProductsValidate = new CallsProductsValidate();

try
{

    /** Percorro todos os registros */
    foreach ($_POST['call_product_id'] as $keyResult => $result)
    {

        /** Parâmetros de entrada */
        $CallsProductsValidate->setCallProductId(@(int)filter_input(INPUT_POST, 'call_product_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $CallsProductsValidate->setCallId(@(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $CallsProductsValidate->setProductId($result);
        $CallsProductsValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);

        /** Defino o histórico do registro */
        $history[0]['title'] = 'Cadastro';
        $history[0]['description'] = 'Novo produto vinculado';
        $history[0]['date'] = date('d-m-Y');
        $history[0]['time'] = date('H:i:s');
        $history[0]['class'] = 'badge-primary';
        $history[0]['user'] = $_SESSION['USERSNAMEFIRST'];

        /** Definição do histórico */
        $CallsProductsValidate->setHistory($history);

        /** Verifico a existência de erros */
        if (!empty($CallsProductsValidate->getErrors()))
        {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException($CallsProductsValidate->getErrors(), 0);

        }
        else
        {

            /** Verifico se o usuário foi localizado */
            if ($CallsProducts->Save($CallsProductsValidate->getCallProductId(), $CallsProductsValidate->getCallId(), $CallsProductsValidate->getProductId(), $CallsProductsValidate->getCompanyId(), json_encode($CallsProductsValidate->getHistory(), JSON_PRETTY_PRINT)))
            {

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Sucesso',
                    'message' => 'Produto vinculado com sucesso',
                    'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=' . $CallsProductsValidate->getCallId()

                ];

            }
            else
            {

                /** Retorno mensagem de erro */
                throw new InvalidArgumentException('Não foi possivel salvar o registro', 0);

            }

        }

    }

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;

}
catch (Exception $exception)
{

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => '<div class="alert alert-danger" role="alert">' . $exception->getMessage() . '</div>',
        'title' => 'Atenção',
        'type' => 'exception',

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;

}