<script type="text/javascript">
// Variável que define se será salvo as ordens
var alterado_ordem = false;
</script>

<style type="text/css">
table tbody tr:nth-child(2n-1) td
{
    background-color:transparent;
}

table tbody tr:hover td
{
    background-color:#FFC;
}

div#info
{
    width:100%;
    text-align:center;
}

/* Paginação */
div#voltar,
div#avancar
{
    padding-top:10px;
}
div#paginacao
{
    text-align:center;
    padding-top:10px;
}

.ASC
{
    background-image:url('<?php echo base_url('/arquivos/css/icons/seta_down.png'); ?>');
    background-position:right center;
    background-repeat:no-repeat;
    padding-right:17px;
}

.DESC
{
    background-image:url('<?php echo base_url('/arquivos/css/icons/seta_up.png'); ?>');
    background-position:right center;
    background-repeat:no-repeat;
    padding-right:17px;
}

.linha_busca
{
    background-color:#F5F5F5;
}

.campos_busca
{
    border:1px solid #DEDEDE;
}

.ordenar
{
    width:50px;
}

table tbody tr td.hierarquia a
{
    padding:10px 15px;
}

table tbody tr td.hierarquia a:hover
{
    text-decoration:none;
}
</style>

<!-- Paginação - início -->
<?php
if ( !$desabilitar_paginacao )
{
    $pgAvancar = ($pagina_atual+1);
    $pgVoltar = (($pagina_atual-1) > 0) ? ($pagina_atual-1) :0;
    $irPara = ($tem_mais_paginas ? $pgAvancar :($pgVoltar ? $pgVoltar :''));

    // Ir para página
    $irPara_field = array(
        'name' => 'ir_para',
        'id' => 'ir_para',
        'value' => $irPara,
        'size' => '1',
        'style' => 'width:15px;'
    );
}
?>
<!-- Fim - Paginação -->

<div style="clear:both;"></div>
<h2><?php echo $titulo; ?> - Listar</h2>

<div>
    <?php
    if ( !$desabilitar_inserir )
    {
    ?>
    <div style="float:left;">
        <?php echo anchor($module.'/'.$controller.'/'.$funcao_inserir, '<i class="fa fa-plus-circle"></i> Cadastrar', 'class="btn btn-success" title="Inserir novo registro"'); ?>
    </div>
    <?php
    }
    ?>

    <?php
    if ( !$desabilitar_buscar )
    {
    ?>
    <div style="float:right;">
        <a href="javascript:exibir_busca();" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</a>
    </div>
    <?php
    }
    ?>


    <?php
    if( $botoes )
    {
        foreach ( $botoes as $botao )
        {
            $atributos_html = array();
            foreach ( (array)$botao['atributos_html'] as $key=>$atributo )
            {
                $atributos_html[] = $key.'="'.$atributo.'"';
            }
    ?>  
    <div style="float:right;">
        <a <?php echo implode(' ', $atributos_html); ?>>
            <?php echo $botao['titulo']; ?>
        </a>
    </div>
    <?php    
        }
    }
    ?>
</div>

<!-- Separador -->
<div style="clear:both;"></div>
<div style="height:10px;"></div>

<!-- Separador -->
<div style="clear:both;"></div>
<div style="height:10px;"></div>

<?php
// Informações adicionais
if ( is_array($info) )
{
    $info = implode('<br>', $info);
}

if ( count($filtros) > 0 || strlen($info) > 0 )
{
?>
<div class="modal modal-info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Informação:</h4>
            </div>
            <div class="modal-body">
<p>
<?php
if ( strlen($info) > 0)
{
    echo $info;
}

if ( count($filtros) > 0 && strlen($info) > 0 )
{
    echo '<br><br>';
}

if ( count($filtros) > 0 )
{
?>
Filtrando dados onde:<br />
<?php
    foreach ( $filtros as $k => $fltr )
    {
?>
coluna <b><?php echo $k; ?></b> seja igual a <b><?php echo $fltr; ?></b>;<br />
<?php
    }
}
?>
</p>
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

<!-- Formulário que envia os dados da paginação, ordenação, filtros... -->
<form id="formulario" name="formulario" onsubmit="serializar_formulario(this); return false;">

<table class="lista components">
<thead>
<?php
$html_filtros = '';
$html_colunas = '';
foreach ( (array)$colunas as $coluna )
{
    if( !$coluna['oculta'] )
    {
        // Define umas variáveis
        $icone_coluna = '';
        $coluna['coluna_sql'] = $coluna['coluna_sql'] ? $coluna['coluna_sql'] :$coluna['coluna'];
        $size = !is_null($coluna['tamanho']) ? 'width="'.$coluna['tamanho'].'"' :'';
        if ( !$desabilitar_ordenacao )
        {
            $onclick = $coluna['coluna_sql'] ? 'onclick="ordenar_por(\''.$coluna['coluna_sql'].'\');"' : NULL;
        }
        $cursor = $coluna['coluna_sql'] ? 'style="cursor:pointer;"' : NULL;
        $title = $coluna['coluna_sql'] ? 'title="Ordenar por '.$coluna['descricao'].'"' : NULL;

        // Se for uma coluna "filtrável"
        if ( $coluna['coluna_filtravel'] )
        {
            // Cria um input pra ela
            $atributos = '';
            foreach ( (array)$coluna['atributos_html'] as $atributo => $valor )
            {
                $atributos .= $atributo.'="'.$valor.'" ';
            }
            $html_filtros .= '<td class="linha_busca"><input type="text" name="'.$coluna['coluna_sql'].'" style="width:100%;" class="campos_busca" value="'.$filtros[$coluna['coluna_sql']].'" '.$atributos.'/></td>';
        }
        else
        {
            $html_filtros .= '<td class="linha_busca"></td>';
        }

        if ( $coluna['funcao'] == 'ordenar' )
        {
            $icone_coluna = '<button type="submit" title="Salvar ordem" class="btn" style="background:none;border:none;"><i class="fa fa-save"></i></button>';
        }

        if ( strlen($coluna['align']) == 0 )
        {
            $coluna['align'] = 'left';
        }

        // Armazena em uma variável o cabeçalho da tabela
        if ( $onclick )
        {
            $html_colunas .= '<th '.$size.' class="'.$coluna['tipo_ordem'].'"><a '.$onclick.' '.$cursor.' '.$title. '>'.$coluna['descricao'].'</a>'.$icone_coluna.'</th>';
        }
        else
        {
            $html_colunas .= '<th '.$size.' class="'.$coluna['tipo_ordem'].'" style="text-align:'.$coluna['align'].'">'.$coluna['descricao'].$icone_coluna.'</th>';
        }
    }
}

    // Linha para a busca
    echo '<tr id="campos_busca" style="display:none;">'.$html_filtros.'</tr>';
    // Linha cabeçalho da tabela
    echo '<tr>'.$html_colunas.'</tr>';
?>
    </thead>
    <tbody>
<?php
        $prefixo_tr = '';
        if ( is_array($registros) && count($registros) > 0 )
        {
            $nivel = 0;
            include('default_listar_linha.php');
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
    Nenhum há dados. <?php if(!$desabilitar_inserir){echo anchor($module.'/'.$controller.'/'.$funcao_inserir,'Inserir um novo?','title="Inserir"');}?>
</div>
<?php
}
?>
<!-- Fecha o formulário que envia os dados da paginação, ordenação, filtros... -->
<input type="submit" style="position:absolute;top:-100px;opacity:0;"/>
<?php echo form_close(); ?>

<!-- Paginação - início -->
<div class="row">
    <div id="voltar" class="pull-left col-md-3">
<?php
if ( $pgVoltar > 0 )
{
?>
        <a href="javascript:paginacao(<?php echo $pgVoltar; ?>);" class="btn btn-primary"><i class="glyphicon glyphicon-arrow-left"></i> Voltar</a>
<?php
}
?>
    </div>

<?php
if ( !$desabilitar_paginacao && ( $pgVoltar || $tem_mais_paginas ))
{
?>
    <div id="paginacao" class="pull-left col-md-6">

<?php echo form_open('', array('id' => 'form_ir_para', 'method'=>'POST')); ?>
    Exibindo página <?php echo $pagina_atual; ?> de <?php echo ceil($total_de_registros/$registros_por_pagina); ?>.<br />
    Ir para página <?php echo form_input($irPara_field); ?> <a href="javascript:paginacao($('#ir_para').val());">ir</a>
<?php echo form_close(); ?>
    </div>

    <div id="avancar" class="pull-right text-right col-md-3">
<?php
    if ( $tem_mais_paginas )
    {
?>
        <a href="javascript:paginacao(<?php echo $pgAvancar; ?>);" class="btn btn-primary">Avançar <i class="glyphicon glyphicon-arrow-right"></i></a>
<?php
    }
?>
    </div>
<?php
}
?>
</div>
<!-- Fim - Paginação -->
<div style="clear:both;"></div>
<div style="height:10px;"></div>

<script type="text/javascript">
// Depois de carregada a página, carrega os valores das colunas ajax
$(document).ready(function()
{
    $('.colunas_ajax').each(
        function(count, value)
        {
            var id = value.id.split('coluna_ajax_')[1];
            var funcao = $.trim(value.innerHTML);
            var img = value.innerHTML='<img src="<?php echo site_url('/arquivos/imagens/ajaxload.gif'); ?>" border="0" />';

            $.ajax(
            {
                url:'<?php echo site_url('/'.$module.'/'.$controller)?>/'+funcao+'/'+id,
                success:function(data)
                {
                    value.innerHTML=data;
                }
            });
        }
    );

<?php
    if ( $tem_hierarquia && count($registros) > 0 )
    {
?>
    if ( '<?php echo $registros[0]['id']; ?>'.length > 0 )
    {
        expandir(<?php echo $registros[0]['id']; ?>, 1);
    }
<?php
    }
?>
    definir_acoes();

    // "Ir para"
    $('#form_ir_para').submit(function(event)
    {
        event.preventDefault();
        paginacao($('#ir_para').val());
    });
});

<!-- Ordenar pelas colunas -->
function ordenar_por(coluna)
{
    form = document.getElementById('form_listar');
    form.ordem.value += ','+coluna;
    form.submit();
}

// Paginação
function paginacao(pagina)
{
    form = document.getElementById('form_listar');
    form.ordem.value += ',';
    form.pagina_atual.value = pagina;
    form.submit();
}

// Busca
function exibir_busca()
{
    var campos_busca = document.getElementById('campos_busca');
    if ( campos_busca.style.display == 'none' )
    {
        campos_busca.style.display = 'table-row';
    }
    else
    {
        campos_busca.style.display = 'none';
    }
}

function serializar_formulario(formulario)
{
    form = document.getElementById('form_listar');
    form.ordem.value += ',';
    form.params.value = $(formulario).serialize();
    form.submit();
}

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
	$('#expandir_'+parent_id).html('<img src="<?php echo base_url('/arquivos/imagens/ajaxload.gif');?>" />');

    // Passa de volta pro PHP as variáveis
    var module = '<?php echo urlencode(base64_encode(serialize($module))); ?>';
    var controller = '<?php echo urlencode(base64_encode(serialize($controller))); ?>';
    var colunas = '<?php echo urlencode(base64_encode(serialize($colunas))); ?>';
    var acoes = '<?php echo urlencode(base64_encode(serialize($acoes))); ?>';
    var where = '<?php echo urlencode(base64_encode(serialize($where))); ?>';
    var ordem = '<?php echo urlencode(base64_encode(serialize($ordem))); ?>';
    var view_linha = '<?php echo urlencode(base64_encode(serialize($view_linha))); ?>';
    var funcao_editar = '<?php echo urlencode(base64_encode(serialize($funcao_editar))); ?>';
    var funcao_ativar_inativar = '<?php echo urlencode(base64_encode(serialize($funcao_ativar_inativar))); ?>';

    // Faz o AJAX que retorna as linhas "filhas"
    var params =
    {
        module: module,
        controller: controller,
        parent_id: parent_id,
        colunas: colunas,
        acoes: acoes,
        where: where,
        ordem: ordem,
        nivel: nivel,
        view_linha: view_linha,
        funcao_editar: funcao_editar,
        funcao_ativar_inativar: funcao_ativar_inativar
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

        definir_acoes();
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

function definir_acoes()
{
    <!-- JS da coluna ativo/inativo -->
    $("tr td img.ativo").click(function()
    {
        var img = this;
        var id = img.id.split('ativo_inativo_');
        id = id[1];
        var ativo = img.src.search(/\/published/) >= 0;
        img.src = img.src.replace(/\/[^\/]+\.png/,'/ajaxload.gif');
        $.ajax({
            url:'<?php echo site_url('/'.$module.'/'.$controller.'/'.$funcao_ativar_inativar)?>/'+id,
            success:function(){ img.src = img.src.replace('ajaxload.gif',(ativo?'unpublished.png':'published.png')); }
        });
    });

    <!-- JS da coluna ordem -->
    $("tr td img.ordenar_subir").click(function()
    {
        alterado_ordem = true;

        var img = this;
        var id = img.id;
        var new_order = parseInt(document.getElementById('ordem_'+id).value) + 1;

        document.getElementById('ordem_'+id).value = new_order;
    });

    $("tr td img.ordenar_descer").click(function()
    {
        alterado_ordem = true;

        var img = this;
        var id = img.id;
        var new_order = parseInt(document.getElementById('ordem_'+id).value) - 1;

        document.getElementById('ordem_'+id).value = new_order;
    });
}   
</script>

<!-- JavaScript adicional -->
<?php echo $js; ?>

<?php echo form_open($module.'/'.$controller.'/'.$function, array('id' => 'form_listar', 'method'=>'POST')); ?>
<input type="hidden" id="ordem" name="ordem" value="<?php echo $ordem; ?>"/>
<input type="hidden" id="pagina_atual" name="pagina_atual" value="<?php echo $pagina_atual; ?>"/>
<input type="hidden" id="params" name="params" value="<?php echo $params; ?>"/>
<?php echo form_close(); ?>
