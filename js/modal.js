/*!
 * Modale v1.0.0
 * www.souza.inf.br/
 * Copyright (c) 2021, Souza Consultória Tecnologica
 */

function modalPage(create, width, sec, title, message, color, type, func, close, height, screensaver) {


    /** Verifica se é a criação da janela popup/modal */
    if (create === true) {

        var div = '';
        var color_modal = '';
        var color_text = '';
        var icon = '';

        /** Verifica se a cor foi informada */
        if (!color) {

            color_modal = 'bg-light';

        } else {

            switch (color) {

                case 'blue':

                    color_modal = 'bg-primary';
                    color_text = 'text-white';

                    break;

                case 'grey':

                    color_modal = 'bg-secondary';
                    color_text = 'text-white';

                    break;

                case 'green':

                    color_modal = 'bg-success';
                    color_text = 'text-white';

                    break;


                case 'red':

                    color_modal = 'bg-danger';
                    color_text = 'text-white';

                    break;


                case 'yellow':

                    color_modal = 'bg-warning';
                    color_text = 'text-white';

                    break;


                case 'cyan':

                    color_modal = 'bg-info';
                    color_text = 'text-white';

                    break;


                case 'white':

                    color_modal = 'bg-light';

                    break;


                case 'black':

                    color_modal = 'bg-dark';
                    color_text = 'text-white';

                    break;

                case 'random':

                    /** Temas disponiveis */
                    var a = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];

                    /** Embaralha os temas */
                    var color_rand = shuffle(a);

                    /** Pega o primeiro item embaralhado */
                    color_modal = 'bg-' + color_rand[0];

                    break;


                default:

                    color_modal = 'bg-light';
            }

        }

        /** Verifica o tipo de mensagem para carregar o icone */
        if (type) {

            switch (type) {


                case "alert":

                    icon = '<i class="fas fa-exclamation-triangle fa-3x"></i>';

                    break;

                case "info":

                    icon = '<i class="fas fa-info fa-3x"></i>';

                    break;

                case "error":

                    icon = '<i class="fas fa-times fa-3x"></i>';

                    break;

                case "checked":

                    icon = '<i class="far fa-check-square fa-3x"></i>';

                    break;

                case "expired":

                    icon = '<i class="far fa-times-circle fa-3x"></i>';

                    break;

                case "question":

                    icon = '<i class="fas fa-question fa-3x"></i>';

                    break;

                case "success":

                    icon = '<i class="fas fa-check fa-3x"></i>';

                    break;

                case "delete":

                    icon = '<i class="fas fa-trash-alt fa-3x"></i>';

                    break;

                case "circle":

                    /** Cria a div responsável em carregar o spinner+info */
                    icon = '<div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">';
                    icon += '   <span class="sr-only">Loading...</span>';
                    icon += '</div>';

                    break;


                case "ball":

                    /** Cria a div responsável em carregar o spinner+info */
                    icon = '<div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">';
                    icon += '   <span class="sr-only">Loading...</span>';
                    icon += '</div>';

                    break;

            }


        }

        div += '<div id="modal-block">';
        div += '    <div class="modal-box animate slideIn">';
        div += '        <div class="card ' + color_text + ' ' + color_modal + '">';
        div += '            <h5 class="card-header">' + title;
        div += '                <button type="button" class="close" aria-label="Close" onclick="'+(screensaver === true ? 'screensaver()' : 'modalPage(false)')+'">';
        div += '                    <span aria-hidden="true">&times;</span>';
        div += '                </button>';
        div += '            </h5>';
        div += '            <div class="card-body">';
        div += '              <h5 class="text-center">' + icon + '</h5>';
        div += '              <p class="text-center">' + message + '</p>';
        div += '            </div>';
        div += '            <div class="card-footer text-muted text-right">';

        /** Verifica se existe função a ser utilizada */
        if (func) {

            div += '            <div class="row">';
            div += '                <div class="col-md-6 text-left">';
            div += '                    <button class="btn btn-danger" onclick="'+(screensaver === true ? 'screensaver()' : 'modalPage(false)')+'"><i class="far fa-times-circle mr-1"></i>Cancelar</button>';
            div += '                </div>';

            div += '                <div class="col-md-6 text-right">';
            div += '                    <button class="btn btn-primary" onclick="' + func + '" id="btnModalPage"><i class="fas fa-check-circle mr-1"></i>Confirmar</button>';
            div += '                </div>';
            div += '            </div>';

        }

        /** Verifica se é para habilitar o boltão de fechar */
        if (close === true) {

            div += '                <button class="btn btn-danger" onclick="modalPage(false)"><i class="far fa-times-circle mr-1"></i> Fechar</button>';
        }

        div += '            </div>';
        div += '        </div>';
        div += '    </div>';
        div += '    <div class="blackoutModal"></div>';//Background total na tela            
        div += '</div>';


        /** Adiciona o modal na tela */
        $('body').append(div);

        /** Aplica as especificações css na nova janela */
        $('.modal-box').css({
            'position': 'absolute',
            'width': (width > 0 ? width : 500) + 'px',
            'height': (height > 0 ? height + 'px' : 'auto'),
            'left': '48.5%',
            /*'top' : '33%',*/
            'margin-top': '-' + (height > 0 ? (height - 100) / 2 : $('.modal-box').height() / 2) + 'px',
            'margin-left': '-' + (width > 0 ? width / 2 : 250) + 'px'
        });

        /** Verifica se o tempo de execução foi definido */
        if (parseInt(sec) > 0) {

            setTimeout(function () {

                $('div').remove('#modal-block');
            }, sec);//Remove o item a partir dos segundos informados

        }

        /** Desabilita a tecla enter */
        $(document).ready(function(){
            $('input').keypress(function (e) {
                var code = null;
                code = (e.keyCode ? e.keyCode : e.which);               
                return (code == 13) ? false : true;
            });
        });



    } else {

        $('div').remove('#modal-block');
    }

}

function show(str) {

    alert(str);
}