<?php
// Titulo (inserir/editar)
$inserir_editar = 'Inserir';
if ( strlen($pais['id']) > 0 )
{
    $inserir_editar = 'Editar';
}
?>

<h2>Pa&iacute;s - <?php echo $inserir_editar;?></h2>

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

<?php echo form_open_multipart('site/paises/editar/'.$pais['id']); ?>
<input type="hidden" name="pais[id]" value="<?php echo $pais['id'];?>" />
<table width="100%" border="0">
<?php
if ( strlen($pais['id']) > 0 )
{
?>
    <tr>
        <th align="right">C&oacute;digo:</th>
        <td><?php echo $pais['id'];?></td>
    </tr>
<?php
}
?>

    <tr>
        <th align="right">Nome:</th>
        <td>
<?php
$data = array(
    'name' => 'pais[nome]',
    'id' => 'nome',
    'value' => $pais['nome'],
    'size' => '60',
    'title' => 'Informe o nome do pais'
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
    'name' => 'pais[sigla]',
    'id' => 'sigla',
    'value' => $pais['sigla'],
    'size' => '60',
    'title' => 'Informe o sigla do pais'
);
?>
            <?php echo form_input($data);?>
        </td>
    </tr>

    <tr class="limpo">
        <th>&nbsp;</th>
        <td>
            <?php echo form_submit('submit', 'Salvar', 'class="button ok"');?>
            <?php echo anchor('site/paises/listar', 'Cancelar', 'class="button cancel"');?>
        </td>
    </tr>
</table>
<?php echo form_close(); ?>