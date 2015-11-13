<h2>Usuário - Permissões</h2>
<?=anchor('usuarios/permissoes_padrao/'.$this->uri->segment(3), 'Permissões padrão', 'class="button config" style="float:right;"');?>
<?=anchor('usuarios', 'Voltar', 'class="button back" style="float:right;"');?>

<?php
$acesso = array();
foreach ( $permissoes as $permissao )
{
    $acesso[] = $permissao['site_id'].'-'.$permissao['modulo_id'];
}
?>

<?php echo form_open('', array('id' => 'form_usuarios_permissoes', 'method'=>'POST')); ?>
    <table>
        <tbody>
            <tr>
                <th>Usuário</th>
                <td><?=$usuario['name']?> - <?=$usuario['email']?></td>
            </tr>
            <tr>
                <th>Permissoes</th>
                <td>
                <div style="position:relative;">
                    <div style="width:700px;min-height:415px;padding-right:5px; border-right:1px solid #ccc;float:left;" id="permissoes">
<?php
$margin_top = 0;
foreach ( $sites as $site )
{
    echo '<a id="'.$site['id'].'">'.$site['titulo'].'</a><br />';
    echo '<div class="modulos" id="modulos_site_'.$site['id'].'"  style="margin-top:'.$margin_top.'px">';
    $margin_top -= 15;

    foreach ( $modulos as $modulo )
    {
        echo '<label>'.form_checkbox('permissoes[]',$site['id'].'-'.$modulo['id'],in_array($site['id'].'-'.$modulo['id'], $acesso), 'id="checkbox_'.$site['id'].'-'.$modulo['id'].'"').$modulo['path'].'</label><br />';
    }

    // Botão de remover todas permissões
    echo '<br /><span onclick="remover_todas_permissoes(\''.$site['id'].'\');" class="button" style="float:none;cursor:pointer;">Remover todas permissões</span>';
    // Botão de aplicar todas permissões
    echo '<span onclick="aplicar_todas_permissoes(\''.$site['id'].'\');" class="button" style="float:none;cursor:pointer;">Aplicar todas permissões</span>';
    // Botão de aplicar permissões padrão
    echo '<span onclick="aplicar_permissoes_padrao(\''.$site['id'].'\');" class="button" style="float:none;cursor:pointer;">Aplicar permissões padrão</span><br />';

    echo '</div>';
}
?>    
                     </div>
                </div>
                </td>
            </tr>
            <tr class="limpo">
                <th></th>
                <td><?=form_submit('submit','Salvar','class="button ok"');?> <?=anchor('usuarios','Cancelar','class="button cancel"');?></td>
            </tr>
        </tbody>
    </table>
<?php echo form_close(); ?>

<script type="text/javascript">
/**
 * Remove todas as permissões
 */
function remover_todas_permissoes(site_id)
{
<?php
    foreach ( $modulos as $modulo )
    {
?>
    $('#checkbox_'+site_id+'-<?=$modulo['id'];?>')[0].checked=false;
<?php
    }
    ?>
};

/**
 * Aplica todas as permissões
 */
function aplicar_todas_permissoes(site_id)
{
<?php
    foreach ( $modulos as $modulo )
    {
?>
    $('#checkbox_'+site_id+'-<?=$modulo['id'];?>')[0].checked=true;
<?php
    }
    ?>
};

function aplicar_permissoes_padrao(site_id)
{
    // Remove todas as permissões
    remover_todas_permissoes(site_id);

    // Adiciona as padrão
<?php
    foreach ( $permissoes_padrao as $permissao )
    {
?>
    $('#checkbox_'+site_id+'-<?=$permissao['id'];?>')[0].checked=true;
<?php
    }
    ?>
};
</script>

<script type="text/javascript">
$('#permissoes .modulos').css('display','none');

$('#permissoes a').click(function()
{
    // Remove css de selecionado de todos os links de sites
    $('#permissoes a').removeClass('selecionado');
    // Adiciona css de seleciona no link clicado
    $(this).addClass('selecionado');
    // Oculta as divs das permissões dos sites
    $('#permissoes .modulos').css('display','none');
    // Exibe a div de permissão do site clicado
    $('#modulos_site_'+this.id).css('display','');
});
</script>
