<style type="text/css">
table tbody tr:nth-child(2n-1) td
{
    background-color:transparent;
}

table tbody tr:hover td
{
    background-color:#FFC;
}

table tbody tr td.hierarquia a
{
    padding:10px 15px;
}

table tbody tr td.hierarquia a:hover
{
    text-decoration:none;
}

div#div_popup
{
    width:807px;
    height:323px;
    overflow-x:auto;
}
</style>

<div style="clear:both;"></div>
<h2><?php echo $titulo; ?> - Listar</h2>

<!-- Separador -->
<div style="clear:both;"></div>
<div style="height:10px;"></div>

<table>
    <thead>
<?php
$html_colunas = '';
foreach ( (array)$colunas as $coluna )
{
    if( !$coluna['oculta'] )
    {
        $size = !is_null($coluna['tamanho']) ? 'width="'.$coluna['tamanho'].'"' :'';
        $html_colunas .= '<th '.$size.' class="'.$coluna['tipo_ordem'].'">'.$coluna['descricao'].'</th>';

    }
}
    // Linha cabeçalho da tabela
    echo '<tr>'.$html_colunas.'</tr>';
?>
    </thead>
</table>
<div id="div_popup">
<table>
    <tbody id="div_popup">
<?php
        $prefixo_tr = '';
        if ( is_array($registros) && count($registros) > 0 )
        {
            $nivel = 0;
            include('paginas_selecao_pagina_pai_linha.php');
?>
    </tbody>
</table>
<?php
        }
        else
        {
?>
    </tbody>
</table>
Nenhum registro.
</div>
<?php
}
?>
<!-- Fim - Paginação -->
<div style="clear:both;"></div>
<div style="height:10px;"></div>

<script type="text/javascript">
// Depois de carregada a página, carrega os valores das colunas ajax
$(document).ready(function()
{
    if ( '<?php echo $registros[0]['id']; ?>'.length > 0 )
    {
        expandir(<?php echo $registros[0]['id']; ?>, 1);
    }
});

/**
 * Função que exibe as páginas "filhas"
 * @param parent_id ID da página pai
 * @param nivel Quantidade de |-
 */
function expandir(parent_id, nivel)
{
    // Obtém a linha do parent_id
    var tr = $('#linha_id_'+parent_id);

    // Coloca o loading no lugar do "+"
    $('#expandir_'+parent_id).html('<img src="<?php echo base_url('arquivos/imagens/ajaxload.gif');?>" />');

    // Passa de volta pro PHP as variáveis
    var module = '<?php echo urlencode(base64_encode(serialize($module))); ?>';
    var controller = '<?php echo urlencode(base64_encode(serialize($controller))); ?>';
    var colunas = '<?php echo urlencode(base64_encode(serialize($colunas))); ?>';
    var where = '<?php echo urlencode(base64_encode(serialize($where))); ?>';
    var ordem = '<?php echo urlencode(base64_encode(serialize($ordem))); ?>';
    var view_linha = '<?php echo urlencode(base64_encode(serialize($view_linha))); ?>';

    // Faz o AJAX que retorna as linhas "filhas"
    var params =
    {
        module: module,
        controller: controller,
        parent_id: parent_id,
        colunas: colunas,
        where: where,
        nivel: nivel,
        ordem: ordem,
        view_linha: view_linha
    };
    $.post('<?php echo site_url('/'.$module.'/'.$controller)?>/obter_filhos/', params, function(nova_tr)
    {
        // Acrescenta linha
        tr.after(nova_tr);
        // Tira o loading no lugar do "+"
        $('#expandir_'+parent_id).html('<a href="#" onclick="ocultar_filhos('+parent_id+', '+nivel+', this)">-</a>');

        var title_td = false;
        // Procura as <td> de titulo
        $('.sub_'+parent_id+' td').each(function()
        {
            var parent_id = $(this).parent().attr('class').substring(4);

            // Adiciona |- 
            if ( title_td )
            {
                // Coloca os |-
                var identacao = '';
                for ( var i=0; i < nivel; i++ )
                {
                    identacao += '<span style="display:block;float:left; margin-right:5px;color:#ddd;height:30px;">&#124;&#8212;</span>';
                }

                // Adiciona |- na frente do titulo
                var html = $(this).html();
                $(this).html(identacao+html);
            }

            // Encontra a div do "+". Então a próxima terá o "|-"
            if ( strpos($(this).attr('id'), 'expandir_') !== false )
            {
                title_td = true;
            }
            else
            {
                title_td = false;
            }
        })
    });
}

function strpos(haystack, needle, offset)
{
    var i = (haystack+'').indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
}

function ocultar_filhos(parent_id, nivel, elemento)
{
    $('tr.sub_'+parent_id).each(function()
    {
        $(this).hide();
    });

    $('#expandir_'+parent_id).html('<a href="#" onclick="expandir('+parent_id+', '+nivel+');return false;">+</a>');
}
</script>