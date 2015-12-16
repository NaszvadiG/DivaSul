<?php
// Título (inserir/editar)
$inserir_editar = 'Inserir';
if ( strlen($cidade['id']) > 0 )
{
    $inserir_editar = 'Editar';
}
?>

<h2>Cidade - <?php echo $inserir_editar;?></h2>

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

<?php echo form_open_multipart('site/cidades/editar/'.$cidade['id']); ?>
<input type="hidden" name="cidade[id]" value="<?php echo $cidade['id'];?>" />
<table width="100%" border="0">
<?php
if ( strlen($cidade['id']) > 0 )
{
?>
    <tr>
        <th align="right">Código:</th>
        <td><?php echo $cidade['id'];?></td>
    </tr>
<?php
}
?>

    <tr>
        <th align="right">Nome:</th>
        <td>
<?php
$data = array(
    'name' => 'cidade[nome]',
    'id' => 'nome',
    'value' => $cidade['nome'],
    'size' => '60',
    'title' => 'Informe o nome da cidade'
);
?>
            <?php echo form_input($data);?>
        </td>
    </tr>

    <tr>
        <th align="right">CEP:</th>
        <td>
<?php
$data = array(
    'name' => 'cidade[cep]',
    'id' => 'cep',
    'value' => $cidade['cep'],
    'size' => '60',
    'title' => 'Informe o CEP da cidade'
);
?>
            <?php echo form_input($data);?>
        </td>
    </tr>

    <tr>
        <th>Estado:</th>
        <td>
            <?php echo form_dropdown('cidade[estado_id]', $estados, $cidade['estado_id']);?>
        </td>
    </tr>

    <tr class="limpo">
        <th>&nbsp;</th>
        <td>
            <?php echo form_submit('submit', 'Salvar', 'class="button ok"');?>
            <?php echo anchor('site/cidades/listar', 'Cancelar', 'class="button cancel"');?>
        </td>
    </tr>
</table>
<?php echo form_close(); ?>