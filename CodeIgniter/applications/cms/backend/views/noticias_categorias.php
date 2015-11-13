<?php
// Título (inserir/editar)
$inserir_editar = 'Editar';
if ( is_null($categoria['id']) )
{
    $inserir_editar = 'Inserir';
}
?>
<h2>Categoria - <?=$inserir_editar;?></h2>

<?php
if ( is_array($erros) )
{
    $erros = implode('<br>', $erros);
}

if ( strlen($erros) > 0 )
{

    echo '<div class="msg error">'.$erros.'</div>';
}
?>

<?php echo form_open(); ?>
<input type="hidden" name="categoria[site_id]" value="<?php echo strlen($categoria['site_id']) > 0 ? $categoria['site_id'] : $site_id; ?>" />
    <table class="form">
        <tbody>
<?php
if ( $categoria['id'] )
{
?>
            <tr>
                <th>ID</th>
                <td>
                    <?php echo $categoria['id']; ?>
                    <input type="hidden" name="categoria[id]" value="<?php echo $categoria['id']; ?>" />
                </td>
            </tr>
<?php
}
?>

            <tr>
                <th>Categoria pai</th>
                <td>
<?php
// Organiza de forma que possa ser usado no <select>
$options = array();
$options[null] = '--Nenhuma--';
foreach ( (array)$categorias as $value )
{
    $options[$value['id']] = $value['titulo'];
}
?>
                    <?php echo form_dropdown('categoria[parent_id]', $options, $categoria['parent_id']); ?>
                </td>
            </tr>

            <tr>
                <th>Título</th>
                <td>
                    <?php echo form_input(array('name'=>'categoria[titulo]', 'size'=>'80', 'value'=>$categoria['titulo'])); ?>
                </td>
            </tr>

            <tr class="limpo">
                <th></th>
                <td><?php echo form_submit('submit','Salvar','class="button ok"'); ?> <?php echo anchor('noticias/listar_categorias','Cancelar','class="button cancel"'); ?></td>
            </tr>
        </tbody>
    </table>
<?php echo form_close(); ?>