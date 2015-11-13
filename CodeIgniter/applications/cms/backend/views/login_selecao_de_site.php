<style type="text/css">
.site_sel
{
    padding:4px 7px 8px 24px;
    border:1px solid #CCC;
    border-radius:3px;
    -moz-border-radius:3px;
    margin:5px;
    height:13px;
    overflow:hidden;
    font-size:14px;
    background-image:url(<?php echo base_url('arquivos/css/icons/edit.png'); ?>);
    background-repeat:no-repeat;
    background-position:2px 4px;
    float:left;
}

.site_sel:hover
{
    cursor:pointer;
}

div.coluna
{
    float:left;
    width:95%;
}
</style>

<h2>Selecionar Site</h2>
<?php echo form_open('', array('id' => 'form_login_site', 'method'=>'POST')); ?>
    <table>
        <tbody>
            <tr>
                <th>Site:</th>
                <td>
                    <div id="coluna_1" class="coluna">
<?php
foreach ( $sites as $site )
{
    // Verifica se tem favicon, se tem usa-o, se não usa o ícone de editar
    $background = '';
    if ( (strlen($site['icone']) > 3 ) && is_file(SERVERPATH.'arquivos/icones_dos_sites/'.$site['icone']) )
    {
        $background = 'style="background-image:url('.base_url('../arquivos/icones_dos_sites/'.$site['icone']).');"';
    }

    // Botão de acesso ao site
    echo '<div class="site_sel" '.$background.' name="'.$site['id'].'" title="'.$site['titulo'].'" /> '.$site['titulo'].'</div>';
}
?>
                    </div>
                    <input id="site_id_selected" type="hidden" name="site_id" value="" />
                </td>
            </tr>
        </tbody>
    </table>
<?php echo form_close(); ?>
<script type="text/javascript">
$('form .site_sel').click(function()
{
    $('#site_id_selected').val($(this).attr('name'));
    $('form').submit();
});
</script>
