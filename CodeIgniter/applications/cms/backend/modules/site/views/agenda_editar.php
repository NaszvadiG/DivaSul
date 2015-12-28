<style type="text/css">
.required{color:red !important;}
</style>

<?php
// Título (inserir/editar)
$inserir_editar = 'Editar';
if ( is_null($banner['id']) )
{
    $inserir_editar = 'Inserir';
}
?>
<h2>Agenda - <?php echo $inserir_editar;?></h2>
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

<?php echo form_open('site/agenda/editar/'.$agenda['id']); ?>
    <input type="hidden" name="agenda[id]" value="<?php echo $agenda['id'];?>" />
    <table width="100%" border="0">
<?php
if ( strlen($agenda['id']) > 0 )
{
?>
        <tr>
            <th align="right">Código<span class="required">*</span></th>
            <td><?php echo $agenda['id'];?></td>
        </tr>
<?php
}
?>

                <?php echo form_input($data);?>
            </td>
        </tr>


        <tr>
            <th align="right">Título<span class="required">*</span></th>
            <td>
<?php
$data = array(
    'name' => 'agenda[titulo]',
    'id' => 'titulo',
    'value' => $agenda['titulo'],
    'size' => '60',
    'title' => 'Informe o título da agenda'
);
?>
                <?php echo form_input($data);?>
            </td>
        </tr>

        <tr>
            <th align="right">Descrição<span class="required">*</span></th>
            <td>
<?php
$input_data = array(
    'name' => 'agenda[descricao]',
    'id' => 'descricao',
    'value' => $agenda['descricao'],
    'size' => '170',
    'rows' => '9',
    'title' => 'Informe a descrição do agenda',
    'class' => 'ckeditor'
);
?>
                <?php echo form_textarea($input_data);?>
            </td>
        </tr>

        <tr>
            <th align="right">Data<span class="required">*</span></th>
            <td>
<?php
$data = array(
    'name' => 'agenda[data]',
    'id' => 'valor',
    'value' => $agenda['data'],
    'size' => '60',
    'title' => 'Informe o data'
);
?>
                <?php echo form_input($data);?>
            </td>
        </tr>

        <tr>
            <th>Concluído<span class="required">*</span></th>
            <td>
<?php
// Campo concluido
$options = array(
    1 => 'Sim',
    0 => 'Não'
);
?>
                <?php echo form_dropdown('agenda[concluido]', $options, $agenda['concluido']);?>
            </td>
        </tr>

        <tr class="limpo">
            <th>&nbsp;</th>
            <td>
                <?php echo form_submit('submit', 'Salvar', 'class="button ok"');?>
                <?php echo anchor('site/agenda', 'Cancelar', 'class="button cancel"');?>
            </td>
        </tr>
    </table>
<?php echo form_close(); ?>
