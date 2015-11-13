<script type="text/javascript" language="JavaScript">
// Contador
var count_respostas=0;

/**
 * Função que adiciona mais um campo resposta
 */
function adicionar_resposta(id_div_campos, resposta, cor)
{
    // Contador de campos (usado nos IDs)
    count_respostas++;

    // Default value
    resposta = (resposta) ? resposta : '';
    cor = (cor) ? cor : '';

    // Obtém a div
    var div_campos = document.getElementById(id_div_campos);
    var elements_campos = div_campos.getElementsByTagName('*');

    // Cria uma div pros campos
    var div_resposta = document.createElement('div');
    div_resposta.setAttribute('id', 'div_resposta_'+count_respostas);
    // Adiciona ela
    div_campos.insertBefore(div_resposta, elements_campos[elements_campos.length]);
    // Obtém ela para adicionar os campos
    var div_campo_resposta = document.getElementById('div_resposta_'+count_respostas);
    var elements_resposta = div_campo_resposta.getElementsByTagName('*');

    // Campo resposta
    div_campo_resposta.innerHTML += count_respostas+' - ';
    var campo_resposta = document.createElement('input');
    campo_resposta.setAttribute('type', 'text');
    campo_resposta.setAttribute('id', 'resposta_'+count_respostas);
    campo_resposta.setAttribute('name', 'respostas[descricao][]');
    campo_resposta.setAttribute('size', '60');
    campo_resposta.setAttribute('value', resposta);

    // Campo cor
    var campo_cor = document.createElement('input');
    campo_cor.setAttribute('type', 'text');
    campo_cor.setAttribute('id', 'resposta_cor_'+count_respostas);
    campo_cor.setAttribute('name', 'respostas[cor][]');
    campo_cor.setAttribute('size', '1');
    campo_cor.setAttribute('readonly', 'true');
    campo_cor.setAttribute('value', cor);
    if ( cor )
    {
        campo_cor.setAttribute('style', 'background:'+cor+';color:'+cor+';');
    }

    // Adiciona o campo titulo
    div_campo_resposta.insertBefore(campo_resposta, elements_resposta[elements_resposta.length]);
    div_campo_resposta.insertBefore(campo_cor, elements_resposta[elements_resposta.length]);

    // Adiciona o botão remover
    if ( count_respostas > 1 )
    {
        // Se for maior que dois começa a "esconder" o X do campo acima
        if ( count_respostas > 2 )
        {
            document.getElementById('remover_'+(count_respostas-1)).style.display='none';
        }

        var remover = document.createElement('span');
        remover.innerHTML = ' <a id="remover_'+count_respostas+'" href="javascript:remover_resposta(\''+id_div_campos+'\');void(0);" title="Remover uma opção"><img src="/cms/css/icons/delete.png"/></a>';
        div_campo_resposta.insertBefore(remover, elements_resposta[elements_resposta.length]);
    }

    // Adiciona quebra de linha após o campo de resposta
    div_campo_resposta.insertBefore(document.createElement('br'), elements_resposta[elements_resposta.length]);

    // Adiciona quebra de linha após a div da opção de resposta
    div_campo_resposta.insertBefore(document.createElement('br'), elements_campos[elements_campos.length]);

    // Ativa os seletores de cor
    ativa_paleta_de_cor(count_respostas);
}

function ativa_paleta_de_cor(count)
{
    $('#resposta_cor_'+count).ColorPicker(
    {
        onShow: function(colpkr)
        {
            $(colpkr).fadeIn(500);
            return false;
        },
        onHide: function(colpkr)
        {
            $(colpkr).fadeOut(500);
            return false;
        },
        onChange: function(hsb, hex, rgb)
        {
            document.getElementById('resposta_cor_'+count).value='#'+hex;
            $('#resposta_cor_'+count).css('color', '#'+hex);
            $('#resposta_cor_'+count).css('backgroundColor', '#'+hex);
            document.getElementById('respostas_com_cores_personalizadas').checked=true;
        }
    });
}

function remover_resposta(id_div_campos)
{
    // Obtém a div
    var div_campo_resposta = document.getElementById(id_div_campos);
    var elements_campos = div_campo_resposta.getElementsByTagName('div');
    div_campo_resposta.removeChild(elements_campos[elements_campos.length-1]);

    // Se for maior que dois começa a "mostrar" o X do campo acima
    if ( count_respostas > 2 )
    {
        document.getElementById('remover_'+(count_respostas-1)).style.display='inline';
    }

    count_respostas--;
}
</script>

<div id="div_campo_resposta_respostas"></div>
<a href="javascript:adicionar_resposta('div_campo_resposta_respostas');" class="button add" style="background-color:#E5E5E5;">Adicionar mais uma opção</a>

<script type="text/javascript" language="JavaScript">
<?php
$total = count($respostas);
if ( $total > 0 )
{
    foreach ( $respostas as $resposta )
    {
?>
        adicionar_resposta(
            'div_campo_resposta_respostas', 
            '<?=$resposta['descricao'];?>',
            '<?=$resposta['cor'];?>');
<?php
    }
}
else
{
?>
        adicionar_resposta('div_campo_resposta_respostas');
<?php
}
?>
</script>
