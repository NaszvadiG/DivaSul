<style type="text/css">
table tbody tr:nth-child(2n-1) td
{
    background:none;
}

table tbody tr:hover td
{
    background-color:#FFC;
}

div#info
{
    width:100%;
    text-align:center;
}
</style>

<h2>Usuários - Permissões padrão</h2>
<?=anchor('usuarios/permissoes/'.$this->uri->segment(3), 'Voltar para permissões do usuário', 'class="button back" style="float:right;"');?>

<table>
    <thead>
        <tr>
            <th width="30">Código</th>
            <th>Descrição</th>
            <th>Path</th>
            <th width="70">Ações</th>
        </tr>
    </thead>
    <tbody>
<?php
foreach ( $permissoes as $permissao )
{
?>
        <tr>
            <td><?=$permissao['id']?></td>
            <td><?=$permissao['descricao']?></td>
            <td><?=$permissao['path']?></td>
            <td align="center">
                <img id="<?=$permissao['id'];?>" src="<?php echo site_url('arquivos/css/icons/'.(($permissao['padrao'] == 1) ? 'yes.png' : 'no.png'));?>" alt="<?=$permissao['padrao'];?>" style="cursor:pointer;" class="padrao"/>
            </td>
        </tr>
<?php
}
?>
    </tbody>
</table>

<script type="text/javascript">
$('body').on('click', 'tr td img.padrao', function()
{
    var img = this;
    var id = img.id;
    img.src = img.src.replace(/\/[^\/]+\.png/,'/ajaxload.gif');
    $.ajax(
    {
        url:'<?=site_url('usuarios/definir_permissao_padrao')?>/'+id,
        success: function(data)
        {
            location.reload();
        }
    });
});
</script>