<h1><?php echo $titulo; ?> - <?php echo (strlen($registro['id']) > 0 ? 'Editar' : 'Inserir');?></h1>

<?php
if ( is_array($erros) )
{
    $erro = implode('<br>', $erros);
}
if ( strlen($erro) > 0 )
{
?>
<div class="modal modal-danger">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Oops!</h4>
            </div>
            <div class="modal-body">
                <p><?php echo $erro; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<div class="box box-primary">
    <?php echo form_open_multipart(base_url($module.'/'.$controller.'/'.$funcao_editar.'/'.$registro['id']), array('role' => 'form')); ?>
        <div class="box-body">
            <div class="row">
<?php
$autos = array();
if ( isset($campos) && is_array($campos) && count($campos) > 0 )
{
    foreach ( $campos as $campo )
    {
        $hidden = '';
        if ( $campo['hidden'] )
        {
            $hidden = 'style="display:none"';
        }
?>
                 <div id="campo_<?php echo $campo['id']; ?>" class="form-group col-md-<?php echo $campo['tamanho'] > 0 ? $campo['tamanho'] : '12'; ?>" <?php echo $hidden; ?>>
                    <label for="<?php echo $campo['id']; ?>"><?php echo $campo['label']; ?> 
<?php
        if ( $campo['required'] )
        {
?>
<span class="required">*</span>
<?php
        }
?>
</label>

<?php
        if ( strlen($campo['pre']) > 0 || strlen($campo['pos']) > 0 )
        {
?>
                    <div class="input-group">
<?php
        }

        if ( strlen($campo['pre']) > 0 )
        {
            echo $campo['pre'];
        }

        if ( $campo['type'] == 'dropdown' )
        {
            echo form_dropdown('registro['.$campo['id'].']', $campo['options'], $campo['value'], 'id="'.$campo['id'].'" name="'.$campo['name'].'" class="form-control '.$campo['class'].'" title="'.$campo['placeholder'].'" '.$campo['attrs']);
        }
        elseif ( $campo['type'] == 'multi_upload' )
        {
            require_once(APPPATH.'views/default_editar_multiupload.php');
            if ( $campo['foto_capa'] )
            {
?>
                        <input type="hidden" id="foto_capa" name="registro[foto_capa]" value="<?php echo $campo['value'];?>" />
<?php
            }
?>
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
            $('#bt_salvar').attr('disabled','disabled');
            $('#bt_salvar').attr('title','Aguarde o upload das imagens para salvar...');
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
            $('#bt_salvar').removeAttr('disabled');
            $('#bt_salvar').removeAttr('title');
        },
        onSelectError : function()
        {
            alert('Não foi possível selecionar estas imagens. Lembre-se de que são permitidas até 12 imagens JPG.');
        }
    });
});
</script>
                <div id="imagens_existentes">
<?php
$imagens = glob(SERVERPATH.$path.$registro['id']."/*_thumb.*", GLOB_BRACE);
if ( is_array($imagens) && count($imagens) > 0 )
{
    foreach ( $imagens as $k => $imagem )
    {
        $imagem = basename($imagem);
        $imagem_grande = str_replace('_thumb', '', $imagem);
?>
                    <div id="imagem_<?php echo $imagem; ?>" class="caixa_imagem">
                        <label for="<?php echo $k; ?>">
                            <div style="background-image:url(<?php echo site_url('../'.$path.$registro['id'].'/'.$imagem); ?>);" class="imagem">
                                <img src="<?php echo site_url('../arquivos/css/icons/delete.png'); ?>" class="remover_imagem" id="<?php echo $imagem; ?>" title="Clique para remover esta imagem" />
                            </div>
                        </label>
                        <br>
                        <input type="checkbox" id="<?php echo $k; ?>" data-id="<?php echo $imagem; ?>" value="1" class="capa" title="Marque para definir esta imagem como capa" <?php if($imagem==$registro['foto_capa']){echo 'checked';}; ?> />
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

<script type="text/javascript">
$(function()
{
    // Inicia com as imagens ja existentes (se for insercao ou se houver imagens temporarias(editou e nao salvou))
    if ('<?php echo $registro['id']; ?>' != '')
    {
        $.ajax(
        {
            type: 'post',
            async: false,
            url:'<?php echo site_url($module.'/'.$controller.'/ajax_obter_imagens/'.$registro['id']); ?>',
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
            url:'<?php echo site_url($module.'/'.$controller.'/remover_imagem/'.$registro['id']); ?>/'+imagem,
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
        url:'<?php echo site_url($module.'/'.$controller.'/ajax_remover_imagem_temporaria'); ?>/'+imagem,
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
<?php
        }
        elseif ( $campo['type'] == 'subdetail' && is_array($campo['colunas']) )
        {
?>
<div>
    <button id="bt_add_produto" class="btn btn-app"><i class="fa fa-plus"></i> Adicionar produto</button>
    <table class="zebra">
        <thead>
            <tr>
<?php
            foreach ( $campo['colunas'] as $k => $col )
            {
?>
                <th data-column-id="<?php echo $k; ?>" <?php echo implode(' ', $col['attr']); ?>><?php echo $col['titulo']; ?></th>
<?php
            }
?>
            </tr>
        </thead>
        <tbody id="subdetail_produtos">
<?php
            if ( $campo['value'] && is_array($campo['value']) && count($campo['value']) > 0 )
            {
                foreach ( $campo['value'] as $reg )
                {
?>
            <tr>
<?php
                    foreach ( $reg as $linha )
                    {
                        echo $linha;
                    }
?>
            </tr>
<?php
                }
            }
?>
        </tbody>
    </table>
</div>
<?php
        }
        else
        {
            if ( $campo['autocomplete'] )
            {
                $autos[$campo['id']] = $campo['autocomplete'];
            }
?>
        <input type="<?php echo $campo['type']; ?>"
               class="form-control <?php echo $campo['class']; ?>"
               id="<?php echo $campo['id']; ?>"
               name="<?php echo $campo['name']; ?>"
               placeholder="<?php echo $campo['placeholder']; ?>"
               title="<?php echo $campo['placeholder']; ?>"
               <?php echo $campo['attrs']; ?>
               value="<?php echo $campo['value']?>" />
<?php
        }

        if ( strlen($campo['pos']) > 0 )
        {
            echo $campo['pos'];
        }

        if ( strlen($campo['pre']) > 0 || strlen($campo['pos']) > 0 )
        {
?>
                    </div>
<?php
        }
?>
                </div>
<?php
    }
}
?>
            </div>
        </div>

        <div class="box-footer">
            <div class="row">
                <div class="col-md-6 text-right">
                    <input type="submit" id="bt_salvar" name="submit" class="btn btn-primary" value="Salvar"/>
                    </div>
                <div class="col-md-6 text-left">
                <a class="btn btn-danger" href="<?php echo base_url($module.'/'.$controller.'/'.$funcao_listar); ?>">Cancelar</a>
                </div>
            </div>
        </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
$(function()
{
<?php
foreach ( $autos as $k => $autocomplete )
{
?>
    $('#<?php echo $k; ?>').autocomplete(
    {
        source: function(request, response)
        {
            // show loading
            $('.ui-autocomplete').show().html('<div class="text-center"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i></div>');  
            $.ajax(
            {
                type : 'GET',
                url: '<?php echo base_url($module.'/'.$controller.'/'.$autocomplete); ?>/'+$('#<?php echo $k; ?>').val(),
                success: function(data)
                {
                    if (data.length > 0)
                    {
                        response(data.split('|#|'));
                    }
                    else
                    {
                        $('.ui-autocomplete').hide();  
                    }
                }
            });
        },
        minLength: 1
    })
<?php
}
?>

<?php
echo $custom_js;
?>
});
</script>
