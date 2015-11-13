<div style="clear:both"></div>
<?php
if( strlen($pagina['content_last']) > 0 )
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
        url:'<?php echo site_url('paginas/get_last_content'); ?>/<?php echo $pagina['id']; ?>',
        success:function(data)
        {
            document.getElementById('page').value = data;
        }
    });
};
</script>

<div style="clear:both"></div>
<?php
$inserir_editar = ' - Inserir';
if ( strlen($pagina['id']) > 0 )
{
    $inserir_editar = ' - Editar';
}
?>
<h2>Página <?=$inserir_editar;?></h2>

<?php echo form_open(''); ?>
<input type="hidden" name="pagina[parent_id]" value="<?php echo $pagina['parent_id']?>" id="parent_id" />
<input type="hidden" name="pagina[id]" value="<?php echo $pagina['id']; ?>" />
<?php
if ( strlen($erro) > 0 )
{
    echo '<div class="msg error">'.$erro.'</div>';
}
?>
<table class="form">
    <tbody>
<?php
if ( strlen($pagina['id']) > 0 )
{
?>
        <tr>
            <th>Código</th>
            <td>
                <?php echo $pagina['id']; ?>
            </td>
        </tr>
<?php
}
?>

        <tr>
            <th>Título Menu</th>
            <td><?php echo form_input('pagina[titulo]', $pagina['titulo'], 'size="60" id="title"'); ?></td>
        </tr>

        <tr>
            <th>Título Conteúdo</th>
            <td><?php echo form_input('pagina[titulo_conteudo]', $pagina['titulo_conteudo'], 'size="80" id="titulo_conteudo"'); ?></td>
        </tr>

        <tr>
            <th>Url da Página</th>
            <td><?php echo form_input('pagina[url]', $pagina['url'], 'size="60" id="url"'); ?></td>
        </tr>

        <tr>
            <th>Nota</th>
            <td><?php echo form_input('pagina[note]', $pagina['note'], 'size="60" id="note"'); ?></td>
        </tr>

        <tr>
            <th>Menu</th>
            <td><?php echo form_dropdown('pagina[menu_id]', $menus, $pagina['menu_id']); ?></td>
        </tr>

<?php
if ( (strlen($pagina['id']) == 0) || (strlen($pagina['url']) > 0) )
{
    $parent_data = '';
    if ( (strlen($parent['titulo']) > 0) )
    {
        $parent_data = '<span id="parent_title">'.$parent['titulo'].'</span><br />'.
                       '<small id="parent_url">'.$parent['url'].'</small>';
    }
?>
        <tr>
            <th>Pai</th>
            <td>
                <span style="float:left;margin-right:10px;">
                    <span id="parent_title"><?php echo $parent_data; ?></span>
                </span>
                <a href="#" id="set_parent">Buscar</a>
            </td>
        </tr>
<?php
}
?>

        <tr>
            <th>Tipo</th>
            <td><?php echo form_dropdown('pagina[tipo]', $tipos, $pagina['tipo'], 'id="tipo"'); ?></td>
        </tr>

        <tr>
            <th>Conteúdo</th>
            <td id="content_container"><?php echo $content_container;?></td>
        </tr>

        <tr>
            <th>Template</th>
            <td><?php echo form_dropdown('pagina[template_id]', $templates, $pagina['template_id']); ?></td>
        </tr>

        <tr>
            <th>Ordem</th>
<?php
if ( strlen($pagina['ordem']) == 0 )
{
    $pagina['ordem'] = '10';
}
?>
            <td><?php echo form_input('pagina[ordem]', $pagina['ordem'], 'size="10"'); ?></td>
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
                <?=form_dropdown('pagina[ativo]', $options, $pagina['ativo']);?>
            </td>
        </tr>

<?php
if ( strlen($pagina['content']) > 0 )
{
?>
        <tr>
            <th>Baixar</th>
            <td><input type="button" value="Baixar pagina" class="button baixar" onclick="baixar_pagina();" /></td>
        </tr>
<?php
}
?>

        <tr class="limpo">
            <th></th>
            <td>
                <?php echo form_submit('submit', 'Salvar', 'class="button ok"'); ?>
                <?php echo anchor('paginas', 'Cancelar', 'class="button cancel"'); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php echo form_close(); ?>

<script type="text/javascript">
// Quando altera-se o tipo de página
$('#type').change(function()
{
    $("#content_container").load("<?php echo site_url('paginas/ajax_obter_content_container')?>/"+this.value);
});

// Quando escolhe a página pai
$("#set_parent").click(function(){escolhePai();});
var div_fundo_escuro, div_popup, iframe;
function escolhePai()
{
    if ( !div_popup )
    {
        div_fundo_escuro = document.createElement('div');
        div_fundo_escuro.setAttribute('id', 'popupbg');
        // Quando clica no fundo escuro fecha o popup
        div_fundo_escuro.setAttribute('onclick', 'fecha_frame()');
        div_popup = document.createElement('div');
        div_popup.setAttribute('id', 'popupcentro');
        div_escolher_pagina_pai = document.createElement('div');
        div_escolher_pagina_pai.setAttribute('id', 'div_listagem_de_paginas');
        div_escolher_pagina_pai.setAttribute('style', 'height:400px;');
        div_popup.appendChild(div_escolher_pagina_pai);
        document.body.appendChild(div_fundo_escuro);
        document.body.appendChild(div_popup);
        $('div#div_listagem_de_paginas').html('<img src="<?php echo base_url('arquivos/imagens/ajaxload.gif');?>"/>');

        $.ajax(
        {
            url:'<?php echo site_url('paginas/ajax_set_parent')?>',
            success:function(tabela)
            {
                $('div#div_listagem_de_paginas').html(tabela);
            }
        });
    }
}

// ESC => fecha popup
$(document).keydown(function(e){ if(e.keyCode==27){fecha_frame();}});

<?php
// Se não possui ID (inserção)
if ( empty($pagina['id']) )
{
?>
// Ao sair do campo título, preenche titulo_conteudo e url(url)
    $('#title').blur(function()
    {
        $('#titulo_conteudo').val(this.value);
        $('#url').val(generate_url(this.value));
    });
<?php
}
?>

// Define parent ID
function escolher_pai(parent_id, elemento)
{
    document.getElementById('parent_id').value = parent_id;
    document.getElementById('parent_title').innerHTML = $(elemento).html();
    fecha_frame();
}

// Fecha a janela de seleção de parent ID
function fecha_frame()
{
    document.body.removeChild(div_fundo_escuro);
    document.body.removeChild(div_popup);
    div_popup = null;
}

// Gera a url
function generate_url(valor)
{
    valor = valor.replace(/^\s+|\s+$/g, '');
    valor = valor.replace(/Æ|ä|æ/g, 'AE');
    valor = valor.replace(/À|Á|Â|Ã|Ä|Å|à|á|â|ã|ä|å|ª/g, 'a');
    valor = valor.replace(/Ç|ç/g, 'c');
    valor = valor.replace(/Ð|ð/g, 'd');
    valor = valor.replace(/È|É|Ê|Ë|è|é|ê|ë/g, 'e');
    valor = valor.replace(/Ì|Í|Î|Ï|ì|í|î|ï/g, 'i');
    valor = valor.replace(/Ñ|ñ/g, 'n');
    valor = valor.replace(/Ò|Ó|Ô|Õ|Ö|Ø|ò|ó|ô|õ|ö|ø|º/g, 'o');
    valor = valor.replace(/Ù|Ú|Û|Ü|ù|ú|û|ü/g, 'u');
    valor = valor.replace(/Ý|ý|ÿ/g, 'y');
    valor = valor.replace(/ß/g, 'ss');
    valor = valor.replace(/[^a-zA-Z0-9_-]/g, '-');
    valor = valor.replace(/--+/g, '-');
    valor = valor.toLowerCase();
    return valor;
}

function baixar_pagina()
{
    window.open('<?php echo site_url('paginas/baixar_html_pagina/'.$pagina['id']);?>');
}
</script>