<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
    <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="text-heading line">
                <h3>
<?php
if ( is_array($produtos) && count($produtos) > 0 )
{
?>
Listando <strong title="de <?php echo $total_produtos; ?>"><?php echo count($produtos); ?></strong> produto<?php echo (count($produtos)>1?'s':''); ?>:
<?php
}
else
{
    echo 'Nenhum produto nesta categoria.';
}
?>
                </h3>
            </div>
        </div><!--ed:col-3-->
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="style-switch">
                <a href="#" class="grid active"><i class="fa fa-th-large"></i></a>
                <a href="#lista" class="list"><i class="fa fa-th-list"></i></a>
            </div>
        </div><!--end:col-9-->
    </div><!--end:row-->
<?php
if ( is_array($produtos) && count($produtos) > 0 )
{
?>
    <ul class="all-products">
<?php
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
        <li>
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
?>
    </ul>

    <div class="pagination">
<?php
if ($pagina > 1)
{
?>
        <a href="<?php echo base_url('/produtos/'.$link.'/'.(($pagina-1)>0?($pagina-1):1)); ?>" class="prev-btn"><i class="fa fa-angle-left"></i></a>
<?php
}
?>
        <div class="pages">
<?php
$controle = 3;
$min = $pagina-$controle;
$min = $min > 0 ? $min : 1;
$max = $min+($controle*2);
$max = $max > $total_paginas ? $total_paginas : $max;
for( $i = $min; $i <= $max; $i++ )
{
?>
            <a href="<?php echo base_url('/produtos/'.$link.'/'.$i); ?>" <?php if ($pagina == $i){echo 'class="active"';} ?>><?php echo $i; ?></a>
<?php
}
?>
        </div> <!--end:pages-->
<?php
if ($tem_mais_paginas)
{
?>
        <a href="<?php echo base_url('/produtos/'.$link.'/'.($pagina+1)); ?>" class="next-btn"><i class="fa fa-angle-right"></i> </a>
<?php
}
?>
    </div>
<?php
}
?>
</div><!--end:colright -->

