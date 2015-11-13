<link rel="stylesheet" type="text/css" href="../media/css/botoes.css" />
<link rel="stylesheet" type="text/css" href="../media/css/messages.css" />
<link rel="stylesheet" type="text/css" href="../media/enquete/enquete.css" />

<?php
if ( is_array($erro) && count($erro) > 0 )
{
    echo '<div class="msg erro">';
    foreach ( $erro as $err )
    {
        echo $err.'<br />';
    }
    echo '</div>';
}
?>

<?php
if ( is_array($info) && count($info) > 0 )
{
    echo '<div class="msg info">';
    foreach ( $info as $inf )
    {
        echo $inf.'<br />';
    }
    echo '</div>';
}
?>

<div class="enquete">
    <?php echo form_open('', array('id' => 'form_enquete_'.$enquete['id'], 'method'=>'POST')); ?>
    <div class="titulo_enquete">
        <?php echo $enquete['titulo']; ?>
    </div>
    <div class="descricao_enquete">
        <?php echo $enquete['descricao']; ?>
    </div>
    <div class="opcoes_enquete">
        <div class="opcoes">
<?php
    foreach ( $opcoes as $opcao )
    {
        echo '<div class="opcao">';

        $dis = '';
        if ( $disabled )
        {
            $dis = ' disabled="true"';
        }
        if ( $enquete['multipla_resposta'] == 't' )
        {
            echo form_checkbox('resposta[]', $opcao['id'], false, 'id="opcao_'.$opcao['id'].'"'.$dis);
        }
        else
        {
            echo form_radio('resposta', $opcao['id'], false, 'id="opcao_'.$opcao['id'].'"'.$dis);
        }

        echo '<label for="opcao_'.$opcao['id'].'">'.$opcao['descricao'].'</label>';

        echo '</div>';
    }
?>
        </div>
    </div>
    <div style="height:25px;"></div>
    <div class="botoes">
        <?php
            if ( !$disabled )
            {
                echo form_submit('votar', 'Votar!', 'class="button votar"');
            }
        ?>
        <?php
            if ( $enquete['pag_resultados'] != 'false' )
            {
                echo anchor($enquete['pag_resultados'], 'Resultados', 'class="button resultados"');
            }
        ?>
    </div>
    <?php echo form_close(); ?>
</div>
