<style type="text/css">
//campos requeridos
.required{color:red !important;}

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
    background-image: url('<?php echo base_url('../arquivos/imagens/black_30.png'); ?>');
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
        buttonText      : " Selecionar imagens ",
        fileExt         : '*.JPEG;*.JPG;*.jpeg;*.jpg;*.GIF;*.gif;*.PNG;*.png',
        fileDesc        : 'Imagens',
        multi           : true,
        auto            : true,
        method          : 'post',
        removeCompleted : true,
        sizeLimit       : 1024*1024*200, //1024*1024 => 1M
        uploadLimit     : 12,
        width           : 120,
        itemTemplate    : ' \
            <div id="${fileID}" class="uploadify-queue-item"> \
                <div class="cancel"><a href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\')">X</a></div> \
                <span class="fileName">${fileName} (${fileSize})</span><span class="data"></span> \
            </div>',
        onError         : function(event, ID, fileObj, errorObj)
        {
            //console.log('Arquivo: '.fileObj.name);
            //console.log(errorObj.type+"::"+errorObj.info);
            alert('Lamento, mas não foi possível fazer o envio deste arquivo. Tente novamente mais tarde ou contate o administrador.');
        },
        onUploadStart : function(file)
        {
            // impede que clique em salvar antes de finalizar o upload
            $('input.button.ok').attr('disabled','disabled');
            $('input.button.ok').attr('title','Aguarde o upload das imagens para salvar...');
        },
        onQueueComplete : function(event, data)
        {
            $.ajax(
            {
                type: 'post',
                async: true,
                url:'<?php echo site_url('site/produtos/ajax_obter_imagens/'.$produto['id']); ?>',
                success: function(data)
                {
                    $('#ajax_imagens').html(data);
                }
            });
            // libera o salvar
            $('input.button.ok').removeAttr('disabled');
            $('input.button.ok').removeAttr('title');
        },
        onSelectError : function()
        {
            alert('Não foi possível selecionar estas imagens. Lembre-se de que são permitidas até 12 imagens JPG.');
        }
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
            <th align="right">Código<span class="required">*</span></th>
            <td><?php echo $produto['id'];?></td>
        </tr>
<?php
}
?>

        <tr>
            <th align="right">Referência<span class="required">*</span></th>
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

        <tr>
            <th>Categoria<span class="required">*</span></th>
            <td>
                <?php echo form_dropdown('produto[categoria_id]', $categorias, $produto['categoria_id']);?>
            </td>
        </tr>

        <tr>
            <th align="right">Título<span class="required">*</span></th>
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
            <th align="right">Descrição<span class="required">*</span></th>
            <td>
<?php
$input_data = array(
    'name' => 'produto[descricao]',
    'id' => 'descricao',
    'value' => $produto['descricao'],
    'size' => '170',
    'rows' => '9',
    'title' => 'Informe a descrição do produto',
    'class' => 'ckeditor'
);
?>
                <?php echo form_textarea($input_data);?>
            </td>
        </tr>

        <tr>
            <th align="right">Valor<span class="required">*</span></th>
            <td>
<?php
$data = array(
    'name' => 'produto[valor]',
    'id' => 'valor',
    'value' => $produto['valor'],
    'size' => '60',
    'title' => 'Informe o valor do produto'
);
?>
                <?php echo form_input($data);?>
            </td>
        </tr>

        <tr>
            <th>Imagens<span class="required">*</span></th>
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
                        <label for="<?php echo $k; ?>">
                            <div style="background-image:url(<?php echo site_url('../'.$path.$produto['id'].'/'.$imagem); ?>);" class="imagem">
                                <img src="<?php echo site_url('../arquivos/css/icons/delete.png'); ?>" class="remover_imagem" id="<?php echo $imagem; ?>" title="Clique para remover esta imagem" />
                            </div>
                        </label>
                        <br>
                        <input type="checkbox" id="<?php echo $k; ?>" data-id="<?php echo $imagem; ?>" value="1" class="capa" title="Marque para definir esta imagem como capa" <?php if($imagem==$produto['foto_capa']){echo 'checked';}; ?> />
                        <label for="<?php echo $k; ?>">Imagem capa</label>
                    </div>
<?php
    }
}
?>
                    <div style="clear:both;"></div>
                    <br />
                    <br />
                </div>
                <br />Imagens ainda não salvas
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
            <th align="right">Ordem</th>
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
            <th>Promoção<span class="required">*</span></th>
            <td>
<?php
// Campo promocao
$options = array(
    1 => 'Sim',
    0 => 'Não'
);
?>
                <?php echo form_dropdown('produto[promocao]', $options, $produto['promocao']);?>
            </td>
        </tr>

        <tr>
            <th>Ativo<span class="required">*</span></th>
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


    // Inicia com as imagens ja existentes (se for insercao ou se houver imagens temporarias(editou e nao salvou))
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
            }
        });
    }

    // Ao definir uma imagem como capa, garante que nenhuma outra ja esteja definida como tal
    $('body').on('click', 'input.capa', function()
    {
        $('.capa').each(function()
        {
            $(this).prop('checked', false);
        });
        $($('#foto_capa').get(0)).attr('value', $(this).attr('data-id'));
        $(this).prop('checked', true);
    });

    // Se clicar em salvar e nao tiver nenhuma definida como capa, marca a primeira
    $('body').on('click', '.limpo .ok', function()
    {
        if ( $('.capa:checked').length == 0 )
        {
            $($('.capa').get(0)).click();
        }
    });

    // Remove uma imagem
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
                    element.parent().parent().parent().remove();
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