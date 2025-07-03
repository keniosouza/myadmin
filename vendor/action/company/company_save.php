<?php

/** Importação de classes  */
use vendor\model\Company;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){       

        /** Instânciamento de classes  */
        $Company = new Company();

        /** Parametros de entrada  */
        $companyId      = isset($_POST['company_id']) ? $Main->antiInjection($_POST['company_id']) : '';
        $companyName    = isset($_POST['company_name']) ? $Main->antiInjection($_POST['company_name']) : '';
        $fantasyName    = isset($_POST['fantasy_name']) ? $Main->antiInjection($_POST['fantasy_name']) : '';
        $document       = isset($_POST['document']) ? $Main->ClearDoc($Main->antiInjection($_POST['document'])) : '';
        $zipCode        = isset($_POST['zip_code']) ? $Main->antiInjection($_POST['zip_code']) : '';
        $adress         = isset($_POST['adress']) ? $Main->antiInjection($_POST['adress']) : '';
        $number         = isset($_POST['number']) ? $Main->antiInjection($_POST['number']) : '';
        $complement     = isset($_POST['complement']) ? $Main->antiInjection($_POST['complement']) : '';
        $district       = isset($_POST['district']) ? $Main->antiInjection($_POST['district']) : '';
        $city           = isset($_POST['city_']) ? $Main->antiInjection($_POST['city_']) : '';
        $stateInitials  = isset($_POST['state_initials']) ? $Main->antiInjection($_POST['state_initials']) : '';
        $active         = isset($_POST['active']) ? $Main->antiInjection($_POST['active']) : '';

        /** Controles  */
        $err = 0;
        $msg = "";


        /** Valida as informações do obrigatórias */
            
        /** Verifica se o primeiro nome foi informado */
        if(empty($companyName)){

            $err++;
            $msg .= "<li>Informe a razão social da empresa</li>";

        }

        /** Verifica se o segundo nome foi informado */
        if(empty($fantasyName)){

            $err++;
            $msg .= "<li>Informe o nome fantasia da empresa</li>";
        }  
        
        /** Verifica se CPF / CNPJ foi informado */
        if(empty($document)){

            $err++;
            $msg .= "<li>Informe o CPF/CNPJ da empresa</li>";

        } elseif(!$Main->cpfj($document)) {

            $err++;
            $msg .= "<li>Informe um CPF/CNPJ válido para a empresa</li>";        
        }
        
        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados do usuário ou 
         * efetua o cadastro de um novo*/
        if($err === 0){

            /** Salva as alterações ou cadastra um novo usuário */
            if($Company->Save((int)$companyId, utf8_encode($companyName), utf8_encode($fantasyName), (string)$document, (string)$zipCode, utf8_encode($adress), (string)$number, utf8_encode($complement), utf8_encode($district), utf8_encode($city), (string)$stateInitials, (string)$active)){           

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => ($companyId > 0 ? 'Empresa atualizada com sucesso!' : 'Empresa cadastrada com sucesso!'),

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;            

            }else{//Caso ocorra algum erro, informo

                throw new InvalidArgumentException(($companyId > 0 ? 'Não foi possível atualizar o cadastro da empresa' : 'Não foi possível cadastrar a nova empresa'), 0);	
            }

        }else{/** Caso existam erro(s) informo */

            /** Trata a mensagem de resposta */
            $list = "<ol>" . $msg . "</ol>";

            /** Informo */
            throw new InvalidArgumentException($list, 0);
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