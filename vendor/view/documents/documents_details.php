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

        /** Parametros de entrada  */
        $documentsId = isset($_POST['documents_id']) ? (int)filter_input(INPUT_POST,'documents_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Validando os campos de entrada */
        $DocumentsValidate->setDocumentsId($documentsId);

        /** Verifica se não existem erros a serem informados */
        if (!empty($DocumentsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($DocumentsValidate->getErrors(), 0);        

        } else {     

            /** Verifica se o ID informado é válido */
            if($DocumentsValidate->getDocumentsId() > 0){ 

                /** Consulta o documento informado */
                $DocumentsResult = $Documents->Get($documentsId); 
                
                /** Verifica se o arquivo esta visualização */
                if($DocumentsResult->documents_id > 0){
                    
                    /** Carrega os dados do json */
                    $data = json_decode($DocumentsResult->tag, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                    /** Carrega a descrição de cada marcação */
                    $label = array_keys($data);     
                
                ?>

                    <table class="table table-sm table-bordered">

                        <tbody>

                        <tr>
                            <td>Descrição</td>
                            <td><?php echo $data['descricao'];?></td>
                        </tr>

                        <?php

                        for($j=0; $j<count($data); $j++){

                            /** Desconsidera o label descrição */
                            if($label[$j] != 'descricao'){

                                if(!empty( $data[$label[$j]]['value'])){

                        ?>
                            <tr>

                                <td><?php echo $Main->treatMask($label[$j]);?></td>
                                <td><?php echo $data[$label[$j]]['value'];?></td>
                            </tr>

                        <?php } }} ?>

                        </tbody>

                    </table>
                    
                    <?php
                    /** Pego a estrutura do arquivo */
                    $div = ob_get_contents();

                    /** Removo o arquivo incluido */
                    ob_clean();

                    /** Result **/
                    $result = array(

                        'cod' => 201,
                        'data' => $div,
                        'title' => 'Detalhes do documento nº '.$Main->setzeros($DocumentsResult->documents_id, 6)

                    );


                    sleep(1);

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;        

                }else{

                    /** Informo */
                    throw new InvalidArgumentException("<ol><li>Nenhum documento localizado para esta solicitação</li></ol>", 0);

                } 
            }?>

    <?php

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
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}