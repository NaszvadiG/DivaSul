<?php
// Título (inserir/editar)
$inserir_editar = 'Inserir';
if ( strlen($site['id']) > 0 )
{
    $inserir_editar = 'Editar';
}
?>
<h2>Site - <?php echo $inserir_editar; ?></h2>

<?php
if ( !empty($erro) )
{
    echo '<div class="msg erro">'.$erro.'</div>';
}
?>

<?php echo form_open_multipart(); ?>
    <table class="form">
        <tbody>
<?php
if ( $site['id'] )
{
?>
            <tr>
                <th>Código</th>
                <td>
                    <?php echo $site['id']; ?>
                    <input type="hidden" name="site[id]" value="<?php echo $site['id']; ?>" />
                </td>
            </tr>
<?php
}
?>

            <tr>
                <th>Título</th>
                <td><?php echo form_input('site[titulo]',$site['titulo'],'size="50" onchange="preenche_campo_dir(this.value);"'); ?></td>
            </tr>

            <tr>
                <th>Diretório</span></th>
                <td>
                    <?php echo form_input('site[dir]',$site['dir'],'id="site_dir" size="50" onkeypress="this.value=retornaSomenteMinusculo(this.value);" onblur="this.value=retornaSomenteMinusculo(this.value);" onchange="preenche_campos(this.value);"'); ?><br />
                    <span style="color:#F00;">*</span> Somente letras minúsculas, números, "-" ou "_".
                </td>
            </tr>

            <tr>
                <th>Url</th>
                <td><?php echo form_input('site[url]',$site['url'],'size="70" id="url" readonly="true"'); ?></td>
            </tr>

<?php if (!empty($site['id'])){ ?>

            <tr>
                <th>Template Padrão</th>
                <td><?php echo form_dropdown('site[template_id]', $templates,$site['template_id']); ?></td>
            </tr>
<?php } ?>
            <tr>
                <th>Ícone</th>
                <td>
<?php
$icon = array(
    'name' => 'icone',
);
?>
                <?php echo form_upload($icon); ?>
                </td>
            </tr>
<?php
if ( is_file(SERVERPATH.'arquivos/icones_dos_sites/'.$site['icone']) )
{
?>
            <tr>
                <th>Ícone atual</th>
                <td>
                    <img src="<?php echo base_url('../arquivos/icones_dos_sites/'.$site['icone']); ?>" alt="<?php echo $site['title']; ?>" />
                <td>
            <tr>
<?php 
} 
?>

            <tr class="limpo">
                <th></th>
                <td><?php echo form_submit('submit', 'Salvar', 'class="button ok"'); ?> <?php echo anchor('sites','Cancelar','class="button cancel"'); ?></td>
            </tr>
        </tbody>
    </table>
<?php echo form_close(); ?>

<script text="text/javascript" language="JavaScript">
function preenche_campo_dir(valor)
{
    if ( !document.getElementById('site_dir').value )
    {
        document.getElementById('site_dir').value = retornaSomenteMinusculo(valor, '-');
        preenche_campos(retornaSomenteMinusculo(valor, '-'));
    }
}

function preenche_campos(valor)
{
    // Preenche campo URL
    document.getElementById('url').value = 'http://<?php echo $_SERVER['SERVER_NAME']; ?>/'+valor+'/';
}
</script>
