<h2>Módulos - <?php echo is_null($modulo['id'])?'Adicionar':'Atualizar';?></h2>

<?php
if ( !empty($erro) )
{
    echo '<div class="msg erro">'.$erro.'</div>';
}
?>

<?php echo form_open_multipart('modulos/editar/'.$modulo['id']); ?>
    <table width="100%" border="0">
<?php
// Campo Id (se definido aparece)
if ( strlen($modulo['id']) > 0 )
{
?>
        <tr>
            <th align="right">Código:</th>
            <td>
                <?php echo $modulo['id']; ?>
                <input type="hidden" id="id" name="modulo[id]" value="<?php echo $modulo['id']; ?>" />
            </td>
        </tr>
<?php
}
?>

        <tr>
            <th align="right">Título:</th>
            <td>
<?php
// Campo título
$input_data = array(
    'name' => 'modulo[titulo]',
    'id' => 'titulo',
    'value' => $modulo['titulo'],
    'size' => '60'
);
?>
                <?php echo form_input($input_data); ?>
            </td>
        </tr>

        <tr>
            <th align="right">Descrição:</th>
            <td>
<?php
$input_data = array(
    'name' => 'modulo[descricao]',
    'id' => 'descricao',
    'value' => $modulo['descricao'],
    'size' => '170',
    'rows' => '9',
    'title' => 'Informe a descrição do módulo',
    'class' => 'ckeditor'
);
?>
                <?php echo form_textarea($input_data); ?>
            </td>
        </tr>

        <tr>
            <th align="right">Controller:</th>
            <td>
<?php
// Campo descrição
$input_data = array(
    'name' => 'modulo[path]',
    'id' => 'path',
    'value' => $modulo['path'],
    'size' => '60'
);
?>
                <?php echo form_input($input_data); ?>
            </td>
        </tr>

        <tr>
            <th align="right">Permissão padrão:</th>
            <td>
<?php
// Campo que define se é uma permissão padrão
$input_data = array(
    'name' => 'modulo[padrao]',
    'id' => 'padrao',
    'value' => '1',
    'checked' => $modulo['padrao'] == '1'
);
?>
                <?php echo form_checkbox($input_data); ?>
            </td>
        </tr>

        <tr class="limpo">
            <th></th>
            <td>
                <?php echo form_submit('submit','Salvar','class="button ok"'); echo anchor('modulos','Cancelar','class="button cancel"'); ?>
            </td>
        </tr>
    </table>
<?php echo form_close(); ?>