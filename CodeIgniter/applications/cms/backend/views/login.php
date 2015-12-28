<div class="login-box">

<script>
$('body').addClass('login-page');
</script>

    <div class="login-logo">
        <a href="<?php echo base_url(); ?>"><b>DivaSul</b> - Área Restrita</a>
    </div>
    <!-- /.login-logo -->

    <div class="login-box-body">
    <p class="login-box-msg"><img src="<?php echo base_url('../arquivos/imagens/logo.jpg'); ?>" style="width:100%;"/></p>


        <?php
            // Mensagens:
            if ( is_array($erros) && count($erros) > 0 )
            {
                $erro = implode('<br>', $erros);
            }
            if ( strlen($erro) > 0 )
            {

            echo '<div class="callout callout-danger">
                    <h5>'.$erro.'</h5>
                  </div>';
            }
            // Fim das mensagens
        ?>

        <?php echo form_open('', 'onsubmit="return valida(this);"'); ?>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Usuário" id="usuario" name="usuario">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Senha" id="senha" name="senha">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8"></div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Entrar</button>
                </div>
                <!-- /.col -->
            </div>
        <?php echo form_close(); ?>
<!--
        <div class="social-auth-links text-center">
            <p>- Ou -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Entre usando sua conta Facebook</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Entre usando sua conta Google+</a>
        </div>
-->
        <!-- /.social-auth-links -->

<!--
        <a href="#">Esqueci minha senha</a><br>
        <a href="register.html" class="text-center">Quero criar uma conta</a>
-->

        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
    $(function()
    {
      $('input').iCheck(
      {
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });
    </script>
    <script type="text/javascript">
    function valida(form)
    {
        var ok = false;
        if ( form.usuario.value.length == 0 && form.senha.value.length == 0 )
        {
            alert("Por favor, preencha os campos usuário e senha.");
            form.usuario.focus();
        }
        else if ( form.usuario.value.length == 0 )
        {
            alert("Para efetuar o login você deve informar seu usuário.");
            form.usuario.focus();
        }
        else if ( form.senha.value == "" )
        {
            alert("Por favor, digite a sua senha para efetuar o login.");
            form.senha.focus();
        }
        else
        {
            ok = true;
        }

        return ok;
    }
    $('#usuario').focus();
    </script>
</div>
