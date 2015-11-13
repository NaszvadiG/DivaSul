    <div id="header">
        <div class="in">
            <h1 id="logo">
            <a class="home" href="<?=base_url()?>" title="Página principal"><?=(!empty($site_name)?$site_name:'Área Administrativa')?><?=($_SERVER['SERVER_NAME'] == 'localhost') ? ' - TESTE' : ''?></a>
            </h1>
<?php 
if ( !empty($usuario['id']) )
{
                echo '<div id="userbox">'.$usuario['nome'];
                if ( $sites > 1 )
                { echo ' | '.anchor('login/trocar_site','Trocar site');}
                echo ' | '.anchor('login/deslogar','Sair').'</div>';
}
?>
        </div>
    </div><!-- #header -->

    <div id="nav">
        <ul>
<?php
    if ( !empty($menu_topo) )
    {
?>
        <?php echo $menu_topo; ?>
<?php
    }
?>
        </ul>
    </div><!-- #nav -->
    <div style="clear:both;"></div>
    <div id="content">
        <div id="loading" onclick="this.style.display='none';" title="Clique para fechar">
            <div id="loading_image"></div>
        </div>
        <div id="mini_loading" onclick="this.style.display='none';" title="Clique para fechar">
        </div>