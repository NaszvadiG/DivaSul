<style type="text/css">
div#visualizar_log b,
div#visualizar_log a
{
    font-size:10pt;
}

div#visualizar_log blockquote
{
    margin-left:20px;
    margin-right:20px;
    padding:5px;
    border:1px solid #CCC;
}
</style>
<div id="visualizar_log">
    <h2>Explorando <span title="">LOG</span> <b><?php echo $log['id']; ?></b></h2><br /><br />
<?php
if ( count((array)$dados_anteriores) == 0 )
{
?>
    O registro <b><?php echo $log['id_registro']; ?></b> da tabela <b><?php echo $log['tabela']; ?></b> foi removido.<br />
    O valor anterior da coluna <b><?php echo $log['campo']; ?></b> era:<br /><br />
    <blockquote><?php echo $log['valor']; ?></blockquote><br />
    <br />
<?php
}
else
{
?>
    O registro <b><?php echo $log['id_registro']; ?></b> da tabela <b><?php echo $log['tabela']; ?></b> foi alterado a coluna <b><?php echo $log['campo']; ?></b> de:<br /><br />
    <blockquote><?php echo $log['valor']; ?></blockquote><br />
    para:<br /><br />
    <blockquote><?php echo $dados_anteriores[$log['campo']]; ?>.</blockquote><br />
<?php
}
?>
    <br />
    <p>O &uacute;ltimo usu&aacute;rio a alterar o registro foi: <a href="mailto:<?php echo $usuario['email']; ?>" target="_blank"><b><?php echo $usuario['nome']; ?></b>(<?php echo $log['usuario_id']; ?>)</a> <?php echo strftime('%A, %d de %B de %Y as %H:%M:%S', strtotime($log['data_hora'])); ?>. 
<?php
if ( strlen($log['data_alteracao']) == 10 )
{
?>
    Em <?php echo $log['data_alteracao'];
}
?>
<?php
if ( strlen($log['hora_alteracao']) == 8 )
{
?>
 &agrave; <?php echo $log['hora_alteracao']; ?>.
<?php
}
else
{
?>
.
<?php
}
?>
    </p>
    <br />
    <br />
    <?php echo anchor('logs/listar', 'Voltar', 'class="button back" style="float:right;"'); ?>
</div>
