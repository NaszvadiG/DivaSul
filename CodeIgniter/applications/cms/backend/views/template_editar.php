<div style="clear:both"></div>
<?php 
if ( strlen($template['html_last']) > 0 )
{
    echo <<<HTML
    <input type="button" value="Restaurar" style="float:right;" class="button restaurar" onclick="restaurar(this);"/>
HTML;
}
?>

<!-- JS do restaurar -->
<script type="text/javascript">
function restaurar(botao)
{
    botao.style.display='none';

    $.ajax({
        url:'<?=site_url('/templates/obter_valor');?>/<?=$template['id'];?>/html_last',
        success:function(data)
        {
            document.getElementById('template').value = data;
        }
    });
};
</script>
<div style="clear:both"></div>

<?php
// Título (inserir/editar)
$inserir_editar = 'Editar';
if ( is_null($template['id']) )
{
    $inserir_editar = 'Inserir';
}
?>
<h2>Template - <?=$inserir_editar;?></h2>

<?php
if ( !empty($erro) )
{
    echo '<div class="msg erro">'.$erro.'</div>';
}
?>

<?php echo form_open(); ?>
    <table class="form">
        <tbody>
<?php
if ( $template['id'] )
{
?>
            <tr>
                <th>Código</th>
                <td>
                    <?=$template['id'];?>
                    <input type="hidden" name="template[id]" value="<?=$template['id'];?>"/>
                </td>
            </tr>
<?php
}
?>

            <tr>
                <th>Título</th>
                <td>
                    <?=form_input('template[titulo]', $template['titulo'], 'size="80"');?>
                </td>
            </tr>

            <tr>
                <th align="right">Cabeçalho e rodapé padrão:</th>
                <td>
                    <?php
                        // Campo que define se terá o cabeçalho e o rodapé padrão
                        $options = array(
                            'f'=>'Não',
                            't'=>'Sim'
                        );
                    ?>
                    <?=form_dropdown('template[cabecalho_rodape_padrao]', $options, $template['cabecalho_rodape_padrao'], 'id="cabecalho_rodape_padrao" onchange="exibe_oculta_campo_html_header(this.value);"');?>
                </td>
            </tr>

            <tr id="campo_html_header">
                <th>Html header</th>
                <td>
                    <textarea cols="90" rows="12" id="template_html_header" name="template[html_header]"><?=$template['html_header']?></textarea>
                </td>
            </tr>

            <tr>
                <th>Html</th>
                <td>
                    <textarea cols="90" rows="30" id="template" name="template[html]"><?=$template['html']?></textarea>
                </td>
            </tr>

            <tr>
                <th>Baixar</th>
                <td>
                    <input type="button" value="Baixar template" class="button baixar" onclick="baixar_template();" />
                </td>
            </tr>

            <tr class="limpo">
                <th></th>
                <td>
                    <?=form_submit('submit','Salvar','class="button ok"'); ?> <?=anchor('templates','Cancelar','class="button cancel"'); ?>
                </td>
            </tr>
        </tbody>
    </table>
<?php echo form_close(); ?>

<script type="text/JavaScript" language="JavaScript">
function baixar_template()
{
    window.open('<?=site_url('templates/baixar_html_template/'.$template['id']);?>');
}

function exibe_oculta_campo_html_header(value)
{
    var campo_html_header = document.getElementById('campo_html_header');
    if ( value != 't' )
    {
        campo_html_header.style.display='none';
    }
    else
    {
        campo_html_header.style.display='';
    }
}
exibe_oculta_campo_html_header(document.getElementById('cabecalho_rodape_padrao').value);
</script>
