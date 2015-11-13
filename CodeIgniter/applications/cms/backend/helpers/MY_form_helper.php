<?php
function montar_assoc($array)
{
    $new_array = array();
    foreach ( $array as $arr )
    {
        $new_array[$arr['id']] = array_pop($arr);
    }

    return $new_array;
}

/**
 * $data['attr'][] = id, nome, value, etc
 * $hora['attr'][] = id, nome, value, etc
 * $botao_limpar = false
 * $label_botao_limpar = ''
 */
function input_data_hora($data, $hora, $botao_limpar = false, $label_botao_limpar='')
{
    $html = '';
    if ( $botao_limpar === false )
    {
        $data['attr']['readonly'] = false;
        $hora['attr']['readonly'] = false;
    }
    $html .= input_data($data);
    $html .= ' ';
    $html .= input_hora($hora);

    if ( $botao_limpar )
    {
        $html .= '<img src="'.base_url('arquivos/css/icons/clear.png').'" alt="clear.png" border="0" style="cursor:pointer;margin-top:3px;margin-left:5px;" onclick="document.getElementById(\''.$data['attr']['id'].'\').value=\'\';document.getElementById(\''.$hora['attr']['id'].'\').value=\'\';" title="Limpar '.$label_botao_limpar.'"/>';
    }

    return $html;
}

/**
 * $param['attr'][] = id, nome, value, etc
 * $botao_limpar = false
 * $label_botao_limpar = ''
 */
function input_data($params, $botao_limpar=false, $label_botao_limpar='')
{
    $input_data = array();
    
    // Defaults
    $params['attr']['class'] = 'campo_data';
    $params['attr']['size'] = 10;
    $params['attr']['maxlength'] = $params['attr']['size'];
    $params['attr']['onkeyup'] = 'mascara_data(this);';
    $params['attr']['onchange'] = 'verifica_data(this);';
    if ( $params['attr']['readonly'] === false )
    {
        unset($params['attr']['readonly']);
    }
    else
    {
        $params['attr']['readonly'] = true;
    }
    foreach ( $params['attr'] as $attr=>$valor )
    {
        $input_data[$attr] = $valor;
    }

    $html = '';
    $html .= form_input($input_data);

    if ( $botao_limpar )
    {
        $html .= '<img src="'.base_url('arquivos/css/icons/clear.png').'" alt="clear.png" border="0" style="cursor:pointer;margin-top:3px;margin-left:5px;" onclick="document.getElementById(\''.$params['attr']['id'].'\').value=\'\';" title="Limpar '.$label_botao_limpar.'"/>';
    }

    return $html;
}

/**
 * $param['attr'][] = id, nome, value, etc
 * $botao_limpar = false
 * $label_botao_limpar = ''
 */
function input_hora($params, $botao_limpar=false, $label_botao_limpar='')
{
    $input_data = array();
    
    // Defaults
    $params['attr']['class'] = 'campo_hora';
    $params['attr']['size'] = 5;
    $params['attr']['maxlength'] = $params['attr']['size'];
    $params['attr']['onkeyup'] = 'mascara_hora(this);';
    $params['attr']['onchange'] = 'verifica_hora(this);';
    if ( $params['attr']['readonly'] === false )
    {
        unset($params['attr']['readonly']);
    }
    else
    {
        $params['attr']['readonly'] = true;
    }
    foreach ( $params['attr'] as $attr=>$valor )
    {
        $input_data[$attr] = $valor;
    }

    $html = '';
    $html .= form_input($input_data);

    if ( $botao_limpar )
    {
        $html .= '<img src="'.base_url('arquivos/css/icons/clear.png').'" alt="clear.png" border="0" style="cursor:pointer;margin-top:3px;margin-left:5px;" onclick="document.getElementById(\''.$params['attr']['id'].'\').value=\'\';" title="Limpar '.$label_botao_limpar.'"/>';
    }

    return $html;
}
?>
