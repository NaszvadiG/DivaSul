<script type="text/javascript" src="<?php echo base_url('../arquivos/libs/Jcrop/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('../arquivos/libs/Jcrop/js/jquery.Jcrop.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('../arquivos/libs/Jcrop/css/jquery.Jcrop.css'); ?>" type="text/css" />
<script type="text/javascript">
function updateCoords(c)
{
    $('#imageX').val(c.x);
    $('#imageY').val(c.y);
    $('#imageW').val(c.w);
    $('#imageH').val(c.h);
};

function checkCoords()
{
    if ( parseInt($('#imageW').val()) )
    {
        return true;
    }
    else
    {
        alert('Por favor, selecione a área a ser recortada da imagem.');
        return false;
    }
}

$(function()
{
<?php if ( $categoria['largura'] > 0 && $categoria['altura'] > 0 ) { ?>
    if ( $('#cropbox') )
    {
        $('#cropbox').Jcrop(
        {
            aspectRatio: <?php echo $categoria['largura']; ?>/<?php echo $categoria['altura']; ?>,
            setSelect: [0, 0, <?php echo $categoria['largura']; ?>, <?php echo $categoria['altura']; ?>],
            onSelect: updateCoords
        });
    }
<?php } ?>
});
</script>

<?php
// Título (inserir/editar)
$inserir_editar = 'Editar';
if ( is_null($banner['id']) )
{
    $inserir_editar = 'Inserir';
}
?>
<h2>Banner - <?=$inserir_editar;?></h2>

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
if ( $banner['id'] )
{
?>
            <tr>
                <th>Codigo</th>
                <td>
                    <?php echo $banner['id']; ?>
                    <input type="hidden" name="banner[id]" value="<?php echo $banner['id']; ?>" />
                </td>
            </tr>
<?php
}
?>

            <tr>
                <th>Categoria</th>
                <td>
<?php
// Organiza de forma que possa ser usado no <select>
$options = array();
//$options[0] = '--Selecione--';
foreach ( (array)$categorias as $value )
{
    $options[$value['id']] = $value['titulo'];
}
?>
                    <?php echo form_dropdown('banner[categoria_id]', $options, $banner['categoria_id']); ?>
                </td>
            </tr>

            <tr>
                <th>Título</th>
                <td>
                    <?php echo form_input(array('name'=>'banner[titulo]', 'size'=>'50', 'value'=>$banner['titulo'])); ?>
                </td>
            </tr>

            <tr>
                <th>Link</th>
                <td>
                    <?php echo form_input(array('name'=>'banner[link]', 'size'=>'80', 'value'=>$banner['link'])); ?>
                </td>
            </tr>

            <tr>
                <th>Abrir</th>
                <td>
<?php
// Campo target
$options = array(
    '_self' => 'Mesma janela',
    '_blank' => 'Nova janela/aba'
);
?>
                    <?php echo form_dropdown('banner[target]', $options, $banner['target']); ?>
                </td>
            </tr>

<?php
if ( is_file($path.$banner['img_banner']) )
{
?>
            <tr>
                <th>Banner atual</th>
                <td>
                    <div>
                        <img id="cropbox" src="<?php echo base_url('../'.$site_dir.'/arquivos/banners/'.$banner['img_banner']); ?>" border="0"/>
                        <input type="hidden" id="imageX" name="imageX" />
			            <input type="hidden" id="imageY" name="imageY" />
			            <input type="hidden" id="imageW" name="imageW" />
			            <input type="hidden" id="imageH" name="imageH" />
                    </div>
                </td>
            </tr>
<?php
}
?>
            <tr>
                <th>Imagem</th>
                <td>
<?php
if ( is_file($path.$banner['img_banner']) )
{
?>
                    <a class="remover_atual" data-id="cropbox">Remover atual</a><br>
<?php
}
?>
                    <?php echo form_upload(array('name'=>'img_banner', 'size'=>'80')); ?>
                </td>
            </tr>

            <tr>
                <th>Ordem</th>
                <td>
                    <?php echo form_input(array('name'=>'banner[ordem]', 'size'=>10, 'value'=>$banner['ordem'])); ?>
                </td>
            </tr>

            <tr>
                <th>Ativo</th>
                <td>
<?php
// Campo ativo
$options = array(
    '1' => 'Sim',
    '0' => 'Não'
);
?>
                    <?php echo form_dropdown('banner[ativo]', $options, $banner['ativo']); ?>
                </td>
            </tr>

            <tr class="limpo">
                <th></th>
                <td><?php echo form_submit('submit','Salvar','class="button ok"'); ?> <?php echo anchor('banners/listar','Cancelar','class="button cancel"'); ?></td>
            </tr>
        </tbody>
    </table>
<?php echo form_close(); ?>

<script type="text/javascript">
var elemento;
$('.remover_atual').on('click', function()
{
    var elemento = $('#'+$(this).attr('data-id'));
    var img = elemento.attr('src').split('/').pop();
    $(this).remove();
    $.ajax({
        url:'<?php echo base_url('banners/remover_imagem/'.$banner['id']); ?>/'+img,
        success:function(data)
        {
            if ( data == '1' )
            {
                elemento.parent().parent().parent().remove();
                $(this).remove();
            }
            else
            {
                alert('Não foi possível remover a imagem.');
            }
        },
        error:function()
        {
            alert('Erro ao remover imagem.');
        }
    })
});
</script>
