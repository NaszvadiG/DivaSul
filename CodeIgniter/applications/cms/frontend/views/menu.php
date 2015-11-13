<?php
$atual = ' class="atual"';
if ( is_array($itens) && count($itens) > 0 )
{
    echo '<ul class="listMenu">';

    foreach ( $itens as $item )
    {
        $class = ($item['url'] == array_pop(explode('/', $_SERVER['REQUEST_URI']))) ? $atual : '';

        // Se for separador tem um CSS especial
        $separator = '';
        if ( $item['tipo'] == 'separador' )
        {
            $separator .= '" class="separator';
            if ( preg_match('/show_title/i', $item['content']) )
            {
                $separator .= ' subtitulo';
            }
        }
        $separator .= '"';

        echo '<li id="item'.$item['id'].'" '.$separator.'>';

        // Separador
        if ( $item['type'] == 'separator' )
        {
            $params = unserialize($item['content']);
            echo '<span>';
            if ( !empty($params['show_title']) )
            {
                echo $item['title'];
            }
            echo '</span>';
        }
        // Link
        elseif ( $item['tipo'] == 'link' )
        {
            $params = unserialize($item['content']);
            $link = $params['link'];
            if ( strpos($link, 'http') === false )
            {
                $link = base_url($link);
            }
            echo '<a href="'.$link.'"'.(!empty($params['blank'])?' '.$class.' target="_blank"':'').'>'.$item['titulo'].'</a>';
        }
        // Página
        else
        {
            echo '<a href="'.base_url($item['url']).'" '.$class.'>'.$item['titulo'].'</a>';
        }
        // Nota
        if ( !empty($item['note']) )
        {
            echo '<br /><small>'.$i['note'].'</small>';
        }

        echo '</li>';
    }

    echo '</ul>';
}
?>
