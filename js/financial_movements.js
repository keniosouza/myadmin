
/** Função que dispara um evento ao clicar em uma linha da tabela */
function manageMovement() {
    var table = document.getElementById("tableFinancialMovements");
    var rows = table.getElementsByTagName("tr");
    for (i = 0; i < rows.length; i++) {
        var currentRow = table.rows[i];
        var createClickHandler = 
            function(row) 
            {
                return function() { 
                    
                    /** Recupera o ID da celula */
                    var cell = row.getElementsByTagName("td")[0];
                    var id = parseInt(cell.innerHTML);

                    /** Verifica se o ID foi devidamento carregado e envia a requisição*/
                    if(id > 0){

                        /** Prepara a queryString da requisição */
                        var QS = 'FOLDER=view&ACTION=financial_movements_manage&TABLE=financial_movements&financial_movements_id='+id;

                        /** Efetua a requisição */
                        //manageMovementSend(QS);


                        /** Envia a solicitação */
                        $.ajax({

                            /** Dados para envio */
                            url : 'router.php',
                            type : 'post',
                            dataType : 'json',
                            data : QS,

                            /** Antes de enviar */
                            beforeSend : function () {   
                                
                                

                            },        

                            /** Caso tenha sucesso */
                            success: function(response) {          

                                /** Legenda(s) 
                                 *
                                 * Code 202 Accepted
                                 * Code 99 Logout
                                 * Code 98 Open Document
                                 * Code 97 Download Document
                                 * Code 200 OK
                                 * Code 201 created/popup/form
                                 * 
                                 * */  
                                
                                /** Cancela o block page */
                                blockPage(false);                


                                switch (parseInt(response.cod)) {

                                    case 0:             

                                        /** Informa o erro */
                                        modalPage(true, 0, 0, 'Atenção', response.message, '', 'alert', '', true);                

                                        break;

                                    case 200:
                                        

                                        break;

                                    case 201:              
                                        
                                        
                                        
                                        break;  
                                    

                                    default:

                                        /** Abro um popup com os dados **/                    
                                        modalPage(true, 0, 0, 'Atenção', 'Falha na solicitação', '', 'alert', '', true);
                                        break;

                                }
                            

                            },

                            /** Caso tenha falha */
                            error: function (xhr, ajaxOptions, thrownError) {


                                /** Cancela o block page */
                                blockPage(false);                       

                                /** Controle de mensagens */
                                let messages = Array();

                                /** Adiciono um elemtno a array */
                                messages.push(['erro', xhr.status + ' - ' + ajaxOptions + ' - ' + thrownError]);

                                /** Abro um popup com os dados **/                
                                modalPage(true, 0, 0, 'Atenção', messages, '', 'alert', '', true); 

                            }

                        }); 

                        
                    }

                };
            };

        currentRow.ondblclick = createClickHandler(currentRow);
    }
}
window.onload = manageMovement();