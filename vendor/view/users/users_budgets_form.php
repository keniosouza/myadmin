<?php

/** Importação de classes  */
use vendor\model\Users;
use vendor\model\ClientBudgets;
use vendor\model\ClientBudgetsCommissions;
use vendor\controller\client_budgets\ClientBudgetsValidate;

error_reporting(E_ALL);
ini_set('display_errors','On');

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $Users = new Users();
        $ClientBudgets = new ClientBudgets();        
        $ClientBudgetsValidate = new ClientBudgetsValidate();
        $ClientBudgetsCommissions = new ClientBudgetsCommissions();

        /** Parametros de entrada  */
        $clientsBudgetsId = isset($_POST['clients_budgets_id']) ? (int)filter_input(INPUT_POST,'clients_budgets_id', FILTER_SANITIZE_NUMBER_INT) : 0;
        $companyId        = isset($_SESSION['USERSCOMPANYID'])  ? (int)$_SESSION['USERSCOMPANYID']                                               : 0;

        /** Validando os campos de entrada */
        $ClientBudgetsValidate->setClientsBudgetsId($clientsBudgetsId);

        /** Verifico a existência de erros */
        if (!empty($ClientBudgetsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientBudgetsValidate->getErrors(), 0);        

        } else {

            /** Carrega o orçamento informado */
            $ClientBudgetsResult = $ClientBudgets->Get($ClientBudgetsValidate->getClientsBudgetsId());

        ?>

            <form class="w-100" id="frmBudgetsCommissions" autocomplete="off"> 

                <div class="form-group row">

                    <div class="col-sm-12 ">

                        <?php

                            echo $ClientBudgetsResult->description;
                            echo '<br/>';
                            echo $ClientBudgetsResult->fantasy_name;

                        ?>
                        <hr/>
                        <label for="users_id">Colaborador:</label>
                        <select class="form-control form-control" id="users_id " name="users_id">
                            <option value="" selected>Selecione</option>

                            <?php 
                                $UsersResult = $Users->All(0 ,0, $companyId, null);
                                foreach($UsersResult as $UsersKey => $Result){ 
                            ?>
                            <option value="<?php echo $Result->users_id;?>"><?php echo $Main->decryptData($Result->name_first);?> <?php echo $Main->decryptData($Result->name_last);?></option>

                            <?php } ?>
                        </select>  
                    </div>             

                </div>                        

                <div class="form-group">

                    <div class="col-sm-12 ">
                        <label for="parcel">Frequência:</label>
                        <div class="table-mini-container" style="height: 180px;">
                                                       
                            <div class="row">
                                <?php 
                                    for($i=1; $i<=$ClientBudgetsResult->often; $i++){
                                    
                                ?>
                                    <div class="col-sm-4">
                                        <table class="table-mini"> 
                                            <tr>
                                                <td align="center" width="20"><?php echo $i;?></td>
                                                <td width="60"><input type="text" class="form-control form-control price" name="parcel[]"/></td>
                                                <td align="center" width="20">%</td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>  
                    </div> 
                                    
                </div> 

                <div class="col-sm-12 text-center" id="sendCommission"></div>                

                <input type="hidden" name="clients_budgets_id" value="<?php echo $ClientBudgetsResult->client_budgets_id;?>"/>
                <input type="hidden" name="clients_id" value="<?php echo $ClientBudgetsResult->clients_id;?>"/>
                <input type="hidden" name="TABLE" value="clients_budgets"/>
                <input type="hidden" name="ACTION" value="clients_budgets_commissions_save"/>
                <input type="hidden" name="FOLDER" value="action" />

            </form>


            <script type="text/javascript">

                /** Carrega as mascaras dos campos inputs */
                $(document).ready(function(e) {

                    /** inputs mask */
                    loadMask();  

                });

            </script>         

            <?php
            /** Pego a estrutura do arquivo */
            $div = ob_get_contents();

            /** Removo o arquivo incluido */
            ob_clean();

            /** Result **/
            $result = array(

                'cod' => 201,
                'data' => $div,
                'title' => 'Agendar comissão', 
                'func' => 'sendForm(\'#frmBudgetsCommissions\', \'\', true, \'\', 0, \'#sendCommission\', \'Atualizando comissão\', \'random\', \'circle\', \'sm\', true)'
                        
            );  

            /** Envio **/
            echo json_encode($result);

            /** Paro o procedimento **/
            exit;  
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
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}