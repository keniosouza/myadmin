<?php

/** Importação de classes  */
use vendor\model\UsersAcl;

/** Instânciamento de classes  */
$UsersAcl = new UsersAcl();

/** Parametros de entrada  */
$usersAclId  = isset($_POST['users_acl_id']) ? $Main->antiInjection($_POST['users_acl_id']) : 0;
$description = isset($_POST['description'])  ? $Main->antiInjection($_POST['description'])  : '';

/** Controles  */
$err = 0;
$msg = "";
$list = "";

try{

    /** Valida as informações do usuário */
        
    /** Verifica se o primeiro nome foi informado */
    if(empty($description)){

        $err++;
        $msg .= "<li>Informe a descrição do novo controle de acessos</li>";

    }

    
    /** Verifica se não existem erros a serem informados, 
     * caso não haja erro(s) salvo os dados informados ou 
     * efetua o cadastro de um novo*/
    if($err === 0){

        /** Salva as alterações ou cadastra um novo usuário */
        if($UsersAcl->Save((int)$usersAclId, utf8_encode($description))){           

            /** Informa o resultado positivo **/
            $result = [

                'cod' => 200,
                'title' => 'Atenção',
                'message' => ($usersAclId > 0 ? 'Controle de acesso atualizado com sucesso!' : 'Controle de acesso cadastrado com sucesso!'),

            ];

            /** Envio **/
            echo json_encode($result);

            /** Paro o procedimento **/
            exit;            

        }else{//Caso ocorra algum erro, informo

            throw new InvalidArgumentException(($usersAclId > 0 ? 'Não foi possível atualizar o controle de acesso' : 'Não foi possível cadastrar o controle de acesso'), 0);	
        }

    }else{/** Caso existam erro(s) informo */

        /** Trata a mensagem de resposta */
        $list = "<ol>" . $msg . "</ol>";

        /** Informo */
        throw new InvalidArgumentException($list, 0);
    }


}catch(Exception $exception){

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => $exception->getMessage(),
        'title' => 'Atenção',
        'type' => 'exception',

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}