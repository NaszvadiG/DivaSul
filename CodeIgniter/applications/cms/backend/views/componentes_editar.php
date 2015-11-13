<?php 
if( strlen($componente['content_last']) > 0 || strlen($componente['path_last']) > 0 )
{
?>
    <input type="button" value="Restaurar" style="float:right;" class="button restaurar" onclick="restaurar(this);"/>
<?php
}
?>

<!-- JS do restaurar -->
<script type="text/javascript">
function restaurar(botao)
{
    botao.style.display='none';

    // Content
    $.ajax({
        url:'<?php echo site_url('/components/get_last_content'); ?>/<?php echo $componente['id']; ?>',
        success:function(data)
        {
            document.getElementById('parametros').value = data;
        }
    });

    // Path
    $.ajax({
        url:'<?php echo site_url('/components/get_last_path'); ?>/<?php echo $componente['id']; ?>',
        success:function(data)
        {
            document.getElementById('path').value = data;
        }
    });
};
</script>
<div style="clear:both"></div>

<?php
// Título (inserir/editar)
$inserir_editar = 'Editar';
if ( is_null($componente['id']) )
{
    $inserir_editar = 'Inserir';
}
?>
<h2>Componente - <?=$inserir_editar;?></h2>

<?php
if ( !empty($erro) )
{
    echo '<div class="msg erro">'.$erro.'</div>';
}
?>

<?php echo form_open(); ?>
<table class="form">
    <tbody>
<?php
    if ( strlen($componente['id']) > 0 )
    {
?>
        <tr>
            <th>Código</th>
            <td>
                <?php echo $componente['id'];?>
                <input type="hidden" id="id" name="componente[id]" value="<?=$componente['id'];?>" />
            </td>
        </tr>
<?php
}
?>

        <tr>
            <th>Título</th>
            <td><?php echo form_input('componente[titulo]',$componente['titulo'],'size="80"'); ?></td>
        </tr>

        <tr>
            <th>Script</th>
            <?php $path_field = array('id' => 'path', 'name' => 'componente[path]', 'value' => $componente['path']); ?>
            <td><?php echo form_input($path_field); ?></td>
        </tr>

        <tr>
            <th>Parâmetros</th>
<?php
$content_field = array(
    'id' => 'parametros',
    'name' => 'componente[content]',
    'value' => $componente['content'],
    'rows' => '5',
    'cols' => 40
);
?>
            <td><?php echo form_textarea($content_field); ?></td>
        </tr>

        <tr>
            <th>Posição</th>
            <td><?php echo form_input('componente[posicao]',$componente['posicao'],'size="30"'); ?></td>
        </tr>

        <tr>
            <th>Ativo</th>
            <td><?php echo form_dropdown('componente[ativo]', array('1'=>'Sim','0'=>'Não'), $componente['ativo']); ?></td>
        </tr>

        <tr>
            <th>Páginas</th>
            <td>
<?php
$select_componente_paginas = array(
    'todas'=>'Todas',
    'nenhuma'=>'Nenhuma',
    'somente_selecionadas'=>'Somente selecionadas',
    'exceto_selecionadas'=>'Exceto selecionadas',
);
            echo form_dropdown('componente_paginas[tipo]',$select_componente_paginas,(!empty($componente_paginas['tipo'])?$componente_paginas['tipo']:''),'id="componente_paginas_tipo"');
?>
                <div id="componentes">
<?php
if ( !empty($componente_paginas['ids']) )
{
    foreach ( $componente_paginas['ids'] as $k => $v )
    {
        $componente_paginas['ids'][$k] = abs($v);
    }
}

foreach ( $paginas as $id => $titulo )
{
    $checked = !empty($componente_paginas['ids']) && (in_array($id,$componente_paginas['ids']));
    echo '<input type="checkbox" name="componente_paginas[ids][]" value="'.$id.'"'.($checked?' checked="checked"':'').' /> '.$titulo;
    echo '<br />';
}
?>
                </div>
            </td>
        </tr>

        <tr class="limpo">
            <th></th>
            <td><?=form_submit('submit','Salvar','class="button ok"'); ?> <?=anchor('componentes','Cancelar','class="button cancel"'); ?></td>
        </tr>
    </tbody>
</table>
<?php echo form_close(); ?>

<script type="text/javascript">
$("#componente_paginas_tipo").change(function()
{
    if (this.value=='todas' || this.value=='nenhuma')
    {
        $("#componentes input").attr('disabled','disabled');
        $("#componentes input").removeAttr('checked');
    }
    else
    {
        $("#componentes input").removeAttr('disabled');
        if (this.value=='somente_selecionadas')
        {
            $("#componentes input").each(function()
            {
                this.value = Math.abs(this.value);
            });
        }
        else if (this.value=='exceto_selecionadas')
        { 
            $("#componentes input").each(function()
            {
                this.value = -Math.abs(this.value);
            });
        }
    }
});

$("#componente_paginas_tipo").change();
</script>