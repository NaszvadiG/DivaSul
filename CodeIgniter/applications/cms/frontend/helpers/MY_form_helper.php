<?

function montar_assoc($array,$op_selecione = false){
    $new_array = array();
    if($op_selecione){
        $new_array[''] = '--selecione--';
    }
    foreach($array as $a){
        $new_array[$a['id']] = array_pop($a);
    }
    return $new_array;
}

