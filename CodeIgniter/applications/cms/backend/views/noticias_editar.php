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

<script type="text/javascript">
<?php
$path_temporario = urlencode(base64_encode($path_temporario));
?>
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
                url:'<?php echo site_url('noticias/ajax_obter_imagens/'.$noticia['id']); ?>',
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
    $(function()
    {
        $.ajax(
        {
            type: 'post',
            async: false,
            url:'<?php echo site_url('noticias/ajax_obter_imagens/'.$noticia['id']); ?>',
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
<h2>Notícias - <?=$inserir_editar;?></h2>

<?php
if ( is_array($erros) )
{
    $erro = implode('<br>', $erros);
}
if ( strlen($erro) > 0 )
{

    echo '<div class="msg error">'.$erro.'</div>';
}
?>

<?php echo form_open_multipart('noticias/editar/'.$noticia['id']); ?>
    <input type="hidden" name="noticia[id]" value="<?php echo $noticia['id']; ?>" />

<table class="form" id="table_news">
    <tbody>
<?php
if ( strlen($noticia['id']) > 0 )
{
?>
        <tr>
            <th>Código</th>
            <td>
                <?php echo $noticia['id']; ?>
            </td>
        </tr>
<?php
}
?>

        <tr>
            <th>Título</th>
            <td>
                <?php echo form_input(array('name'=>'noticia[titulo]','size'=>'80','value'=>$noticia['titulo']));?>
            </td>
        </tr>
<?php
if ( $this->session->userdata('site_id')==1 )
{
?>
        <tr>
            <th>Cartola</th>
            <td>
                <?php echo form_input(array('name'=>'noticia[cartola]','size'=>'40','value'=>$noticia['cartola']));?>
            </td>
        </tr>
<?php
}
?>

<?php
if ( is_array($categorias) && count($categorias) )
{
    if ( count($categorias) == 1 )
    {
        echo '<input type="hidden" name="categorias_ids" value"'.current(current($categorias)).'"/>';
    }
    else
    {
?>
        <tr>
            <th><?php echo $categoria_titulo; ?></th>
            <td>
<?php
        if ( !is_array($noticia['categorias_ids']) || count($noticia['categorias_ids']) == 0 )
        {
            $noticia['categorias_ids'] = current($categorias);
        }
        $opcoes = array();
        foreach ( $categorias as $categoria )
        {
            $opcoes[$categoria['id']] = $categoria['titulo'];
        }
        
        if ( $permite_mais_de_uma_categoria )
        {
            echo form_multiselect('noticia[categorias_ids][]', $opcoes, $noticia['categorias_ids']);
        }
        else
        {
            echo form_dropdown('noticia[categorias_ids][]', $opcoes, current($noticia['categorias_ids']));
        }
?>
            </td>
        </tr>
<?php
    }
}
?>
        <tr>
            <th id="labelresumo">Introdução da notícia</th>
            <td>
<?php
$input_data = array(
    'name' => 'noticia[intro]',
    'id' => 'intro',
    'value' => $noticia['intro'],
    'size' => '170',
    'rows' => '9',
    'title' => 'Informe a introdução da notícia',
    'class' => 'ckeditor'
);
?>
                <?php echo form_textarea($input_data); ?>
<?php
if ( !is_null($limiteDeCaracteres) && $limiteDeCaracteres > 0 )
{
?>
                <br /><small>Obs.: A recomendação de caracteres para a introdução é <b><?php echo $limiteDeCaracteres; ?></b>. Atualmente está com <big><span id="contador_de_caracteres_titulo" style="font-weight:bold;">0</span></big> caracteres.</small>
<?php
}
?> 
            </td>
        </tr>

        <tr>
            <th>Descrição</th>
            <td>
<?php
$input_data = array(
    'name' => 'noticia[texto]',
    'id' => 'texto',
    'value' => $noticia['texto'],
    'size' => '170',
    'rows' => '9',
    'title' => 'Informe o texto da notícia',
    'class' => 'ckeditor'
);
?>
                <?php echo form_textarea($input_data); ?>
                </div>
            </td>
        </tr>

        <tr>
            <th>Imagens</th>
            <td>
                <div id="imagens_existentes">
<?php
if ( is_array($imagens) && count($imagens) > 0 )
{
    foreach ( $imagens as $imagem )
    {
        $imagem['credito'] = str_replace("'", '"', $imagem['credito']);
        $imagem['legenda'] = str_replace("'", '"', $imagem['legenda']);
?>
                    <div id="imagem_<?php echo $imagem['id']; ?>" class="caixa_imagem">
                        <div style="background-image:url(<?php echo site_url('../'.$path.$imagem['dir'].'/'.str_replace('(', '\(', str_replace(')', '\)', $imagem['arquivo_thumb']))); ?>);" class="imagem">
                            <img src="<?php echo site_url('../arquivos/css/icons/delete.png'); ?>" class="remover_imagem" id="remover_<?php echo $imagem['id']; ?>" title="Clique para remover esta imagem" onclick="remover_imagem('<?php echo $imagem['id']; ?>');" />
                            <input type="hidden" name="imagens[<?php echo $imagem['id']; ?>][id]" value="<?php echo $imagem['id']; ?>" />
                        </div>
                        <br>
                        <!--input type="text" id="credito_<?php echo $imagem['id']; ?>" name="imagens[<?php echo $imagem['id']; ?>][credito]" placeholder="Crédito desta imagem" value='<?php echo $imagem['credito']; ?>' />
                        <br>
                        <input type="text" id="legenda_<?php echo $imagem['id']; ?>" name="imagens[<?php echo $imagem['id']; ?>][legenda]" placeholder="Legenda desta imagem" value='<?php echo $imagem['legenda']; ?>' /-->
                        <br>
                        <input type="checkbox" id="capa_<?php echo $imagem['id']; ?>" name="imagens[<?php echo $imagem['id']; ?>][capa]" value="1" class="capa" title="Marque para definir esta imagem como capa" <?php if($imagem['capa']==1){echo 'checked';}; ?> /><label for="capa_<?php echo $imagem['id']; ?>">Imagem capa</label>
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
            <th>Ativo</th>
            <td>
                <?php echo form_dropdown('noticia[ativo]', array('1'=>'Sim','0'=>'Não'), $noticia['ativo']);?>
            </td>
        </tr>

        <tr class="limpo">
            <th></th>
            <td>
                <?php echo form_submit('submit','Salvar','class="button ok"'); ?><?php echo anchor('noticias','Cancelar','class="button cancel"');?>
            </td>
        </tr>
    </tbody>
</table>
<?php echo form_close(); ?>

<script type="text/javascript">
function remover_imagem(id)
{
    $.ajax(
    {
        type: 'post',
        async: false,
        url:'<?php echo site_url('noticias/ajax_remover_imagem')?>/'+id,
        success: function(data)
        {
            if ( data != '1' )
            {
                alert('Não foi possível remover esta imagem.');
            }
            else
            {
                $('#imagem_'+id).remove();
            }
        }
    });
}

function remover_imagem_temporaria(i, imagem)
{
    $.ajax(
    {
        type: 'post',
        async: false,
        url:'<?php echo site_url('noticias/ajax_remover_imagem_temporaria')?>/'+imagem,
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

$(function()
{
    $('div#imagens_existentes div img.remover_imagem').click(function()
    {
        $(this).hide();
        $(this).show(100);
    });
});

<?php
if ( !is_null($limiteDeCaracteres) && $limiteDeCaracteres > 0 )
{
?>
/**
 * Verifica se o campo intro excedeu o limite de caracteres :D
 */
function verificaLimiteCaracteres(elemento)
{
    var texto = elemento.getData().replace(/<\/?[^>]+(>|$)/g, "").replace(/\n/g, "").trim();
    $('span#contador_de_caracteres_titulo').html(texto.length);
    if ( texto.length > <?php echo $limiteDeCaracteres; ?> )
    {
        $('span#contador_de_caracteres_titulo').css('color', 'red');
    }
    else
    {
        $('span#contador_de_caracteres_titulo').css('color', 'black');
    }
};

$(window).load(function()
{
    verificaLimiteCaracteres(CKEDITOR.instances.intro);
    // Quando muda o valor do campo intro
    CKEDITOR.instances.intro.on('key', function()
    {
        verificaLimiteCaracteres(CKEDITOR.instances.intro);
    });
});
<?php
}
?>
</script>
