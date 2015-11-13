<div style="clear:both;"></div>

<?php
// Título (inserir/editar)
$inserir_editar = 'Editar';
if ( is_null($categoria['id']) )
{
    $inserir_editar = 'Inserir';
}
?>
<h2>Banners - Categoria - <?=$inserir_editar;?></h2>

<?php
if ( is_array($erros) )
{
    $erro = implode('<br>', $erros);
}

if ( strlen($erro) > 0 )
{

    echo '<div class="msg erro">'.$erro.'</div>';
}
?>

<?php echo form_open_multipart(); ?>
    <table class="form">
        <tbody>
<?php
if ( $categoria['id'] )
{
?>
            <tr>
                <th>Código</th>
                <td>
                    <?php echo $categoria['id']; ?>
                    <input type="hidden" name="categoria[id]" value="<?php echo $categoria['id']; ?>" />
                </td>
            </tr>
<?php
}
?>

            <tr>
                <th>Título</th>
                <td>
                    <?php echo form_input(array('name'=>'categoria[titulo]', 'size'=>'80', 'value'=>$categoria['titulo'])); ?>
                </td>
            </tr>

            <tr>
                <th>Largura</th>
                <td>
                    <?php echo form_input(array('name'=>'categoria[largura]', 'size'=>'30', 'value'=>$categoria['largura'])); ?>
                </td>
            </tr>

            <tr>
                <th>Altura</th>
                <td>
                    <?php echo form_input(array('name'=>'categoria[altura]', 'size'=>'30', 'value'=>$categoria['altura'])); ?>
                </td>
            </tr>

            <tr>
                <th>Largura da miniatura</th>
                <td>
                    <?php echo form_input(array('name'=>'categoria[largura_miniatura]', 'size'=>'30', 'value'=>$categoria['largura_miniatura'])); ?>
                </td>
            </tr>

            <tr>
                <th>Altura da miniatura</th>
                <td>
                    <?php echo form_input(array('name'=>'categoria[altura_miniatura]', 'size'=>'30', 'value'=>$categoria['altura_miniatura'])); ?>
                </td>
            </tr>

            <tr>
                <th>Posição</th>
                <td>
                    <?php echo form_input(array('name'=>'categoria[posicao]', 'size'=>'30', 'value'=>$categoria['posicao'])); ?>
                </td>
            </tr>

            <tr class="limpo">
                <th></th>
                <td><?php echo form_submit('submit','Salvar','class="button ok"'); ?> <?php echo anchor('banners/categorias_listar','Cancelar','class="button cancel"'); ?></td>
            </tr>
        </tbody>
    </table>
<?php echo form_close(); ?>
