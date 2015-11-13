<link rel="stylesheet" type="text/css" href="../media/css/botoes.css" />
<link rel="stylesheet" type="text/css" href="../media/css/messages.css" />
<link rel="stylesheet" type="text/css" href="../media/enquete/enquete.css" />
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../libs/flot/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="../libs/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="../libs/flot/jquery.flot.pie.js"></script>

<?php
$total_votos_computados = 0;
foreach ( $resultados as $opcao )
{
    $total_votos_computados += $opcao['votos'];
}
?>

<div class="enquete">
    <div class="titulo_enquete">
        <?php echo $enquete['titulo']; ?>
    </div>
    <div class="descricao_enquete">
        <?php echo $enquete['descricao']; ?>
    </div>
    <div class="escolher_grafico">
<?php
if ( $total_votos_computados > 0 )
{
?>
        <a class="button resultados" onclick="exibir_grafico_barras();">Gráfico de barras</a>
        <a class="button resultados2" onclick="exibir_grafico_pizza();">Gráfico de pizza</a>
<?php
    }
?>
        <div style="clear:both;"></div>
    </div>
    <div class="opcoes_enquete">
        <div class="opcoes" id="grafico_resultados" style="width:450px;height:250px;"></div>
        <div class="total_votos">Total de votos até agora: <?php echo $total_votos_computados; ?></div>
    </div>
    <div style="height:25px;"></div>
</div>

<script type="text/javascript" language="JavaScript">
// Labels
var divs_labels = new Array(
<?php
$labels = array();
foreach ( $resultados as $opcao )
{
    $labels[] = "'".$opcao['descricao']."'";
}
echo implode(',', $labels);
?>
);

// Hover do gráfico
$("#grafico_resultados").bind('plothover',function(e,pos,obj)
{
    if ( obj )
    {
        document.getElementById(obj.series.label).style.backgroundColor='#F5F5F5';
        div_em_uso = obj.series.label;
        tirarHover(obj.series.label);
    }
});

function tirarHover(id)
{
    var div_id;
    for ( var i=0; i < divs_labels.length; i++ )
    {
        div_id = divs_labels[i];
        if ( div_id != id )
        {
            document.getElementById(div_id).style.backgroundColor='transparent';
        }
    }
}

function exibir_grafico_barras()
{
    <?php
    $total_votos_computados = 0;
    foreach ( $resultados as $opcao )
    {
        $total_votos_computados += $opcao['votos'];
    }

    $valor_1_voto = (100/$total_votos_computados);
    ?>

    // Dados para o gráfico:
    var dados_grafico =
    [
    <?php
    $cont = 1;
    foreach ( $resultados as $opcao )
    {
        $color = '';
        if ( strlen($opcao['cor']) > 0 )
        {
            $color = 'color: "'.$opcao['cor'].'",  ';
        }
        $porcentagem = round($valor_1_voto*$opcao['votos'], 0);
        echo '{ label: "'.$opcao['descricao'].'", '.$color.'  data: [['.$cont.','.$porcentagem.']]},';
        $cont++;
    }
    ?>
    ];

    // Tricks
    <?php
    $cont = 1;
    $array = array();
    foreach ( $resultados as $opcao )
    {
        $array[] = "[".$cont.", '".$opcao['descricao']."']";
        $cont++;
    }
    $ticks = implode(',', $array);
    ?>

    // Monta o gráfico 
    $.plot($("#grafico_resultados"), dados_grafico,
    {
        series:
        {
            stack: 0,
            lines:
            {
                show: false,
                steps: false
            },
            bars:
            {
                show: true,
                barWidth: 0.8,
                align: 'center'
            },
        },
        xaxis:
        {
            ticks: [<?php echo $ticks; ?>]
        },
        legend:
        {
            show: false
        },
        grid:
        {
            hoverable: true,
            clickable: true
        }
    });
}

function exibir_grafico_pizza()
{
    <?php
    $total_votos_computados = 0;
    foreach ( $resultados as $opcao )
    {
        $total_votos_computados += $opcao['votos'];
    }

    $valor_1_voto = (100/$total_votos_computados);
    ?>
    
    // Dados para o gráfico:
    var dados_grafico =
    [
    <?php
    foreach ( $resultados as $opcao )
    {
        $porcentagem = round($valor_1_voto*$opcao['votos'], 0);
        $color = '';
        if ( strlen($opcao['cor']) > 0 )
        {
            $color = 'color: "'.$opcao['cor'].'", ';
        }
        echo '{ label: "'.$opcao['descricao'].'", '.$color.' data: '.$porcentagem.'},';
    }
    ?>
    ];

    // Monta o gráfico 
    $.plot($("#grafico_resultados"), dados_grafico, 
    {
        series:
        {
            pie:
            { 
                show: true,
                radius: 0.83,
                label:
                {
                    show: true,
                    radius: 1,
                    formatter: function(label, series)
                    {
                        //return '<div style="font-size:8pt;text-align:center;padding:2px;color:black;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
                        return '<div style="font-size:8pt;text-align:center;padding:2px;color:black;">'+Math.round(series.percent)+'%</div>';
                    },
                    /*
                    background:
                    {
                        opacity: 0.5,
                        color: '#000'
                    }
                    */
                }
            }
        },
        legend:
        {
            show: true
        },
        grid:
        {
            hoverable: true,
            clickable: true
        }
    });
}
</script>

<div style="clear:both;"></div>
<div style="height:15px;"></div>
<div style="clear:both;"></div>
<?php
    if ( $botao_voltar != 'f' )
    {
        echo anchor($enquete['pag_enquete'], 'Voltar', 'class="button back" style="float:right;"');
    }
?>

<script type="text/javascript" language="JavaScript">
exibir_grafico_pizza();
</script>
