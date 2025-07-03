/*!
 * Screensaver v1.0.0
 * www.souza.inf.br/
 * Copyright (c) 2022, Souza Consultória Tecnologica
 */

function screensaver(){

    /** Remove qualquer modal aberta anteriormente */
    $('div').remove('#modal-block');

    /** Oculta o tooltip abertos anteriormente */
    $('div').remove('.tooltip');     

    /** Prepara o protetor de tela */
    div  = '<div id="screensaver">';    
    div += '    <div id="container">';
    div += '        <canvas id="canvas"></canvas>';
    div += '    </div>';     
    div += '    <script src="js/proton/stats.js"></script>';
    div += '    <script src="js/proton/proton.js"></script>'
    div += '    <script src="js/proton/color.js"></script>';
    div += '    <script src="js/proton/proton.start.js"></script>';
    div += '    <script>';
    div += '        $(document).ready(function(){';
    div += '            $(\'div\').remove(\'#stats\');';
    div += '            $("#canvas").on(\'click\', function(){';
    div += '                $(\'div\').remove(\'#screensaver\');';
    div += '                loadFormLogin();';/** Habilita o formulário para informar a senha */
    div += '            });';
    div += '            $(\'input\').keypress(function (e) {';
    div += '                var code = null;';
    div += '                code = (e.keyCode ? e.keyCode : e.which);';                
    div += '                return (code == 13) ? false : true;';
    div += '            });';
    div += '        });';
    div += '    </script>';     
    div += '</div>';


    /** Adiciona o protetor de tela */
    $('body').append(div);    


}

/** Carrega o formulário para informar a senha */
function loadFormLogin(){

    /** Carrega o cookie que contém o e-mail do usuario logado */
    //let recuperandoCookie = document.cookie.split('UserEmail'+"=")[1];

    /** Prepara a função de autenticação e envio */
    let func = 'validateForm(\'#frmNewSession\', \'Autenticando usuário\', \'#loadUser\', \'\')';

    /** Prepara o formulário de informar a senha */
    let formLogin  = '<form action="" id="frmNewSession" class="box" autocomplete="off">';
        formLogin += '  <div class="form-group">';
        /*formLogin += '  <div class="form-group">';
        formLogin += '      <label for="user-email">Email:</label>';
        formLogin += '      <input class="form-control form-control-user" aria-describedby="emailHelp" value="'+recuperandoCookie+'" type="email">';
        formLogin += '  </div>';*/        
        formLogin += '      <label for="user-password">Informe a senha:</label>';
        formLogin += '      <input class="form-control form-control-user" name="user-password" id="user-password" name="user-password" data-required="S" data-toggle="tooltip" data-placement="top" title="Informe sua senha" maxlength="10" placeholder="* * * * * * * *" type="password">';
        formLogin += '  </div> ';        
        formLogin += '  <input type="hidden" name="TABLE" value="users" />';
        formLogin += '  <input type="hidden" name="ACTION" value="users_new_session" />';
        formLogin += '  <input type="hidden" name="FOLDER" value="action" /> ';
        formLogin += '  <h6 id="loadUser" class="text-center"></h6>';
        formLogin += '</form>';  
        
        formLogin += '<script type="text/javascript">';
        formLogin += '  $(document).ready(function(e) {';
        formLogin += '      $(\'input[name="user-password"]\').focus();';
        formLogin += '      $(\'input[name="user-password"]\').keypress(function (e) {';
        formLogin += '          var keycode = (event.keyCode ? event.keyCode : event.which);';
        formLogin += '          if(keycode == \'13\'){';               
        formLogin += '              validateForm(\'#frmNewSession\', \'Autenticando usuário\', \'#loadUser\', \'\');';
        formLogin += '          }';
        formLogin += '          event.stopPropagation();';
        formLogin += '      });'; 
        formLogin += '  });'; 
        formLogin += '</script>';       

    /** Habiita a janela para informar a nova senha */
    modalPage(true, 0, 0,   'Atenção', formLogin, '', '', func, false, false, true);

    /** Coloca o foco no campo senha */
    $('input[name="user-password"]').focus();    

}