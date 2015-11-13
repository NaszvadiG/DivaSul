&nbsp;
<?php
// Se tem registros
if ( is_array($registros) && (count($registros) > 0) )
{
    // Exibe registro a registro
    foreach ( $registros as $registro )
    {
?>
        <tr id="linha_id_<?php echo $registro['id']; ?>" class="<?php echo $prefixo_tr; ?>">
        <?php
        // Dependendo a coluna altera o valor da <td></td>
        foreach ( $colunas as $coluna )
        {
            /* Caso $coluna['substituir_valor'] seja um array de valores, substitui o valor
             * Ex.: 
             * $coluna['substituir_valor'] = array(0=>'Zero', 1=>'Um', 2=>'Dois');
             * $registro[$coluna['coluna']] = 1
             * Substitui o 1 por 'Um'
             */
            if ( count($coluna['substituir_valor']) > 0 )
            {
                $registro[$coluna['coluna']] = $coluna['substituir_valor'][$registro[$coluna['coluna']]];
            }

            // Alinhamento default: center
            $coluna['align'] = $coluna['align'] ? $coluna['align'] : 'left';

            // Se tem hierarquia, exibe o 
            if ( $coluna['coluna'] == 'tem_filhos' )
            {
                if ( $registro['tem_filhos'] == 1 )
                {
?>
                <td align="center" width="25" id="expandir_<?php echo $registro['id']; ?>" class="hierarquia">
                <a href="#<?php echo $registro['id']; ?>" onclick="expandir(<?php echo $registro['id']; ?>, <?php echo $nivel; ?>); return false;">+</a>
                </td>
<?php
                }
                else
                {
?>
                <td align="center" width="25" id="expandir_<?php echo $registro['id']; ?>"></td>
<?php
                }
            }
            // Texto
            elseif( !$coluna['oculta'] )
            {
?>
            <td align="<?php echo $coluna['align']; ?>" width="<?php echo $coluna['tamanho']; ?>">
                <?php if ( $coluna['link'] ) {?>
                    <a href="#<?php echo $registro['id']; ?>" onclick="escolher_pai('<?php echo $registro['id']; ?>', this);">
                <?php }?>
                    <?php echo $registro[$coluna['coluna']]; ?>
                <?php if ( $coluna['link'] ) {?>
                    </a>
                <?php }?>
            </td>
<?php
            }
        }
        ?>
        </tr>
<?php
    }
}
?>
&nbsp;
<?php
