<style type="text/css">
div#imagens_existentes div.caixa_imagem,
div#ajax_imagens div.caixa_imagem
{
    float:left;
    padding:5px;
}

div#imagens_existentes div.caixa_imagem input[type='text'],
div#ajax_imagens div.caixa_imagem input[type='text']
{
    width:196px;
}

div#imagens_existentes div.imagem,
div#ajax_imagens div.imagem
{
    width:200px;
    height:133px;
    -o-box-shadow:3px 3px 6px #777;
    -moz-box-shadow:3px 3px 6px #777;
    -webkit-box-shadow:3px 3px 6px #777;
    box-shadow:3px 3px 6px #777;
    background-size:cover;
    background-repeat:no-repeat;
    background-position:center;
}

div#imagens_existentes div img.remover_imagem,
div#ajax_imagens div img.remover_imagem
{
    float:right;
    cursor:pointer;
    padding: 3px 3px 4px 5px;
    background-image: url(../../../arquivos/imagens/black_30.png);
    -0-border-radius: 0px 0px 0px 10px;
    -moz-border-radius: 0px 0px 0px 10px;
    -webkit-border-radius: 0px 0px 0px 10px;
    border-radius: 0px 0px 0px 10px;
    opacity:0.75;
}

div#imagens_existentes div img.remover_imagem:hover,
div#ajax_imagens div img.remover_imagem:hover
{
    opacity:1;
}

div#imagens_existentes div.caixa_imagem input[type='checkbox'].capa,
div#ajax_imagens div.caixa_imagem input[type='checkbox'].capa
{
    margin:5px 5px 7px 1px;
}
</style>

<?php
$path_temporario = urlencode(base64_encode($path_temporario));
?>
<script type="text/javascript">
$(function()
{
    $('#imagens').uploadify(
    {
        uploader        : '<?php echo site_url('../arquivos/libs/uploadify/uploadify.php?path='.$path_temporario); ?>',
        swf             : '<?php echo site_url('../arquivos/libs/uploadify/uploadify.swf'); ?>',
        cancelImg       : '<?php echo site_url('../arquivos/libs/uploadify/cancel.png'); ?>',
        buttonText      : " Selecionar\n imagens ",
        fileExt         : '*.JPEG;*.JPG;*.jpeg;*.jpg;*.GIF;*.gif;*.PNG;*.png',
        fileDesc        : 'Imagens',
        multi           : true,
        auto            : true,
        method          : 'post',
        removeCompleted : true,
        sizeLimit       : 1024*1024*200, //1024*1024 => 1M
        width           : 120,
        itemTemplate : '\
            <div id="${fileID}" class="uploadify-queue-item">\
                <div class="cancel">\
                    <a href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\')">X</a>\
                </div>\
                <span class="fileName">${fileName} (${fileSize})</span><span class="data"></span>\
            </div>',
        onError         : function(event, ID, fileObj, errorObj)
        {
            //console.log('Arquivo: '.fileObj.name);
            //console.log(errorObj.type+"::"+errorObj.info);
            alert('Lamento, mas não foi possível fazer o envio deste arquivo. Tente novamente mais tarde ou contate o administrador.');
        },
        onQueueComplete : function(event, data)
        {
            $.ajax(
            {
                type: 'post',
                async: false,
                url:'<?php echo site_url('site/produtos/ajax_obter_imagens/'.$produto['id']); ?>',
                success: function(data)
                {
                    $('#ajax_imagens').html(data);
                    $('input.capa').click(function()
                    {
                        // Se marcar a caixa
                        if ( $(this).attr('checked') )
                        {
                            // Desmarca todas as caixas
                            $('input.capa').attr('checked', false);
                            // Mantém marcada a caixa que foi marcada
                            $(this).attr('checked', true);
                        }
                    });
                }
            });
        }
    });

    // Inicia com as imagens já existentes (se for inserção ou se houver imagens temporárias(editou e não salvou))
    if ('<?php echo $produto['id']; ?>' != '')
    {
        $.ajax(
        {
            type: 'post',
            async: false,
            url:'<?php echo site_url('site/produtos/ajax_obter_imagens/'.$produto['id']); ?>',
            success: function(data)
            {
                $('#ajax_imagens').html(data);
                $('input.capa').click(function()
                {
                    // Se marcar a caixa
                    if ( $(this).attr('checked') )
                    {
                        // Desmarca todas as caixas
                        $('input.capa').attr('checked', false);
                        // Mantém marcada a caixa que foi marcada
                        $(this).attr('checked', true);
                    }
                });
            }
        });
    }

    $('input.capa').on('click', function()
    {
        $('.capa').each(function()
        {
            $(this).prop('checked', false);
        });
        $($('#foto_capa').get(0)).attr('value', $(this).attr('data-id'));
        $(this).prop('checked', true);
    });

    $('.limpo .ok').on('click', function()
    {
        if ( $('.capa:checked').length == 0 )
        {
            $($('.capa').get(0)).click();
        }
    });

    $('div#imagens_existentes div img.remover_imagem').on('click', function()
    {
        $(this).hide();
        $(this).show(100);
    });
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
<h2>Produto - <?php echo $inserir_editar;?></h2>
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

<?php echo form_open_multipart('site/produtos/editar/'.$produto['id']); ?>
    <input type="hidden" name="produto[id]" value="<?php echo $produto['id'];?>" />
    <input type="hidden" id="foto_capa" name="produto[foto_capa]" value="<?php echo $produto['foto_capa'];?>" />
    <table width="100%" border="0">
<?php
if ( strlen($produto['id']) > 0 )
{
?>
        <tr>
            <th align="right">Código:</th>
            <td><?php echo $produto['id'];?></td>
        </tr>
<?php
}
?>

        <tr>
            <th>Categoria</th>
            <td>
                <?php //echo form_dropdown('produto[categoria_id]', $categorias, $produto['categoria_id']);?>
<?php
    if ( !is_array($produto['categorias']) )
    {
        $produto['categorias'] = explode("','", substr($produto['categorias'],1,-1));
    }
    echo form_multiselect('produto[categorias][]', $categorias, $produto['categorias']);
?>
            </td>
        </tr>

        <tr>
            <th align="right">Título:</th>
            <td>
<?php
$data = array(
    'name' => 'produto[titulo]',
    'id' => 'titulo',
    'value' => $produto['titulo'],
    'size' => '60',
    'title' => 'Informe o título do produto'
);
?>
                <?php echo form_input($data);?>
            </td>
        </tr>

        <tr>
            <th align="right">Referência:</th>
            <td>
<?php
$data = array(
    'name' => 'produto[referencia]',
    'id' => 'referencia',
    'value' => $produto['referencia'],
    'size' => '60',
    'title' => 'Informe a referência do produto'
);
?>
                <?php echo form_input($data);?>
            </td>
        </tr>

<?php
/*
        <tr>
            <th align="right">Link:</th>
            <td>
<?php
$data = array(
    'name' => 'produto[link]',
    'id' => 'link',
    'value' => $produto['link'],
    'size' => '60',
    'title' => 'Informe o link da produto'
);
?>
                <?php echo form_input($data);?>
            </td>
        </tr>
 */
?>
        <tr>
            <th align="right">Descrição:</th>
            <td>
<?php
$input_data = array(
    'name' => 'produto[descricao]',
    'id' => 'descricao',
    'value' => $produto['descricao'],
    'size' => '170',
    'rows' => '9',
    'title' => 'Informe a introdução do produto',
    'class' => 'ckeditor'
);
?>
                <?php echo form_textarea($input_data);?>
            </td>
        </tr>

        <tr>
            <th>Imagens</th>
            <td>
                <div id="imagens_existentes">
<?php
$imagens = glob(SERVERPATH.$path.$produto['id']."/*_thumb.*", GLOB_BRACE);
if ( is_array($imagens) && count($imagens) > 0 )
{
    foreach ( $imagens as $k => $imagem )
    {
        $imagem = basename($imagem);
        $imagem_grande = str_replace('_thumb', '', $imagem);
?>
                    <div id="imagem_<?php echo $imagem; ?>" class="caixa_imagem">
                        <div style="background-image:url(<?php echo site_url('../'.$path.$produto['id'].'/'.$imagem); ?>);" class="imagem">
                            <img src="<?php echo site_url('../arquivos/css/icons/delete.png'); ?>" class="remover_imagem" id="<?php echo $imagem; ?>" title="Clique para remover esta imagem" />
                        </div>
                        <br>
                        <input type="checkbox" id="<?php echo $k; ?>" data-id="<?php echo $imagem; ?>" value="1" class="capa" title="Marque para definir esta imagem como capa" <?php if($imagem==$produto['foto_capa']){echo 'checked';}; ?> /><label for="<?php echo $k; ?>">Imagem capa</label>
                    </div>
<?php
    }
}
?>
                    <div style="clear:both;"></div>
                    <br />
                    <br />
                </div>
                <br />Imagens ainda não salvas:
                <div id="ajax_imagens">
                    <div style="clear:both;"></div>
                </div>
                <div style="clear:both;"></div>
                <br />
                <br />
                <input type="file" id="imagens" name="imagens" />
            </td>
        </tr>


        <tr>
            <th align="right">Ordem:</th>
            <td>
<?php
$data = array(
    'name' => 'produto[ordem]',
    'id' => 'ordem',
    'value' => $produto['ordem'],
    'size' => '10',
    'title' => 'Informe a ordem de exibição do produto'
);
?>
                <?php echo form_input($data);?>
            </td>
        </tr>


        <tr>
            <th>Destaque Principal</th>
            <td>
<?php
// Campo Destaque Principal
$options = array(
    1 => 'Sim',
    0 => 'Não'
);
?>
                <?php echo form_dropdown('produto[destaque_principal]', $options, $produto['destaque_principal']);?>
            </td>
        </tr>

        <tr>
            <th>Destaque Categoria</th>
            <td>
<?php
// Campo Destaque Categoria
$options = array(
    1 => 'Sim',
    0 => 'Não'
);
?>
                <?php echo form_dropdown('produto[destaque_categoria]', $options, $produto['destaque_categoria']);?>
            </td>
        </tr>


        <tr>
            <th>Ativo</th>
            <td>
<?php
// Campo ativo
$options = array(
    1 => 'Sim',
    0 => 'Não'
);
?>
                <?php echo form_dropdown('produto[ativo]', $options, $produto['ativo']);?>
            </td>
        </tr>

        <tr class="limpo">
            <th>&nbsp;</th>
            <td>
                <?php echo form_submit('submit', 'Salvar', 'class="button ok"');?>
                <?php echo anchor('site/produtos', 'Cancelar', 'class="button cancel"');?>
            </td>
        </tr>
    </table>
<?php echo form_close(); ?>
<script type="text/javascript">
$(function()
{
    $('.remover_imagem').on('click', function()
    {
        var element = $(this)
        var imagem = element.attr('id');
        $.ajax(
        {
            type: 'post',
            async: false,
            url:'<?php echo site_url('site/produtos/remover_imagem/'.$produto['id']); ?>/'+imagem,
            success: function(data)
            {
                if ( data != '1' )
                {
                    alert('Não foi possível remover esta imagem.');
                }
                else
                {
                    element.parent().parent().remove();
                }
            }
        });
    });
});


function remover_imagem_temporaria(i, imagem)
{
    $.ajax(
    {
        type: 'post',
        async: false,
        url:'<?php echo site_url('site/produtos/ajax_remover_imagem_temporaria'); ?>/'+imagem,
        success: function(data)
        {
            if ( data != '1' )
            {
                alert('Não foi possível remover esta imagem.');
            }
            else
            {
                $('#imagem_tmp_'+i).remove();
            }
        }
    });
}
</script>
