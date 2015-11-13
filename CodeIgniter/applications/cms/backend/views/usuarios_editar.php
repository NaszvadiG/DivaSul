<?php
// Título (inserir/editar)
$inserir_editar = 'Editar';
if ( is_null($usuario_['id']) )
{
    $inserir_editar = 'Inserir';
}
?>
<h2>Usuários - <?php echo $inserir_editar;?></h2>

<?php
if ( strlen($erro) > 0 )
{
	echo '<div class="msg erro">'.$erro.'</div>';
}
?>

<?php echo form_open(); ?>
	<table>
		<tbody>
<?php
if ( $usuario_['id'] )
{
?>
            <tr>
                <th>Código</th>
                <td>
                    <?php echo $usuario_['id']; ?>
                    <input type="hidden" name="usuario[id]" value="<?php echo $usuario_['id']; ?>" />
                </td>
            </tr>
<?php
}
?>

			<tr>
				<th>Nome</th>
				<td><?php echo form_input('usuario[nome]', $usuario_['nome']);?></td>
			</tr>

			<tr>
				<th>E-mail</th>
				<td><?php echo form_input('usuario[email]', $usuario_['email']);?></td>
			</tr>

			<tr>
				<th>Usuário</th>
				<td><?php echo form_input('usuario[usuario]', $usuario_['usuario']);?></td>
			</tr>

			<tr>
				<th>Senha</th>
				<td><?php echo form_password('usuario[senha]', '');?></td>
			</tr>

            <tr class="limpo">
				<th></th>
				<td><?php echo form_submit('submit', 'Salvar', 'class="button ok"');?> <?php echo anchor('usuarios', 'Cancelar', 'class="button cancel"');?></td>
			</tr>
		</tbody>
	</table>
<?php echo form_close(); ?>
