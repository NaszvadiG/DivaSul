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
        foreach ( (array)$colunas as $coluna )
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
            // Coluna ordem
            elseif ( $coluna['funcao'] == 'ordenar' )
            {
        ?>
            <td align="center" width="120">
                <input type="text" id="ordem_<?php echo $registro['id']; ?>" name="ordem_<?php echo $registro['id']; ?>" value="<?php echo $registro['ordem']; ?>" class="ordenar" style="width:30px;" onkeyup="this.value=retornaSomenteNumeros(this.value);" onchange="this.value=retornaSomenteNumeros(this.value);alterado_ordem=true;"/>
                <img id="<?php echo $registro['id']; ?>" src="<?php echo base_url('arquivos/css/icons/up.png'); ?>" alt="+" title="+" border="0" style="cursor:pointer;width:20px;" class="ordenar_subir"/>
                <img id="<?php echo $registro['id']; ?>" src="<?php echo base_url('arquivos/css/icons/down.png'); ?>" alt="-" title="-" border="0" style="cursor:pointer;width:20px;" class="ordenar_descer"/>
            </td>
        <?php
            }

            // Coluna ativo
            elseif ( $coluna['funcao'] == 'ativo_inativo' )
            {
                $icone_ativo = 'sem_status.png';
                if ( $registro[$coluna['coluna']] == 1 )
                {
                    $icone_ativo = 'published.png';
                }
                elseif ( $registro[$coluna['coluna']] == 0 )
                {
                    $icone_ativo = 'unpublished.png';
                }
        ?>
            <td align="center">
                <img id="ativo_inativo_<?php echo $registro['id']; ?>" src="<?php echo base_url('arquivos/css/icons/'.$icone_ativo); ?>" alt="<?php echo $registro['ativo']; ?>" border="0" style="cursor:pointer;" class="ativo"/>
            </td>
        <?php
            }

            // Coluna ações
            elseif ( $coluna['funcao'] == 'acoes' )
            {
        ?>
            <td align="center">
                <ul class="actions">
        <?php
                foreach ( $acoes as $acao )
                {
                    $onclick = $acao['onclick'] ? $acao['onclick'] : '';

                    //atributos html
                    $html_attributes = array();
                    // title
                    $html_attributes['title'] = $acao['descricao'];
                    // OnClick
                    if ( strlen($onclick) > 0 )
                    {
                        $html_attributes['onclick'] = $onclick;
                    }
                    // target do link
                    if ( strlen($acao['target']) > 0 )
                    {
                        $html_attributes['target'] = $acao['target'];
                    }

                    $link = $module.'/'.$controller.'/'.$acao['acao'].'/'.$registro['id'];
                    $image = '<img width="16px" src="'.base_url($acao['icone']).'" alt="'.$acao['acao'].'" />';
        ?>
                    <li><?php echo anchor($link, $image, $html_attributes); ?></li>
        <?php
                }
        ?>
                </ul>
            </td>
        <?php
            }
            // Coluna personalizada
            elseif ( isset($coluna['html']) )
            {
        ?>
                <td class="html_id_<?php echo $registro['id']; ?>" align="<?php echo $coluna['align']; ?>" style="<?php echo $coluna['style']; ?>">
                    <?php echo $registro[$coluna['coluna']]; ?>
                    <?php echo str_replace('#id#', $registro['id'], $coluna['html']); ?>
                </td>
        <?php
            }
            // Coluna personalizada (AJAX)
            elseif ( isset($coluna['ajax']) )
            {
        ?>
                <td class="colunas_ajax" id="coluna_ajax_<?php echo $registro['id']; ?>" align="<?php echo $coluna['align']; ?>" style="<?php echo $coluna['style']; ?>">
                    <?php echo $coluna['ajax']; ?>
                </td>
        <?php
            }
            // Link para edição na coluna
            elseif( $coluna['linkar_para_edicao'] )
            {
        ?>
            <td align="<?php echo $coluna['align']; ?>">
            <?php
                $url = base_url($module.'/'.$controller.'/'.$funcao_editar.'/'.$registro['id']);
            ?>
                <a href="<?php echo $url; ?>" title="Editar registro"><?php echo $registro[$coluna['coluna']]; ?></a>
            </td>
        <?php
            }
            // Personalizado
            elseif( (strlen($coluna['concatenar_com']) > 0) && 
                    (($coluna['condicao_concatenar'] == '=') && ($registro[$coluna['coluna']] == $coluna['comparar_com'])) )
            {
                if ( strlen($coluna['passar_funcao']) > 0 )
                {
                    $concat = call_user_func($coluna['passar_funcao'], $registro[$coluna['concatenar_com']]);
                    if ( is_array($concat) )
                    {
                        $concat = implode('/', $concat);
                        if ( substr($concat, -1) == '/' )
                        {
                            $concat = substr($concat, 0, -1);
                        }
                    }
                    $concat = '('.$concat.')';
                }
        ?>
            <td align="<?php echo $coluna['align']; ?>"><?php echo $registro[$coluna['coluna']].$concat; ?></td>
        <?php
            }
            elseif ( is_array($coluna['passar_funcoes']) && count($coluna['passar_funcoes']) > 0 )
            {
                $value = preg_replace('/\&[^;]*;/', '', $registro[$coluna['coluna']]);
                $value = trim(preg_replace('/<[^>]*>/', '', $registro[$coluna['coluna']]));
                foreach ( $coluna['passar_funcoes'] as $funcao=>$params )
                {
                    foreach ( $params as $k=>$param )
                    {
                        $params[$k] = str_replace('{string}', $value, $param);
                    }
                    $value = call_user_func_array($funcao, $params);
                }
        ?>
            <td align="<?php echo $coluna['align']; ?>"><?php echo $value.$coluna['concatenar_fim']; ?></td>
        <?php
            }
            elseif ( strlen($coluna['utils']) > 0 )
            {
                $value = preg_replace('/\&[^;]*;/', '', $registro[$coluna['coluna']]);
                $value = trim(preg_replace('/<[^>]*>/', '', $value));
                $value = count(explode(';', $value))-1;
        ?>
            <td align="<?php echo $coluna['align']; ?>"><?php echo $value; ?></td>
        <?php
            }
            // Texto
            elseif( !$coluna['oculta'] )
            {
        ?>
            <td align="<?php echo $coluna['align']; ?>"><?php echo $registro[$coluna['coluna']]; ?></td>
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
