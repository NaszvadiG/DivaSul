<?php
// Título (inserir/editar)
$inserir_editar = 'Editar';
if ( is_null($menu['id']) )
{
    $inserir_editar = 'Inserir';
}
?>
<h2>Menu - <?=$inserir_editar;?></h2>

<?php
if ( !empty($erro) )
{
    echo '<div class="msg erro">'.$erro.'</div>';
}
?>
<?php echo form_open()); ?>
<table class="form">
    <tbody>
<?php
if ( strlen($menu['id']) > 0 )
{
?>
        <tr>
            <th>Código</th>
            <td>
                <?php echo $menu['id']; ?>
                <input type="hidden" name="menu[id]" value="<?php echo $menu['id']; ?>"/>
            </td>
        </tr>
<?php
}
?>

        <tr>
            <th>Título</th>
            <td><?php echo form_input('menu[title]',$menu['title'],'size="50"'); ?></td>
        </tr>

        <tr class="limpo">
            <th></th>
            <td><?php echo form_submit('submit','Salvar','class="button ok"'); ?> <?php echo anchor('menus','Cancelar','class="button cancel"'); ?></td>
        </tr>

    </tbody>
</table>
<?php echo form_close(); ?>
