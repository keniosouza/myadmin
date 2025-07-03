<?php

/** Importação de classes */
use vendor\model\CallsPriorities;
use vendor\controller\calls_priorities\CallsPrioritiesValidate;

/** Instânciamento de classes */
$CallsPriorities = new CallsPriorities();
$CallsPrioritiesValidate = new CallsPrioritiesValidate();

try
{

    /** Parâmetros de entrada */
    $CallsPrioritiesValidate->setCallPriorityId(filter_input(INPUT_POST, 'call_priority_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsPrioritiesValidate->setCompanyId($_SESSION['USERSCOMPANYID']);
    $CallsPrioritiesValidate->setDescription(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsPrioritiesValidate->setPriority(filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsPrioritiesValidate->setHistory(base64_encode('teste'));

    /** Verifico a existência de erros */
    if (!empty($CallsPrioritiesValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsPrioritiesValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($CallsPriorities->Save($CallsPrioritiesValidate->getCallPriorityId(), $CallsPrioritiesValidate->getCompanyId(), $CallsPrioritiesValidate->getDescription(), $CallsPrioritiesValidate->getPriority(), $CallsPrioritiesValidate->getHistory()))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Atenção',
                'message' => $CallsPrioritiesValidate->getCallPriorityId() > 0 ? '<b>Prioridade atualizada com sucesso!</b>' : '<b>Nova prioridade cadastra com sucesso</b>',
                'redirect' => ''

            ];

        }
        else
        {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel salvar o registro', 0);

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