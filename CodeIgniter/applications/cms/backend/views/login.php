<div style="clear:both;"></div>

<h2>Login</h2>

<?php
// Mensagens:
if ( is_array($erros) && count($erros) > 0 )
{
    $erro = implode('<br>', $erros);
}
if ( strlen($erro) > 0 )
{

    echo '<div class="msg erro">'.$erro.'</div>';
}
// Fim das mensagens
?>

<?php echo form_open('', 'onsubmit="return valida(this);"'); ?>
<table>
	<tbody>
		<tr class="limpo">
			<th>Usuário</th>
			<td><?php echo form_input('usuario', '', 'id="usuario"');?></td>
		</tr>
		<tr class="limpo">
			<th>Senha</th>
			<td><?php echo form_password('senha', '', 'id="senha"');?></td>
		</tr>
		<tr class="limpo">
			<th></th>
			<td>
			    <?php echo form_submit('submit','Login','id="confirm" class="button ok"'); ?>
		    </td>
		</tr>
	</tbody>
</table>
<?php echo form_close(); ?>

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
