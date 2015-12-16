<?php
// Titulo (inserir/editar)
$inserir_editar = 'Inserir';
if ( strlen($estado['id']) > 0 )
{
    $inserir_editar = 'Editar';
}
?>

<h2>Estado - <?php echo $inserir_editar;?></h2>

<?php
// Mensagens:
if ( is_array($erros) && count($erros) > 0 )
{
    $erro = implode('<br>', $erros);
}
if ( strlen($erro) > 0 )
{

    echo '<div class="msg error">'.$erro.'</div>';
}
// Fim das mensagens
?>

<?php echo form_open_multipart('site/estados/editar/'.$estado['id']); ?>
<input type="hidden" name="estado[id]" value="<?php echo $estado['id'];?>" />
<table width="100%" border="0">
<?php
if ( strlen($estado['id']) > 0 )
{
?>
    <tr>
        <th align="right">C&oacute;digo:</th>
        <td><?php echo $estado['id'];?></td>
    </tr>
<?php
}
?>

    <tr>
        <th align="right">Nome:</th>
        <td>
<?php
$data = array(
    'name' => 'estado[nome]',
    'id' => 'nome',
    'value' => $estado['nome'],
    'size' => '60',
    'title' => 'Informe o nome do estado'
);
?>
            <?php echo form_input($data);?>
        </td>
    </tr>

    <tr>
        <th align="right">Sigla:</th>
        <td>
<?php
$data = array(
    'name' => 'estado[sigla]',
    'id' => 'sigla',
    'value' => $estado['sigla'],
    'size' => '60',
    'title' => 'Informe a sigla do estado'
);
?>
            <?php echo form_input($data);?>
        </td>
    </tr>

    <tr>
        <th>Pa&iacute;s</th>
        <td>
            <?php echo form_dropdown('estado[pais_id]', $paises, $estado['pais_id']);?>
        </td>
    </tr>

    <tr class="limpo">
        <th>&nbsp;</th>
        <td>
            <?php echo form_submit('submit', 'Salvar', 'class="button ok"');?>
            <?php echo anchor('site/estados/listar', 'Cancelar', 'class="button cancel"');?>
        </td>
    </tr>
</table>
<?php echo form_close(); ?>