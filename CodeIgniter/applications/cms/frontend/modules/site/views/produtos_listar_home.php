<?php
if ( is_array($produtos) && count($produtos) > 0 )
{
    foreach ( $produtos as $produto )
    {
        $capa_thumb = base_url('/arquivos/produtos/sem-foto.jpg');
        $capa = $capa_thumb;
        if ( is_file($path.$produto['id'].'/'.$produto['foto_capa']) )
        {
            $capa_thumb = base_url('/arquivos/produtos/'.$produto['id'].'/'.$produto['foto_capa']);
            $capa = str_replace('_thumb', '', $capa_thumb);
        }
?>
    <div class="s1-item">
        <div class="product-thumb">
            <a href="<?php echo base_url('/produto/'.$produto['link']); ?>"><img src="<?php echo $capa_thumb; ?>"></a>
        </div>
        <div class="p-info">
            <h4><?php echo $produto['titulo']; ?></h4>
            <span>&nbsp;</span>
            <div class="p-desc">
                <h5>Descrição</h5>
                <p><?php echo $produto['descricao']; ?></p>
            </div>
            <nav>
                <a href="<?php echo base_url('/produto/'.$produto['link']); ?>"><i class="fa fa-info-circle"></i> </a>
                <?php /*<a href="#"><i class="fa fa-shopping-cart"></i> </a>*/ ?>
                <a href="<?php echo $capa; ?>" class="prettyPhoto"><i class="fa fa-search"></i> </a>
                <?php /*<a href="#"><i class="fa fa-star"></i> </a>*/ ?>
            </nav>
        </div><!--end:p-info -->
    </div>
</li><!--end:item-->
<?php
    }
}
?>
