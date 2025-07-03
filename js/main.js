/**Pega a url atual**/
let appUrl = null;
let appTitle = null;
let appVersion = null;
let appRelease = null;
let appSessionTime = null;

/** Carrego o arquivo de configurações */
$.getJSON("config/config.json", function(data) {

    /** Carrega as configurações da aplicação */
    appUrl = data['app']['url_aplication'];
    appTitle = data['app']['title'];
    appVersion = data['app']['version'];  
    appRelease = data['app']['release'];   
    appSessionTime = data['app']['session_time']; 
    
    /** Carrega o tempo de sessão do usuário */


    /** Aplico o title da aplicação */
    $('#appTitle').html(appTitle+' :: v'+appVersion+'.'+appRelease);
    $('#appTitleTop').html(appTitle);
    $('#appVersionTop').html(appVersion+'.'+appRelease);

});

let ckeditor = null;

function loadCKEditor() {

    /** Listo todos os editores de texto */
    $('.editor').each(function () {

        /** Pego o nome do campo */
        let id = $(this).attr('id');

        DecoupledDocumentEditor
            .create(document.querySelector('#' + id), {

                licenseKey: '',

            })
            .then(editor => {
                ckeditor = editor;
                window.editor = editor;
                // Set a custom container for the toolbar.
                document.querySelector('#' + id + '_toolbar').appendChild(editor.ui.view.toolbar.element);
                document.querySelector('.ck-toolbar').classList.add('ck-reset_all');
            })

    })

}

/** Verifica a sessão do usuário */
function userActive(sessionTime){

    setTimeout(function() {


        $.ajax({

            url : "router.php",
            type : "post",
            dataType : "json",
            data: 'TABLE=users&FOLDER=action&ACTION=users_downtime&kenio',

            /** Antes de enviar */
            beforeSend : function () {},

            /** Caso tenha sucesso */
            success: function (response)
            {
                

                switch (response.cod)
                {
    
                    /** Verifica se é para ativar o screensaver*/
                    case 0:

                        /** Ativa o bloqueio de tela */
                        screensaver();                    
                        break;  

                    /** Verifica se é para verificar o tempo de atividade*/
                    case 200:

                        /** Ativa uma nova verificação */
                        userActive(response.sessionTime);                                            
                        break;  
                        
                    default :

                        /** Ativa uma nova verificação */
                        userActive(response.sessionTime);                   

                        /** Abro um popup com os dados **/
                        modalPage(true, 0, 0,  xhr.status + ' - ' + ajaxOptions, thrownError, '', 'alert', '', true);                    
                        break;
                                            
                }
                
            },

            /** Caso tenha falha */
            error: function (xhr, ajaxOptions, thrownError) { 
                
                /** Ativa uma nova verificação */
                userActive(sessionTime);                  
            
                /** Abro um popup com os dados **/
                modalPage(true, 0, 0,  xhr.status + ' - ' + ajaxOptions, thrownError, '', 'alert', '', true);

            },

            /** Ao completar a requisição */
            complete: function () {}

        });   
        
    }, (sessionTime*60000));//Converte o tempo em minutos para milésimos de segundos
}

/** Envio uma requisição para o backend */
function sendForm(form, editor, create, info, sec, target, message, color, type, size, message_spinner, target_form) {

    $.ajax({

        url : "router.php",
        type : "post",
        dataType : "json",
        data: editor === 'S' ? $(form).serialize() + '&' + $('.editor').attr('id') + '=' + encodeURIComponent(ckeditor.getData()) : $(form).serialize(),

        /** Antes de enviar */
        beforeSend : function () {

            /** Verifica se será bloqueado a tela */
            if(create === true){

                blockPage(create, info, sec, target, message, color, type, size, message_spinner);

            /** Caso o target do formulário tenha sido informado */
            }else if(target_form){

                blockPage(true, info, sec, target_form, message, color, type, size, true);
            }

        },

        /** Caso tenha sucesso */
        success: function (response)
        {
            
            /** Legenda(s) 
             *
             * Code 0 Error
             * Code 202 Accepted
             * Code 99 Logout
             * Code 98 Open Document             
             * Code 96 Authenticating to the screensaver  
             * Code 301 Redireciona para a tela de atualizar a senha
             * Code 200 OK
             * Code 210 arquivo enviado com sucesso
             * 
             * */

            /** Caso seja consulta de documentos, limpo a informação de consulta em andamento */
            $('#loadSearchInfo').html('');
             
            /** Informa a mensagem antes de qualquer ação */
            if(target == '#loadSearchInfo'){

                $(target).html(response.message);
            }

            /** Verifica se o target do formulário foi informado para habilitar o loader no mesmo */
            if(!target_form){

                $(target).html(response.message);
            }

            switch (response.cod)
            {

                /** Verifico se a autenticação foi bem sucedida*/
                case 202:

                    /** Verifica se é um primeiro acesso */
                    if(response.firstAccess == 'S'){

                        location.href = appUrl + 'first-access';
                        break;

                    }else{

                        /** Informa redirecionamento */
                        blockPage(true, response.message, 0, target, '', '', '', '', '');
                        
                        location.href = appUrl + response.url;
                        break;

                    }

                /** Verifica se é logout */
                case 99:


                    /** Redireciono a página */
                    location.href = appUrl;
                    break;

                /** Verifica se o arquivo foi enviado com sucesso */
                case 210:

                        /** Limpa o arquivo enviado com sucesso */
                        $('#results').html('');

                        /** Remove o botão de cadastro */
                        $('#btn-upload').html('');

                        /** Abro um popup com a mensagem **/   
                        modalPage(true, 0, 0, response.title, response.message, '', 'success', '', true);

                    break;

                /** Verifica se a solicitação foi bem sucedida */
                case 200:
                    
                    /** Remove arquivos que já foram cadastrados */
                    $('.send-files').remove();
                
                    /** Verifica se existem item a ser desabilitado */
                    if(response.disabled){

                        $(response.disabled).prop('disabled', true);
                    }

                    /** Verifica se o target foi informado */
                    if( (target) || (target_form) ){

                        /** Verifica se é uma mensagem */
                        if(response.message){

                            /** Carrego o conteúdo **/
                            $( target != '' ? target : target_form ).html(response.message); 

                        }else{

                            /** Carrego o conteúdo **/
                            $( target != '' ? target : target_form ).html(response.data); 

                        }

                        /** Verifica se existem procedimentos a serem executados */
                        if(response.procedure){

                            $('body').append(response.procedure);

                        }                           

                    }else if(response.redirect){/** Verifica se é para efetuar um redirecionamento */

                        /** Redireciono a página */
                        request(response.redirect, '#loadContent', true, '', 0, '', 'Carregando usuários cadastrados', 'random', 'circle', 'sm', true);
                        
                    }else{/** Caso não exista redirecionamento a serem executados, informo */                          

                        /** Abro um popup com a mensagem **/   
                        modalPage(true, 0, 0, response.title, response.message, '', 'success', '', true);

                        /** Verifica se existem procedimentos a serem executados */
                        if(response.procedure){

                            $('body').append(response.procedure);

                        }                        
                    }

                    break;

                /** Verifica se é um erro e o mesmo precisa ser informado por cima da tela */
                case 500:                  

                    /** Redireciono a página */
                    modalPage(true, 0, 0, response.title, response.message, '', 'warning', '', true);                     
                    break;                    

                /** Verifica se foi uma autenticação no bloqueador de tela */
                case 96:  
                
                    /** Inicializa o tempo para verificação de sessão */
                    userActive(response.sessionTime);                

                    /** Libera a página para o usuário utilizar */
                    $('div').remove('#screensaver');
                    modalPage(false);
                    break;                    

                /** Verifica se é visualização de documento */
                case 98:                  

                    /** Redireciono a página */
                    modalDocument('Etiqueta', response.path, response.pedido_id);                        
                    break;

                /** Verifica se atualização de senha permanente */
                case 301:

                    /** Redireciono a página */
                    location.href = appUrl + response.url;
                    break;               

                /** Verifica se é apenas uma mensagem popup */
                default:   
                
                    
                    /** Verifica se o target não foi informado */
                    if( (!target) && (!target_form) ){                       

                        /** Abro um popup com os dados **/   
                        modalPage(true, 0, 0, response.title, response.message, '', 'alert', '', true); 
                        break;

                    }else{/** Caso o target tenha sido informado */                      

                        $(target ? target : target_form).html(response.message);
                    }

                    break;

            }

            loadMask(); //Habilita a visualização de mascaras
     
        },

        /** Caso tenha falha */
        error: function (xhr, ajaxOptions, thrownError) {
         
            /** Caso seja consulta de documentos, limpo a informação de consulta em andamento */
            $('#loadSearchInfo').html('');

            /** Abro um popup com os dados **/
            modalPage(true, 0, 0,  xhr.status + ' - ' + ajaxOptions, thrownError, '', 'alert', '', true);

        },

        /** Ao completar a requisição, cancela o Block Page */
        complete: function () {

            /** Cancela o block page */
            blockPage(false);

        }

    });

}

/** Geral - Requisições */
function request(QS, target_data, create, info, sec, target, message, color, type, size, message_spinner) {       

    /** Fecha possiveis tooltip aberto */
    $('[data-toggle="tooltip"]').tooltip('hide');

    /** Envia a solicitação */
    $.ajax({

        /** Dados para envio */
        url : 'router.php',
        type : 'post',
        dataType : 'json',
        data : QS,

        /** Antes de enviar */
        beforeSend : function () {   
            
            /** Fecha a janela modal aberta anteriormente */
            modalPage(false);

            /** Verifica se será bloqueado a tela */
            if(create === true){
                
                /** Bloqueia a tela */
                blockPage(create, info, sec, target, message, color, type, size, message_spinner);
            }

        },        

        /** Caso tenha sucesso */
        success: function(response) {          

            /** Legenda(s) 
             *
             * Code 0 Error
             * Code 202 Accepted
             * Code 99 Logout
             * Code 97 Download Document 
             * Code 98 Open Document
             * Code 96 Authenticating to the screensaver
             * Code 200 OK
             * Code 201 created/popup/form
             * 
             * */  
            
            /** Cancela o block page */
            blockPage(false);

            switch (parseInt(response.cod)) {

                case 0: 
                
                    /** Verifica se o usuário precisa efetuar autenticação junto ao sistema */
                    if(response.authenticate){

                        /** Prepara a requisição */
                        let func = 'document.location.reload(true);'; 
                        
                        /** Informa a nova autenticação */
                        modalPage(true, 0, 0, 'Atenção', response.message, '', 'alert', func, false); 
                    
                    /** Caso não seja uma nova autenticação informo */
                    }else{ 

                        if(target){

                            /** Carrego o conteúdo **/
                            $(target).html(response.message);

                        }else{

                            /** Informa o erro */
                            modalPage(true, 0, 0, 'Atenção', response.message, '', 'alert', '', true);  

                        }
                        
                    }

                    break;

                case 200:
                    
                    /** Verifica se o target foi informado */
                    if(target_data){
                    
                        /** Carrego o conteúdo **/
                        $(target_data).html(response.data);

                    }else if(target){

                        /** Verifica se a janela modalBox esta ativa */
                        if($('.modal-box').length){

                            /** Carrego o conteúdo **/
                            $(target).html(response.data); 

                        }else if(response.data){ 

                            /** Carrego o conteúdo **/
                            $(target).html(response.data);             

                        }else{

                            /** Informa o erro */
                            modalPage(true, 0, 0, response.title, response.message, '', 'success', '', true);                
                        }

                    }else if(response.redirect){/** Verifica se é para efetuar um redirecionamento */

                        /** Redireciono a página */
                        request(response.redirect, '#loadContent', true, '', 0, '', 'Carregando informações', 'random', 'circle', 'sm', true);

                    }else{

                        /** Informa o erro */
                        modalPage(true, 0, 0, response.title, response.message, '', (response.type != '' ? 'success' : ''), '', true); 
                        
                        /** Verifica se existem procedimentos a serem executados */
                        if(response.procedure){

                            $('body').append(response.procedure);

                        }

                    }

                    break;

                case 201:              
                    
                    /** Verifica se é para visualizar arquivo */
                    if(response.file){

                        //Pego a configuração do monitor	
                        let largura = (screen.width)-30;
                        let altura  = ((screen.height)-250);
                        let margin  = (largura/2)-20;

                        /** Carrego o conteúdo **/
                        modalPage(true, largura, 0, response.title, '<iframe width="'+(largura-40)+'px" height="'+(altura-100)+'px"  src="'+response.file+'" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>', '', '', '', true);

                        /** Redimensiona o popup */
                        $('.modal-box').css({

                            'top' : '10px',
                            'margin-left' : '-'+margin+'px',
                        })
                       

                    }else{
                    
                        /** Carrego o conteúdo **/
                        modalPage(true, (response.width > 0 ? response.width : 0), 0, response.title, response.data, '', '', response.func, (response.func ? false : true), (response.height > 0 ? response.height : 0));

                    }
                    
                    break; 


                /** Verifica se é logout */
                case 99:

                        location.href = appUrl+response.url;

                    break;

                /** Verifica se é um download de arquivo */
                case 97:

                        /** Carrega o arquivo a partir da pasta temporária e efetuo o download do mesmo */
                        fetch(response.file).then( async (result) =>{

                            const blob = await result.blob();// recuperandoo um blob para baixar
                            const anchor = window.document.createElement('a');
                            
                            anchor.href = window.URL.createObjectURL(blob);
                            anchor.download = response.nameFile;
                            anchor.click();
                            window.URL.revokeObjectURL(anchor.href);

                        });                            
                
                    break;
                
                /** Verifica se é uma visualização de arquivo */
                case 98:              
                    
                    /** Verifica se é para visualizar arquivo */
                    if(response.file){

                        //Pego a configuração do monitor	
                        let largura    = (screen.width)-40;
                        let altura     = ((screen.height)-250);
                        let marginLeft = (largura/2)-25;
                        let marginTop  = (altura/2);

                        /** Carrego o conteúdo **/
                        modalPage(true, largura, 0, response.title, '<iframe width="'+(largura-40)+'px" height="'+(altura-100)+'px"  src="'+response.file+'" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>', '', '', '', true);

                        /** Redimensiona o popup */
                        $('.modal-box').css({

                            'margin-top' : '-'+marginTop+'px',
                            'margin-left' : '-'+marginLeft+'px'
                        })
                       

                    }else{
                    
                        /** Carrego o conteúdo **/
                        modalPage(true, (response.width > 0 ? response.width : 0), 0, response.title, response.data, '', '', response.func, (response.func ? false : true), (response.height > 0 ? response.height : 0));

                    }
                    
                    break;                     

                /** Verifica se é download de arquivo */
                case 97:

                        /** Envia o arquivo para download */
                        $('body').append('<iframe width="0px" height="0px"  src="'+response.zipfile+'" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>');

                        /** Cancela o block page */
                        blockPage(false);                          

                    break;                    

                default:

                    /** Abro um popup com os dados **/                    
                    modalPage(true, 0, 0, 'Atenção', 'Não foi possível carregar o arquivo solicitado', '', 'alert', '', true);
                    break;

            }

            /** habilita tooltips */
            $('[data-toggle="tooltip"]').tooltip(); 
            loadMask();           
        
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

/** Janela popup para editar registro */
function openPopupEdit(data){

    /** Carrega o modal na página */
    $('body').append(data);

    /** Abro o popup **/
    $('#defaultModal').modal('show');     

}

/** Carrega todos os ckeckboxes selecionados */
function inputTableCheckLoad(table, qs){

    var input = [];

    //Procura pelos itens selecionados
    $(table+' input[type="checkbox"]:checked').each(function() {

        //Carrega os itens selecionados
        input.push($(this).val());  
    });

    /** Verifica se algum item foi selecionado */
    if(input.length > 0){
    
        /** Envia a requisição */    
        request(qs+'&librarys='+input, '', '', 'S');

    }else{

        /** Fecha a popup anteriormente aberta */
        closePopup();

        /** Informa */
        openPopup('Atenção', 'Selecione pelo menos um item para esta solicitação.', 'alert');
    }

}

/** Carrega o ckeckbox selecionado */
function getCheckLoad(checkBox, qs){

    /** Verifica se algum item foi selecionado */
    if(checkBox){
    
        /** Envia a requisição */    
        request(qs+'&librarys='+$(checkBox).val(), '', '', 'S');

    }else{

        /** Fecha a popup anteriormente aberta */
        closePopup();

        /** Informa */
        openPopup('Atenção', 'Selecione pelo menos um item para esta solicitação.', 'alert');
    }

}

/** Selecionar Checkbox */
function inputTableCheck(){

    $(".inputCheck").each(function() {
            
        $(this).prop("checked", true);

    });   
}

/** Selecionar Checkbox */
function inputTableUnCheck(){

    $(".inputCheck").each(function() {
            
        $(this).prop("checked", false);

    });   
}

/** Carrega os controles da tabela */
function controllTable(idTable){

    $(idTable).DataTable({

        language: {
            decimal:       ",",
            processing:    "Processamento em andamento...",
            search:        "Pesquisar:",
            lengthMenu:    "Mostrar _MENU_ registros",
            info:           "Exibir registros _START_ &agrave; _END_ de _TOTAL_ registros",
            infoEmpty:      "Exibição de item 0 &agrave; 0 de 0 registros",
            infoFiltered:   "(filtrado de _MAX_ registros no total)",
            infoPostFix:    "",
            loadingRecords: "Carregando...",
            zeroRecords:    "Nenhum registro para visualizar",
            emptyTable:     "Sem dados disponíveis na tabela",
            paginate: {
                first:      "Primeiro",
                previous:   "Anterior",
                next:       "Próximo",
                last:       "Último"
            },
            aria: {
                sortAscending:  ": ativo para classificar a coluna em ordem crescente",
                sortDescending: ": ativo para classificar a coluna em ordem decrescente"
            }
        },

        columnDefs: [
            /*{
                targets: [ 0 ],
                orderData: [ 0, 1 ]
            }, */
            {
                targets: [ 1 ],
                orderData: [ 1, 0 ]
            },
        ],

        lengthMenu : [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]]        

    });

}

/** Selecione os itens de uma tabela  */
function loadParameters(table, target, qs){

    var input = [];
    //$(target).html('');

    //Procura pelos itens selecionados
    $(table+' input[type="checkbox"]:checked').each(function() {

        if(parseInt($(this).val()) > 0){

            //Carrega os itens selecionados
            input.push($(this).val());  

        }
    });

    /** Verifica se algum item foi selecionado */
    if(input.length > 0){
    
        /** Envia a requisição */            
        request(qs+'&parameters='+input, target, true, '', 0, '', ' Aguarde... ', 'random', 'circle', 'sm', true);

    }else{

        modalPage(true, 0, 0,   'Atenção', '<b>Selecione pelo menos um item</b>', '', 'alert', '', true);
    }

}

/** Seleciona todos os itens - checkbox */
function checkAll(obj, main){

    /** Verifica se o principal já está marcado */
    if ($(main).prop("checked")) {

        //Procura pelos itens selecionados
        $(obj+' input[type="checkbox"]').each(function() {
        
            $(this).prop("checked", true);     
        });

    } else {

        //Procura pelos itens selecionados
        $(obj+' input[type="checkbox"]').each(function() {
        
            $(this).prop("checked", false);
        });        
    }
}

/** Remove item desnecessário */
function remove(id){

    /** Remove o item da tela */
    $(id).remove();

    /** Remove o tooltip do item */
    $('[data-toggle="tooltip"]').tooltip('hide');
}

/**Adiciona item de parâmetro */
function add(table, field){

    var inputs = field.split(',');
    var id = getRandomIntInclusive(100, 1000);

    var tr = '';
        tr += '<tr id="parameter_'+id+'">';
        tr += ' <td><input type="text" name="parameter_name[]" class="form-control form-control-user" /></td>';
        tr += ' <td width="160" align="center">';
        tr += '     <select name="parameter_type[]" class="form-control form-control-user">';
        tr += '         <option value="INTEGER">INTEGER</option>';
        tr += '         <option value="BIGINT">BIGINT</option>';
        tr += '         <option value="SMALLINT">SMALLINT</option>';
        tr += '         <option value="FLOAT">FLOAT</option>';
        tr += '         <option value="DOUBLE">DOUBLE</option>';
        tr += '         <option value="NUMERIC">NUMERIC</option>';
        tr += '         <option value="DECIMAL">DECIMAL</option>';
        tr += '         <option value="DATE">DATE</option>';
        tr += '         <option value="TIME">TIME</option>';
        tr += '         <option value="TIMESTAMP">TIMESTAMP</option>';
        tr += '         <option value="CHAR">CHAR</option>';
        tr += '         <option value="VARCHAR">VARCHAR</option>';
        tr += '         <option value="BLOB">BLOB</option>';
        tr += '     </select>';
        tr += ' </td>';
        tr += ' <td width="160" align="center">';
        tr += '     <select name="parameter_field[]" class="form-control form-control-user">';
        tr += '         <option value="" selected=true>Selecione</option>';

        for(var i=0; i<inputs.length; i++){

            /** Verifica se o campo não esta vazio */
            if( !inputs[i].isEmpty ){

                tr += '         <option value="'+inputs[i]+'">'+inputs[i]+'</option>';

            }
        }
        
        tr += '     </select>';
        tr += ' </td>';  
        tr += ' <td width="140" align="center">';
        tr += '     <select name="parameter_condition[]" class="form-control form-control-user">';
        tr += '         <option value="0">Selecione</option>';
        tr += '         <option value="1">1 - Where</option>';

        for(var i=2; i<11; i++){

            tr += '         <option value="'+i+'">'+i+' - And</option>';

        }
        
        tr += '     </select>';
        tr += ' </td>';  
        tr += ' <td width="90" align="center">';
        tr += '     <select name="parameter_check[]" class="form-control form-control-user">';
        tr += '         <option value="0" selected=true>Não</option>';
        tr += '         <option value="1">Sim</option>';
        tr += '     </select>'; 
        tr += ' </td>';           
        tr += ' <td width="30"><a href="#" class="btn btn-danger" onclick="remove(\'#parameter_'+id+'\')"><i class="far fa-trash-alt"></i></a></td>';
        tr += '</tr>';

    $(table).append(tr);
}

function getRandomIntInclusive(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

/** Aplica as mascaras nos campos necessários */
function loadMask() {
    
    $('.date').mask('00/00/0000');
    $('.hour').mask('00:00');
    $('.cep').mask('00000-000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.cel_with_ddd').mask('(00) 00000-0000');
    $('.cnpj').mask('00.000.000/0000-00');
    $('.cpf').mask('000.000.000-00');
    $('.postal_code').mask('00000-000');
    $(".number").keypress(checkNumber);
    $('.price').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.',
        //limit: 12,
        centsLimit: 2
    }); 
    $('.percentage').priceFormat({
        prefix: '',
        centsSeparator: ',',
        thousandsSeparator: '.',
        //limit: 12,
        centsLimit: 4
    });          
}

/** Retorna um float arredondado */
function financial(x) {
    return Number.parseFloat(x).toFixed(2);
  }

/** Formata valores para real */
function convertToReal(number, options = {}) {
    const { moneySign = true } = options;

    if(Number.isNaN(number) || !number) return "need a number as the first parameter";

    if(typeof number === "string") { // n1
        number = Number(number);
    }

    let res;

    const config = moneySign ? {style: 'currency', currency: 'BRL'} : {minimumFractionDigits: 2};

    moneySign
    ? res = number.toLocaleString('pt-BR', config)
    : res = number.toLocaleString('pt-BR', config)

    const needComma = number => number <= 1000;
    if(needComma(number)) {
        res = res.toString().replace(".", ",");
    }

    return res; // n2
}  

/** Somente números */
function checkNumber(e) 
{
 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
    return false;
  }
}

/** Função de Upload */
function uploadFiles(folder, table, action, documents_categorys_id, indexing, financial_movements_id, clients_id) {

    $('#selectFiles').filestyle({
        buttonName : 'btn-info',
        buttonText : 'Clique aqui e selecione arquivo para envio'
    }); 
    
    /** Limpa a informação do arquivo na tela */
    $("#results").html('');    

    if (window.File && window.FileList && window.FileReader) {

        $.ajaxSetup({ cache: false });
		
		$('.upload').on('change', function(){
			
            var files = $("#selectFiles").prop("files");

            //console.log(files);

            /** Bloqueia a tela e informa o envio */
            blockPage(true, '', 0, '', 'Existem ' + files.length + ' arquivo(s) a serem enviandos', 'blue', 'circle', 'sm', true); 

            /** Pega a quantidade de itens a serem enviados */
            let totalFiles = files.length;
            
            /** Contador de arquivos enviados */
            let sendFile = "";

            /** Armazena os itens enviados para exclusão */
            let nameFile = [];

            /** Armazena os caminho dos itens enviados para exclusão */
            let pathFile = [];            

            /** Armazena os itens enviados com erro */
            let nameFileError = [];

            /** Armazena erros aleatorios */
            let error = [];

            /** Tags a serem informadas */
            let badge = "";

            /** Id temporário */
            let id = "";

            /** Formulários a serem enviados */
            let formSend = [];           

            blockPage(false);

            /** Verifica se existe */

            /**Pega o tamanho do arquivo enviado */
            let fileSize =  Math.ceil(files[0].size/1024);

            /** Tamanho máximo permitido */
            let filemax = 10240;// 5mb

            /** Verifica se o arquivo esta dentro do tamanho permitido */
            if(fileSize <= filemax){

                (function (file) {

                    /** Verifica se o arquivo tem a extensão permitida */
                    if( allowedExtension( extensionFile(file.name) )){

                        var fileReader = new FileReader();
                        fileReader.onload = function (f) {
                            $("![]()", {
                                src: f.target.result,
                                width: 200,
                                height: 200,
                                title: file.name
                            }).appendTo("#preview"); 
                            
                            /** Envia o arquivo */
                            $.ajax({
                                type: "POST",
                                dataType : "json",
                                url: 'router.php',
                                data: {
                                    'file': f.target.result,
                                    'name': file.name,
                                    'FOLDER' : folder,
                                    'TABLE' : table,
                                    'ACTION' : action,
                                    'documents_categorys_id' : documents_categorys_id,
                                    'financial_movements_id' : financial_movements_id,
                                    'clients_id' : clients_id
                                },

                                /** Antes de enviar */
                                beforeSend : function () {   
                                                            
                                    blockPage(false);

                                    /** Informo */
                                    blockPage(true, '', 0, '', 'Carregando arquivo', 'blue', 'circle', 'sm', true);  
                                                                                
                                },  

                                success: function (result) {

                                    /** Contabiliza os envios */
                                    sendFile++;                                            
                                    
                                    switch (parseInt(result.cod)) {

                                        case 0:
                                
                                            /** Armazena os itens enviados mal sucedidos */
                                            nameFileError.push(result.nameFile);                
                        
                                            break;
                        
                                        case 200:
                                        
                                            /** Armazena os itens enviados bem sucedidos */
                                            nameFile.push(result.nameFile); 
                                            pathFile.push(result.path); 

                                            break;

                                        case 201:

                                            /** Verifico o target */
                                            if(result.target){

                                                /** Carrega o retorno no local informado */
                                                $(result.target).html(result.data);

                                                /** Encerra o bloqueio de tela */
                                                blockPage(false);
                                                modalPage(false);
                                            }

                                        break;                                           

                                    }

                                    /** Verifica se todos os arquivos foram enviados */
                                    if(parseInt(sendFile) == parseInt(totalFiles)){

                                        blockPage(false);
                                        
                                        for(j=0; j<nameFile.length; j++){

                                            /** Gera um Id aleatório */
                                            id = getRandomInt(999, 999999)+j;

                                            /** Verifica se é uma indexação de documentos */
                                            if(indexing === 'S'){

                                                badge  = '<div class="col-sm-7 mb-2 d-flex p-3">';                                                                                             
                                                badge += '  <div class="card w-100">';
                                                badge += '      <div class="card-body">';
                                                //badge += '          <h6 class="card-title">'+nameFile[j]+'</h6>';
                                                badge += '          <embed width="100%" class="view-files" name="plugin" src="'+pathFile[j]+'" type="application/pdf">';                                                                                                       
                                                badge += '      </div>';
                                                badge += '      <div class="card-footer text-right">';
                                                badge += '          <a href="#" class="btn btn-secondary btn-sm" onclick=""><i class="fa fa-arrow-down" aria-hidden="true"></i> Download</a>';
                                                badge += '      </div>';
                                                badge += '  </div>';                                                                                                                            
                                                badge += '</div>';
                                                badge += '<div class="col-sm-5 mb-2 bg-light">';
                                                
                                                /** Divisa */
                                                badge += '  <div class="row mb-2 p-3 bg-light"><h5 class="text-dark">Informe as marcações do arquivo</h5></div>'; 

                                                badge += '  <div class="row p-3">';
                                                badge += '      <form class="w-100" id="frmDocuments-'+id+'" autocomplete="off">';  
                                                badge += '          <label for="description">Descrição: <span class="text-danger"> * </span></label>';
                                                badge += '          <input type="text" class="form-control form-control " id="description" name="description" value="" placeholder="Informe uma descrição">';
                                                badge += '          <input type="hidden" name="required[]" value="S">';
                                                badge += '          <input type="hidden" name="path" value="'+pathFile[j]+'" />'; 
                                                badge += '          <input type="hidden" name="documents_categorys_id" value="'+$('#documents_categorys_id').val()+'"/>';



                                                for(i=0; i<result.tags.length; i++){

                                                    /** Define a mascara */
                                                    let mask = '';
                                                    let placeholder = '';

                                                    switch(parseInt(result.formats[i])){

                                                        case 2 : 
                                                            mask = 'number'; 
                                                            placeholder = 'Somente números';                                                                            
                                                        break
                                                        case 3 : 
                                                            mask = 'date'; 
                                                            placeholder = '__/__/____';          
                                                        break
                                                        case 4 : 
                                                            mask = 'price'; 
                                                            placeholder = '0,00';          
                                                        break
                                                        case 5 : 
                                                            mask = 'cpf';
                                                            placeholder = '999.999.999-99';              
                                                        break
                                                        case 6 : 
                                                            mask = 'cnpj';  
                                                            placeholder = '99.999.999/9999-99';         
                                                        break
                                                        case 7 : 
                                                            mask = 'cep';  
                                                            placeholder = '99999-999';          
                                                        break
                                                        case 8 : 
                                                            mask = 'phone_with_ddd'; 
                                                            placeholder = '(99) 9999-9999)';
                                                        break
                                                        case 9 : 
                                                            mask = 'cel_with_ddd';
                                                            placeholder = '(99) 9 9999-9999)';   
                                                        break

                                                    }                                                           
                                                                                                                
                                                    badge += '  <div class="row p-3">';
                                                    badge += '      <label for="tag">'+result.labels[i]+': '+(result.requireds[i] == 'S' ? '<span class="text-danger"> * </span>' : '')+'</label>';
                                                    badge += '      <input type="text" class="form-control form-control '+mask+'" id="tag[]" name="tag[]" value="" placeholder="'+placeholder+'">';
                                                    badge += '      <input type="hidden" name="mask[]" value="'+result.tags[i]+'">';
                                                    badge += '      <input type="hidden" name="required[]" value="'+result.requireds[i]+'">';
                                                    badge += '      <input type="hidden" name="format[]" value="'+result.formats[i]+'">';
                                                    badge += '  </div>';

                                                }


                                                badge += '      <input type="hidden" name="TABLE" value="documents" />';
                                                badge += '      <input type="hidden" name="ACTION" value="documents_save" />';
                                                badge += '      <input type="hidden" name="FOLDER" value="action" />';
                                                badge += '      <input type="hidden" name="clients_id" value="'+result.clients_id+'" />';                                                    

                                                /** Armazena os formulários de envios */
                                                formSend.push('#'+'frmDocuments-'+id);                                                        

                                                badge += '      </form>';
                                                badge += '  </div>';
                                                badge += '</div>';                                                         
                                                badge += '</div>'; 

                                                /** Habilita o botão de envio */
                                                $('#btn-upload').html('<label for="btn-save"></label><button class="btn btn-primary btn-user btn-block" id="btn-save" onclick="submitForms(\''+formSend+'\')"><i class="far fa-save"></i> Cadastrar novo documento</button>');


                                            }else if(parseInt(result.financial_movements_id) > 0){/** Caso seja um documento do financeiro */
                                            
                                                /** Monta a informação do arquivo */
                                                badge  = '<span class="badge badge-light text-wrap send-files" style="padding:3px 10px; margin:2px;" id="'+id+'" >'+nameFile[j]+' &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Remover arquivo" onclick="removeItem(\'#'+id+'\')"><i class="far fa-trash-alt"></i> Remover</a> <a href="#" class="btn btn-primary btn-sm" onclick="confirmFile(\'action\', \'financial_movements\', \'financial_movements_save_file\', \''+result.financial_movements_id+'\', \''+pathFile[j]+'\', \'#results\')">Confirmar</a></span>';                                                    

                                            }else{/** Caso não seja uma indexação de arquivos, apenas carrego o nome do mesmo na tela */

                                                /** Monta a informação do arquivo */
                                                badge  = '<span class="badge badge-success text-wrap send-files" style="padding:3px 10px; margin:2px;" id="'+id+'" >'+nameFile[j]+' &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-danger btn-sm rounded-circle" data-toggle="tooltip" data-placement="top" title="Remover arquivo" onclick="removeItem(\'#'+id+'\')"><i class="fas fa-times"></i></a><input type="hidden" name="name_files" value="'+nameFile[j]+'" /></span>';

                                            }

                                            /** Carrega a informação do arquivo na tela */
                                            $("#results").html(badge);

                                            /** tooltips */
                                            $('[data-toggle="tooltip"]').tooltip(); 
                                            
                                            /** Masks */
                                            loadMask();                                                      

                                        }                                                 

                                        for(k=0; k<nameFileError.length; k++){

                                            /** Gera um Id aleatório */
                                            id = getRandomInt(999, 999999)+k;

                                            /** Monta a informação do arquivo */
                                            badge  = '<span class="badge badge-light" style="padding:3px; margin:2px;" id="'+id+'" ><span class="text-danger">Falha ao enviar arquivo :: </span> '+nameFileError[k]+' </span>';

                                            /** Carrega a informação do arquivo na tela */
                                            $("#results").append(badge);

                                        }  
                                                                                        
                                    }                                            

                                },

                                /** Caso tenha falha */
                                error: function (xhr, ajaxOptions, thrownError) {
                                
                                    blockPage(false);

                                    /** Armazena os itens enviados mal sucedidos */
                                    error.push(thrownError);                                            

                                }

                            });
                            
                        };

                        /** Limpa os dados anteriormente informados na array's */
                        nameFile = [];
                        pathFile = [];            
                        nameFileError = [];
                        error = [];
                        formSend = [];                        

                        fileReader.readAsDataURL(file);

                    }else{

                        /** Contabiliza os envios */
                        sendFile++;                                   
                    }
                
                })(files[0]);                        

            }else{

                /** Contabiliza os envios */
                sendFile++;    
            }

            // $(document).off();         
        });
    }
    else {

        /** Abro um popup com a mensagem **/   
        modalPage(true, 0, 0, 'Atenção', 'Sorry! you\'re browser does not support HTML5 File APIs.', '', 'alert', '', true);        
    }
}

/** Envia mais de um formulário, utilizado para multiplos upload e arquivos a serem indexados */
function submitForms(items){

    /** Separa os formulários a serem enviados */
    let forms = items.split(',');

    /** Lista os formulários a serem enviados */
    for(i=0; i<forms.length; i++){

        /** Envia o formulário individualmente */
        sendForm(forms[i], '', true, '', 0, '', 'Enviando arquivos para indexação', 'random', 'circle', 'sm', true); 
    }
    
}

/** Remove itens especificos pelo ID */
function removeItem(id){

    $(id).remove();
}

/** Gera um microtime para servir de ID temporário */
function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min;
}

/** Inverte uma string */
function reverseString(s){
    return s.split("").reverse().join("");
}

/** Pega a extensão de um arquivo */
function extensionFile(file){

    /** Inverte a string */
    let str = reverseString(file);

    /** Separa a string em array pelo separador "ponto"  */
    let ar = str.split('.');

    /** Pega a primeira posição da array e inverte a string para a posição correta*/
    let ext = reverseString(ar[0]);

    /** Retorna a extensão do arquivo */
    return ext;
}

/** Verifica se um arquivo esta dentro das extensões permitidas */
function allowedExtension(ext){

    let allow = 0;

    /** Extensões permitidas */
    let alloweds = [ 'doc', 'pdf', 'jpg', 'jpeg', 'csv', 'xls', 'xlsx', 'docx', 'png', 'ret', 'RET', 'CED', 'ced' ];

    /** Lista as extensões permitidas e compara com a que foi informada */
    alloweds.forEach(function(allowed, i) {
        
        /** Verifica se o arquivo informado não esta nas extensões permitidas */
        if(allowed === (ext.trim()) ){

            allow++;
        }
    });

    /** Verifica se a extensãoe esta na lista dos permitidos */
    if(allow > 0){

        return true;

    }else{

        return false;
    }

}

/** Listando dados Json */
function dadosJson() {
    $.getJSON('dados.json', function(json) {
        $.each(json, function() {
            let info = '<p>' + this['id'] + '</p>';
            $('#boxTeste').append(info);
        });
    });/*GetJson end*/ 
}/*function end*/

/** Prepara form para consulta de documentos */
function formSearchDocs(documents_categorys_id, description){


    let spinner_message  = '<center><div class="spinner-border spinner_small text-success"   role="status">';
        spinner_message += '    <span class="sr-only">Loading...</span>';
        spinner_message += '</div></center>'; 

    /** Prepara o formulário */
    let form = '<form class="form" id="frmSearchDocs">';
        form += '<select class="form-control mb-2 mr-sm-2" name="documents_categorys_id" id="documents_categorys_id">';
        form += '   <option value="0">Selecione</option>';
        
        for(i=0; i<documents_categorys_id.length; i++){
            
            form += '   <option value="'+documents_categorys_id[i]+'">'+description[i]+'</option>';
        }

        form += '</select>';
        form += '<span id="loadTag" class="p-3"></span>';
        form += '<span id="loadSearch" class="p-3"></span>';
        form += '<span id="loadSearchInfo"></span>';
        form += '<input type="hidden" name="FOLDER" value="view" />';
        form += '<input type="hidden" name="ACTION" value="documents_datagrid" />';
        form += '<input type="hidden" name="TABLE" value="documents" />';
        form += '</form>';

    let func = "sendForm('#frmSearchDocs', '', true, 'Enviando consulta...', 0, '#loadContent', '', 'random', 'ball', '', false)";

    /** Abro um popup com a mensagem **/   
    modalPage(true, 500, 0, 'Efetuar consulta por documento', form, '', '', func, false);

    /**Oculta o botão de confirmar **/ 
    $('#btnModalPage').hide();


    /** Carrega categoria de tag selecionada */
    $('#documents_categorys_id').change(function(){

        /** Veirifica se existe valor a ser enviado */
        if($('#documents_categorys_id').val() > 0){


            /** Envia a solicitação */
            $.ajax({

                /** Dados para envio */
                url : 'router.php',
                type : 'post',
                dataType : 'json',
                data : 'FOLDER=view&ACTION=documents_categorys_tags_select&TABLE=documents_categorys_tags&documents_category_id='+$('#documents_categorys_id').val(),

                /** Antes de enviar */
                beforeSend : function () {   
                    
                    /** Informa o loading de carregamento do item */
                    $('#loadTag').html(spinner_message);

                    //Limpa o campo a ser consultado
                    $('#loadSearch').html('');                    
                },        

                /** Caso tenha sucesso */
                success: function(response) {          

                    /** Verifica o código de retorno  */
                    switch (parseInt(response.cod)) {

                        case 0:
                           
                            /** Informa o erro retornado */
                            $('#loadTag').html(response.message);                            

                            break;

                        case 200:

                            /** Carrego o conteúdo **/
                            $('#loadTag').html(response.data);                        

                            break;

                        default:

                            /** Abro um popup com os dados **/                    
                            modalPage(true, 0, 0, 'Atenção', 'Não foi possível carregar o arquivo solicitado', '', 'alert', '', true);
                            break;

                    }
                

                },

                /** Caso tenha falha */
                error: function (xhr, ajaxOptions, thrownError) {
                                      
                    /** Limpa o loading de carregamento do item */
                    $('#loadTag').html(xhr.status + ' - ' + ajaxOptions + ' - ' + thrownError);

                }

            });                                                                                                  

        }else{

            //Limpa a tag selecionada
            $('#loadTag').html('');            

            //Limpa o campo a ser consultado
            $('#loadSearch').html('');

        }
    }); 

}


/** Confirma a gravação de um arquivo de uma determinado serviço */
function confirmFile(folder, table, action, id, file, target){

    /** Parâmetros de envio */
    let qs = {

        FOLDER : folder,
        ACTION : action,
        TABLE  : table,
        id     : id,
        file   : file

    };

    /** Envia a solicitação */
    request(qs, '', true, '', 0, target, ' Aguarde... ', 'random', 'circle', 'sm', true);

}

/** Habilita e desabilita campos informados a partir de campo select */
function enabledInput(inputSelect, inputEnabled){

    $(inputSelect).change(function(){

        if($(inputSelect).val() === 'S'){

            $(inputEnabled).prop("disabled", false );

        }else{

            $(inputEnabled).prop("disabled", true );
        }
    });
}

/** Tecla enter */
function enter2tab(e) {
    if (e.keyCode == 13) {
        cb = parseInt($(this).attr('tabindex'));
 
        if ($(':input[tabindex=\'' + (cb + 1) + '\']') != null) {
            $(':input[tabindex=\'' + (cb + 1) + '\']').focus();
            $(':input[tabindex=\'' + (cb + 1) + '\']').select();
            e.preventDefault();
 
            return false;
        }
    }
}

function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

/** Busca em real time de tabela */
function loadLiveSearch() {

    /** Monitoro o campo de busca */
    $('#search').on('keyup', function () {

        /** Trato os valores de entrada */
        var value = $(this).val();
        var patt = new RegExp(value, "i");

        $('#search_table').find('tr').each(function () {

            if (!($(this).find('td').text().search(patt) >= 0)) {

                $(this).not('#search_table_head').hide();

            }

            if (($(this).find('td').text().search(patt) >= 0)) {

                $(this).show();

            }

        });

    });

}

/** Informa campos obrigatórios antes de enviar o formulário */
function validateForm(form, message, target, print, create){
    
    /** Controles */
    $err = 0;

    /** Percorre o formulário por campos obrigatórios */
    $('[data-required]', form).each(function(){

        if( $(this).attr('data-required') == 'S'){

            /** Verifica se o campo obrigatório possui valor informado */
            if($($(this).attr('data-required', 'S')).val() == ''){

                /** Habilita o tooltip */
                $($(this).attr('data-required', 'S')).tooltip('show');

                /** Aplica a borda vermelha no input */
                $($(this).attr('data-required', 'S')).addClass("border border-danger"); 

                /** Muda a cor do tooltip */
                /*$('.tooltip-inner').css({'background-color' : '#f00'});
                $('.tooltip-inner').css({'border-bottom-color' : '#f00'});*/

                /** Contabiliza os campos obrigatórios vazios */
                $err++;

            }

        }
     
    });
   
    /** Verifica se nao existem erros, caso não existam erros, envio o formulário */
    if($err == 0){

        /** Verifica se é uma impressão de grid */
        if(print == 'S'){

            /** Prepara a solicitação de impressão de grid */
            let data = $(form).serialize().replace('view', 'print');            

            /** Envia a solicitação de impressão da grid */
            request(data, '', true, '', 0, '', 'Enviando solicitação de impressão', 'random', 'circle', 'sm', true);        

        }else{

            /** Envia o formulário com a consulta */
            sendForm(form, '', create, '', 0, '', message, 'random', 'circle', 'sm', true, target);            

        }

    }else{

        /** Remove as bordas vermelhas a partir de 5 segundos e oculta o tooltip */    
        setTimeout(function() {

            /** Percorre o formulário por campos obrigatórios */
            $('[data-required]').each(function(){

                /** Remove os estilos de obrigatório */
                $($(this).attr('data-required', 'S')).removeClass("border border-danger");
                
                /** Oculta o tooltip */
                $($(this).attr('data-required', 'S')).tooltip('hide');  
            
            });

            event.stopPropagation();

        }, 5000);
    }

}

/** Question for action */
function questionModal(data, message){

    /** Função de envio */
    let func = 'request(\''+data+'\', \'\', true, \'\', 0, \'\', \'Aguarde...\', \'random\', \'circle\', \'sm\', true);';

    modalPage(true, 0, 0,   'Atenção', message, '', 'question', func);
}

/** Carrega os itens de uma linha de uma tabela informada */
function prepareBudget(id, source, productId, budgetsId){

    /** Habilita o formulário caso esteja oculto */
    $('.collapse').collapse();   

    /** Envia os dados para o seu destino informado */
    switch(source){
    
        case 'products':

            /** Carrega a descrição */
            $('#description').val($(id).find('td:eq(2)').text());

            /** Seleciona o mês */
            $("#readjustment_month option:contains(" + $(id).find('td:eq(3)').text() + ")").attr('selected', true);

            /** Carrega o dia do vencimento*/
            $('#day_due').val($(id).find('td:eq(4)').text());
            
            /** Carrega o valor do orçamento */
            $('#budget').val($(id).find('td:eq(5)').text());

            /** Informa o id do produto */
            $('#products_id').val(productId);            
                    
        break;

        case 'budgets':

            /** Carrega a descrição */
            $('#description').val($(id).find('td:eq(2)').text());

            /** Separa o último mês de reajuste */
            let lastMonth = ($(id).find('td:eq(9)').text()).split('/');

            /** Array dos meses do ano */
            let months = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];

            /** Seleciona o mês */
            $("#readjustment_month option:contains(" + months[parseInt(lastMonth[1])-1] + ")").attr('selected', true);

            /** Carrega o dia do vencimento*/
            $('#day_due').val($(id).find('td:eq(4)').text());
            
            /** Carrega o valor do orçamento */
            $('#budget').val($(id).find('td:eq(7)').text());

            /** Informa o id do produto */
            $('#products_id').val(productId);   
            
            /** Informa o id do produto */
            $('#clients_budgets_id').val(budgetsId);             

        break;            

    } 
    
    /** Aplica o foco no campo indicado */
    $('#readjustment_type').focus();

    // /** Limpa o ID caso tenha um edição anterior */
    // $('#clients_budgets_id').val(''); 

    /** Limpa o valor do reajuste */
    $('#readjustment_value').val(''); 
    
    /** Limpa o valor do reajuste */
    $('#readjustment_index').val('');    

    /** Limpa o valor reajustado */
    $('#readjustment_budget').val(''); 
    
    /** Inicializa o select da categoria */
    $('#financial_categories_id').prop('selectedIndex',0);   
    
    /** Inicializa o select da conta */
    $('#financial_accounts_id').prop('selectedIndex',0);     
        
}

/** Gera um popup form para consultar um título especifico */
function searchTitle(reference, maturity){

    let form  = '<form id="frmFinancialConsolidationsSearchItem" class="box" autocomplete="off">';
        form += '   <div class="row">';
        form += '       <div class="col-md">';
        form += '           <div class="form-group">';
        form += '               <label for="document">Referência:</label>';
        form += '               <input class="form-control form-control-user" aria-describedby="emailHelp" name="reference" value="'+reference+'" type="text" data-required="S">';
        form += '           </div>';
        form += '       </div>';
        form += '       <div class="col-md">';
        form += '           <div class="form-group">';
        form += '               <label for="maturity">Vencimento:</label>';
        form += '               <input class="form-control form-control-user date" aria-describedby="emailHelp" name="maturity" value="'+maturity+'" type="text" data-required="S">';
        form += '           </div>'; 
        form += '       </div>';       
        form += '   </div>'; 
        form += '   <input type="hidden" name="TABLE" value="financial_consolidations"/>';
        form += '   <input type="hidden" name="ACTION" value="financial_consolidations_search_item"/>';
        form += '   <input type="hidden" name="FOLDER" value="action" />';        
        form += '   <span id="loadFinancialConsolidationsSearchItem"></span>'; 
        form += '</form>';

    /** Carrega a função de logout */
    let func = "validateForm('#frmFinancialConsolidationsSearchItem', 'Enviando consulta', '#loadFinancialConsolidationsSearchItem')";

    /** Habilita a janela popup */
    modalPage(true, 0, 0,   'Atenção', form, '', '', func);

    /** carrega as mascaras */
    loadMask();
}

/** Carrega todos os ckeckboxes selecionados */
function consolidationLoadSelectedItem(table, label, send, data){

    var input = [];

    //Procura pelos itens selecionados
    $(table+' input[type="checkbox"]:checked').each(function() {

        //Carrega os itens selecionados
        input.push($(this).val());  
    });

    /** Verifica se algum item foi selecionado */
    if(input.length > 0){

        /** Verifica se é para enviar os itens selecionados */
        if(send === true){

            /** Envia a solicitação para gerar a nova consolidação e dar baixa nos itens selecionados */
            request(data+'&titles='+input, '', true, '', 0, '', 'Enviando solicitação de consolidação', 'random', 'circle', 'sm', true);

        }else{
    
            /** Armazena o conteúdo selecionado com sua respectiva tabela */    
            $('#consolidationResume'+(table.replace('#', ''))).html(label+': Total selecionados <span class="badge badge-dark">'+input.length+'</span>');
        }

    }else{

        /** Verifica se é para enviar os itens selecionados e caso não exista itens informo*/
        if(send === true){

            /** Habilita a janela popup */
            modalPage(true, 0, 0,   'Atenção', '<b>Nenhum item selecionado para esta solicitação</b>', '', 'alert', '', true)  ;

        }else{

            /** Armazena o conteúdo selecionado com sua respectiva tabela */    
            $('#consolidationResume'+(table.replace('#', ''))).html('');
        }
    }

}

/** Separa os itens a serem consultados junto ao sicoob */
function sendSicoobNotify(qs, data, target_data, create, info, sec, target, message, color, type, size, message_spinner, cont){

    if(cont < data.length){

        /** Envia a solicitação */
        $.ajax({

            /** Dados para envio */
            url : 'router.php',
            type : 'post',
            dataType : 'json',
            data : qs+'&financial_movements_id='+data[cont],

            /** Antes de enviar */
            beforeSend : function () {   

                /** Cria a div responsável em carregar o spinner+info */
                icon = ' <b>Verificando...</b> <div class="spinner-border text-danger" style="width: 1rem; height: 1rem;" role="status">';
                icon += '   <span class="sr-only">Loading...</span>';
                icon += '</div> ';                
                
                /** Informa no item */
                $('#response-'+data[cont]).html(icon);

                /** Bloqueia a tela */
                blockPage(true, '', 0, '', 'Verificando movimentação nº '+data[cont], 'blue', 'circle', 'sm', true); 
                
            },        

            /** Caso tenha sucesso */
            success: function(response) {          

                /** Legenda(s) 
                 *
                 * Code 0 Error
                 * Code 200 OK
                 * 
                 * */  
                
                /** Cancela o block page */
                blockPage(false);

                switch (parseInt(response.cod)) {

                    case 0: 
                    
                            /** Abro um popup com os dados **/                
                            $('#response-'+data[cont]).html(response.message);
                            
                            /** Contabiliza para o próximo registro */
                            cont++;

                            /** Envia a próxima movimentação */
                            sendSicoobNotify(qs, data, target_data, create, info, sec, target, message, color, type, size, message_spinner, cont);                            

                        break;

                    case 200:

                            /** Informa o retorno */
                            $('#response-'+data[cont]).html(response.data);

                            /** Contabiliza para o próximo registro */
                            cont++;

                            /** Envia a próxima movimentação */
                            sendSicoobNotify(qs, data, target_data, create, info, sec, target, message, color, type, size, message_spinner, cont);

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

    } else {

        /** Cancela o block page */
        blockPage(false);        

        /** Abro um popup com os dados **/                
        modalPage(true, 0, 0, 'Atenção', '<div class="alert alert-success" role="alert"><b>Verificação concluída!</b></div>', '', 'success', '', true);         
    }
}


/** Carrega todos os ckeckboxes selecionados */
function commissionsLoadSelectedItem(table, data){

    var input = [];

    //Procura pelos itens selecionados
    $(table+' input[type="checkbox"]:checked').each(function() {

        //Carrega os itens selecionados
        input.push($(this).val());  
    });

    /** Verifica se algum item foi selecionado */
    if(input.length > 0){

        /** Envia a solicitação para gerar a nova consolidação e dar baixa nos itens selecionados */
        request(data+'&client_budgets_commissions_id='+input, '', true, '', 0, '', 'Aguarde...', 'random', 'circle', 'sm', true);

    }else{

        /** Habilita a janela popup */
        modalPage(true, 0, 0,   'Atenção', '<b>Nenhum item selecionado para esta solicitação</b>', '', 'alert', '', true);
    }
}

/** Desfaz base64  */
function b64_to_utf8(str) {

    if(str){
        return window.atob(str);
    }
}

/** Visualiza a mensagem de notificação */
function viewNotify(width, height, title, message, funct=null, close=null, check=null, table=null, target=null, form=null){

    //Pego a configuração do monitor	
    let w = width;
    let h = height;
    let inputs = [];
    let f = form;
    let str = b64_to_utf8(message);

    /** Verifica se precisa carregar os itens selecionados */
    if(check === true){

        //Procura pelos itens selecionados
        $(table+' input[type="checkbox"]:checked').each(function() {

            if(parseInt($(this).val()) > 0){

                //Carrega os itens selecionados
                inputs.push($(this).val());  
            }
        }); 
        
        /** Verifica se algum item não foi selecionado */
        if(inputs.length == 0){

            modalPage(true, 0, 0,   'Atenção', '<b>Nenhum item selecionado para esta solicitação</b>', '', 'alert', '', true);
            return false;
        }
    } 

    /** Carrego o conteúdo **/
    modalPage(true, w, 0, title, decodeURIComponent(escape(str)), '', '', funct, close); 

    /** Verifica se algum item foi selecionado */
    if(inputs.length > 0){

        $(f).append('<input type="hidden" name="inputs" id="inputs" value="'+inputs+'" />');
    }     

}