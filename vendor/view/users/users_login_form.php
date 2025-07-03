<div class="container p-5">
 
    <div class="col-md-12">

        <div class="card o-hidden border-0 shadow-sm my-5 border">

            <div class="card-body p-0">
                            
                <!-- Nested Row within Card Body -->
                <div class="row">
                                                                
                    <div class="col-lg-6">

                        <div class="p-5">

                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4"><img src="img/logo2.png" class="img-fluid" /></h1>
                            </div>

                            <form id="frmAccess" class="box" autocomplete="off">

                                <?php if( (isset($_REQUEST['FIRSTACCESS'])) && ($_REQUEST['FIRSTACCESS'] == "S") ) { ?>

                                    <p class="alert alert-danger"> 
                                        Este é o seu primeiro acesso, cadastre uma senha definitiva para ter acesso.<br/><br/>
                                        A senha precisa ter letras, números e, se possível, caracteres especiais, e um máximo de 10 dígitos.
                                    </p>                                    

                                    <div class="form-group">
                                        <label for="user-email">Email:</label>
                                        <input class="form-control form-control-user" aria-describedby="emailHelp" value="<?php echo $_SESSION['USERSEMAIL']; ?>" disabled type="email">
                                    </div>
                                
                                    <div class="form-group">
                                        <label for="password-inform">Senha:</label>
                                        <input class="form-control form-control-user" name="password-inform" id="password-inform" name="password-inform" maxlength="10" placeholder="* * * * * * * *" type="password">
                                    </div>  
                                    
                                    <div class="form-group">
                                        <label for="password-confirm">Confirmar senha:</label>
                                        <input class="form-control form-control-user" name="password-confirm" id="password-confirm" name="password-inform" maxlength="10" placeholder="* * * * * * * *" type="password">
                                    </div>  
                                    
                                    <input type="hidden" name="TABLE" value="users" />
                                    <input type="hidden" name="ACTION" value="users_first_access" />
                                    <input type="hidden" name="FOLDER" value="action" />  
                                    
                                    <input type="button" class="btn btn-primary btn-user btn-block mb-3" value="Cadastrar senha" onclick="sendForm('#frmAccess', '', true, 'Cadastrando senha', 0, '#loadUser', '', '', 'circle', '', '')" />


                                <?php } else { ?>
                            
                                    <div class="form-group">
                                        <label for="user-email">Email:</label>
                                        <input class="form-control form-control-user" name="user-email" id="user-email" aria-describedby="emailHelp" placeholder="nome@exemplo.com" type="email" data-required="S" data-toggle="tooltip" data-placement="left" title="Informe seu e-mail" value="<?php echo isset($_COOKIE['RememberAccess']) ? $Main->decryptData($_COOKIE['UserEmail']) : '';?>">
                                    </div>
                                
                                    <div class="form-group">
                                        <label for="user-password">Senha:</label>
                                        <input class="form-control form-control-user" name="user-password" id="user-password" name="password-inform" maxlength="10" placeholder="********" type="password" data-required="S" data-toggle="tooltip" data-placement="left" title="Informe sua senha" value="<?php echo isset($_COOKIE['RememberAccess']) ? $Main->decryptData($_COOKIE['UserPassword']) : '';?>">
                                    </div>

                                    <input type="button" class="btn btn-primary btn-user btn-block mb-3" id="btnAccess" onclick="validateForm('#frmAccess', 'Autenticando usuário', '#loadUser')" value="Autenticar usuário" />

                                    <div class="form-group">
                                        
                                        <div class="custom-control custom-checkbox small">
                                            <input class="custom-control-input" id="remember_access" name="remember_access" value="S" type="checkbox" <?php echo isset($_COOKIE['RememberAccess']) ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="remember_access">Lembrar os dados de acesso</label>
                                        </div>

                                    </div>
                                    
                                    <input type="hidden" name="TABLE" value="users" />
                                    <input type="hidden" name="ACTION" value="users_access" />
                                    <input type="hidden" name="FOLDER" value="action" /> 
                                
                                <?php } ?>
                                
                                <h6 id="loadUser" class="text-center"></h6> 
                                    
                            </form>
                                        
                        </div>

                    </div>

                    <div class="col-lg-6 d-none d-lg-block bg-gradient-primary p-5">

                        <div class="p-5 text-white text-center">

                            <h2>Bem vindo de volta!</h2>

                            Esqueceu a senha?

                            <hr/>

                            <div class="text-center">
                                <a class="btn btn-outline-light" href="#">Solicitar nova senha</a>
                            </div>                             

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php if( (isset($_REQUEST['FIRSTACCESS'])) && ($_REQUEST['FIRSTACCESS'] == "S") ) { ?>

    <script type="text/javascript">

        /** Operações ao carregar a página */
        $(document).ready(function(e) {

            /** Coloca o foco no campo e-mail */
            $('input[name="password-inform"]').focus();

            /** Ao pressionar enter no campo e-mail, avança para o campo password */
            $('input[name="password-inform"]').keypress(function(event){
                        
                var keycode = (event.keyCode ? event.keyCode : event.which);
                
                if(keycode == '13'){
                    
                    //Coloco o foco no campo de senha
                    $('input[name="password-confirm"]').focus();
                        
                }
                                        
                event.stopPropagation();
                        
            });	 
            
            
            $('input[name="password-confirm"]').keypress(function(event){
                    
                var keycode = (event.keyCode ? event.keyCode : event.which);
                
                if(keycode == '13'){
                    
                    //Envia a solicitação de cadastro de nova senha
                    validateForm('#frmAccess', 'Autenticando usuário', '#loadUser');
                        
                }
                                        
                event.stopPropagation();
                
            });	        

        });

    </script>       


<?php } else { ?>

    <script type="text/javascript">

        /** Operações ao carregar a página */
        $(document).ready(function(e) {

            /** Coloca o foco no campo e-mail */
            $('input[name="user-email"]').focus();

            /** Ao pressionar enter no campo e-mail, avança para o campo password */
            $('input[name="user-email"]').keypress(function(event){
                        
                var keycode = (event.keyCode ? event.keyCode : event.which);
                
                if(keycode == '13'){
                    
                    //Coloco o foco no campo de senha
                    $('input[name="user-password"]').focus();
                        
                }
                                        
                event.stopPropagation();
                        
            });	 
            
            
            $('input[name="user-password"]').keypress(function(event){
                    
                var keycode = (event.keyCode ? event.keyCode : event.which);
                
                if(keycode == '13'){
                    
                    //Envia a solicitação de autenticação
                    validateForm('#frmAccess', 'Autenticando usuário', '#loadUser');
                        
                }
                                        
                event.stopPropagation();
                
            });	

        });

    </script>    

<?php } ?>