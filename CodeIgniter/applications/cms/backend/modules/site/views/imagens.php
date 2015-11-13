<script src="<?php echo site_url('../cms/arquivos/js/arquivos.js')?>"></script>

<script type="text/javascript">
<?php
$path = urlencode(base64_encode($arquivos_path));
?>
$(function()
{
    $('#file_upload').uploadify(
    {
        uploader        : '<?php echo site_url('../arquivos/libs/uploadify/uploadify.php?path='.$path); ?>',
        swf             : '<?php echo site_url('../arquivos/libs/uploadify/uploadify.swf'); ?>',
        cancelImg       : '<?php echo site_url('../arquivos/libs/uploadify/cancel.png'); ?>',
        buttonText      : ' Selecionar arquivos ',
        //fileExt         : '*.JPEG;*.JPG;*.jpeg;*.jpg;*.GIF;*.gif;*.PNG;*.png',
        fileDesc        : 'Arquivos',
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
        onQueueComplete : function(event, data)
        {
            location.reload();
        },
        onSelectError : function()
        {
            alert('Lamento, mas não foi possível selecionar este arquivo. Tente novamente mais tarde ou contate o administrador.');
        }
    });
});
</script>

<style type="text/css">
.remover_arquivo
{
    margin-top:-15px;
    margin-right:4px;
}
</style>

<h2>Arquivos</h2>

<?php
foreach ( $remover_em_lote as $remover )
{
    if ( !empty($remover['arquivo']['sucesso']) )
    {
        echo '<div class="msg info">Sucesso ao remover o(s) arquivo(s):<br />'.implode('<br />', $remover['arquivo']['sucesso']).'</div>';
    }
    if ( !empty($remover['pasta']['sucesso']) )
    {
        echo '<div class="msg info">Sucesso ao remover a(s) pasta(s):<br />'.implode('<br />', $remover['pasta']['sucesso']).'</div>';
    }
    if ( !empty($remover['arquivo']['falha']) )
    {
        echo '<div class="msg error">Falha ao remover o(s) arquivo(s):<br />'.implode('<br />', $remover['arquivo']['falha']).'</div>';
    }
    if ( !empty($remover['pasta']['falha']) )
    {
        echo '<div class="msg error">Falha ao remover a(s) pasta(s):<br />'.implode('<br />', $remover['pasta']['falha']).'</div>';
    }
}
?>

<div id="tudo">
    <?php if ( strlen($txt) > 0 ) { ?>
    <div class="msg">
        <?php echo $txt; ?>
    </div>
    <?php } ?>
    <br />
    <?php echo $breadcrumbs; ?>

    <div id="divFora">
        <div id="padrao" style="display:block;min-width:920px;width:100%;border-top:1px solid #CCCCCC;padding-top:2px"><br>
            <div id="multi_upload">
                <input type="file" id="file_upload" name="file_upload" />
                <input type="submit" value="Enviar arquivos" class="button baixar" onclick="javascript:$('#file_upload').uploadifyUpload();" style="width:120px;display:none"/>
            </div>
            <div id="single_upload" style="display:none;">
                <?php echo form_open_multipart('', array('name'=>'enviarPadrao')); ?>
                    <label for="arquivo">Escolha um arquivo:</label><?php echo form_upload(array('id'=>'arquivo', 'name'=>'arquivo')); ?><br />
                    <input type="submit" value="Enviar arquivo" class="button baixar"/>
                <?php echo form_close(); ?>
            </div>

            <div style="float:right;">
                <b>Ações em lote:</b> <a href="javascript:remover();">Excluir arquivos selecionados</a>
            </div>
            <br />
            <table id="arquivos" style="text-align:center;border-top:1px solid #CCCCCC;" width="100%">
                <tr class="fundo">
                    <th width="130">Ação</th>
                    <th>Nome</th>
                    <th width="70">Tamanho</th>
                    <th width="150">Modificado em</th>
                    <th width="70">Permissão</th>
                    <th width="70">Usuário</th>
                </tr>
<?php
$i = 1;
if ( is_array($dirs) && count($dirs) > 0 )
{
    foreach ( $dirs as $dir )
    {
        $owner = '-';
        if ( function_exists('posix_getpwuid') )
        {
            $ownerinfo = posix_getpwuid(fileowner($arquivos_path.$dir));
            $owner = $ownerinfo['name'];
        }
?>
                <tr onmouseover="over(this);" onmouseout="out(this);">
                    <td style="text-align:left;">
                        <input type="checkbox" name="remover[]" id="remover_diretorio_<?php echo $i; ?>" value="<?php echo $arquivos_path.$dir; ?>" class="remover_arquivo">
                        <a onclick="apagar('<?php echo urlencode(base64_encode(serialize($arquivos_path.$dir))); ?>', '<?php echo $dir; ?>', this);" style="cursor:pointer;">
                            <img src="<?php echo site_url('arquivos/css/icons/remove.png'); ?>" alt="apagar" title="Apagar diretorio e todos os seus arquivos"/>
                        </a>
                        <a onclick="renomear('<?php echo $dir; ?>');" style="cursor:pointer;">
                          <img src="<?php echo site_url('arquivos/css/icons/rename.png'); ?>" alt="renomear" title="Renomear diretorio"/>
                        </a>
                        <a onclick="copiar('<?php echo $dir; ?>');" style="cursor:pointer;">
                            <img src="<?php echo site_url('arquivos/css/icons/copy.png'); ?>" alt="copiardiretorio" title="Fazer uma cópia desta diretorio"/>
                        </a>
                        <a id="<?php echo $dir; ?>" href="<?php echo site_url('site/imagens/index/'.$current_dir.$dir); ?>" style="cursor:pointer;">
                            <img src="<?php echo site_url('arquivos/css/icons/folder.png'); ?>" alt="ver" title="Entrar na diretorio"/>
                        </a>
                    </td>
                    <td style="text-align:left;">
                        <?php echo $dir; ?>
                    </td>
                    <td>
                        <?php echo (filesize($arquivos_path.$dir)/1024).'Kb'; ?>
                    </td>
                    <td>
                        <?php echo date('Y-m-d H:i:s', filemtime($arquivos_path.$dir)); ?>
                    </td>
                    <td>
                        <?php echo substr(sprintf('%o', fileperms($arquivos_path.$dir)), -4); ?>
                    </td>
                    <td>
                        <?php echo $owner; ?>
                    </td>
                </tr>
<?php
        $i++;
    }
}

if ( is_array($arquivos) && count($arquivos) > 0 )
{
    foreach ( $arquivos as $arquivo )
    {
        $owner = '-';
        if ( function_exists('posix_getpwuid') )
        {
            $ownerinfo = posix_getpwuid(fileowner($arquivos_path.$arquivo));
            $owner = $ownerinfo['name'];
        }
?>
                <tr onmouseover="over(this);" onmouseout="out(this);">
                    <td style="text-align:left;">
                        <input type="checkbox" name="remover[]" id="remover_arquivo_<?php echo $i; ?>" value="<?php echo $arquivos_path.$arquivo; ?>" class="remover_arquivo">
                        <a onclick="apagar('<?php echo urlencode(base64_encode(serialize($arquivos_path.$arquivo))); ?>', '<?php echo $arquivo; ?>', this);" style="cursor:pointer;">
                            <img src="<?php echo site_url('arquivos/css/icons/remove.png'); ?>" alt="apagar" title="Apagar arquivo"/>
                        </a>
                        <a onclick="renomear('<?php echo $arquivo; ?>');" style="cursor:pointer;">
                          <img src="<?php echo site_url('arquivos/css/icons/rename.png'); ?>" alt="renomear" title="Renomear arquivo"/>
                        </a>
                        <a onclick="copiar('<?php echo $arquivo; ?>');" style="cursor:pointer;">
                            <img src="<?php echo site_url('arquivos/css/icons/copy.png'); ?>" alt="copiararquivo" title="Fazer uma cópia deste arquivo"/>
                        </a>
                        <a href="<?php echo $arquivos_url.$current_dir.$arquivo; ?>" target="_blank" style="cursor:pointer;">
                            <img src="<?php echo site_url('arquivos/css/icons/view.png'); ?>" alt="abrir" title="Visualizar arquivo"/>
                        </a>
<?php
                    //verifica se arquivo .css|.txt|.html|.js|.json
                    if ( in_array(array_pop(explode('.', $arquivo)), array('css', 'js', 'txt', 'html', 'json')) )
                    {
?>
                        <a href="<?php echo site_url('site/imagens/editar?file='.base64_encode($arquivos_path.$arquivo)); ?>"  style="cursor:pointer;">
                            <img src="<?php echo site_url('arquivos/css/icons/edit.png'); ?>" alt="abrir" title="Editar arquivo"/>
                        </a>
<?php
                    }
?>
                    </td>
                    <td style="text-align:left;">
                        <?php echo $arquivo; ?>
                    </td>
                    <td>
                        <?php echo round(filesize($arquivos_path.$arquivo)/1024, 2).'Kb'; ?>
                    </td>
                    <td>
                        <?php echo date('Y-m-d H:i:s', filemtime($arquivos_path.$arquivo)); ?>
                    </td>
                    <td>
                        <?php echo substr(sprintf('%o', fileperms($arquivos_path.$arquivo)), -4); ?>
                    </td>
                    <td>
                        <?php echo $owner; ?>
                    </td>
                </tr>
<?php
        $i++;
    }
}
?>
            </table>

            <?php echo count($dirs).' pastas e '.count($arquivos).' arquivos.'; ?>
            <div style="display:none">
                <?php echo form_open('', 'id="hiddenform"'); ?>
                    <input type="text" id="action" name="action" />
                    <input type="submit" />
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function remover()
{
    // Obtem o form
    var form = document.getElementById('hiddenform');

    var item = '';
    var quantidade_itens = document.getElementsByName('remover[]').length;

    for ( var i=0; i < quantidade_itens; i++ )
    {
        var itens = document.getElementsByName('remover[]');
        item = itens[i];

        if ( item.checked )
        {
            // Obtem elementos do form
            var elements = form.getElementsByTagName('*');

            // Adiciona um campo
            var campo = document.createElement('input');
            campo.setAttribute('type', 'text');
            campo.setAttribute('name', 'remover[]');
            campo.setAttribute('value', item.value);

            // Adiciona o campo
            form.insertBefore(campo, elements[elements.length]);
        }
    }

    form.submit();
}
</script>