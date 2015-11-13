<?php
if ( $banners && count($banners) > 0 )
{
?>
<div id="slider-container">
    <div class="flexslider">
        <ul class="slides">
<?php
    foreach ( $banners as $banner )
    {
        if ( is_file($path.$banner['img_banner']) )
        {
            $link = '';
            $target = '';
            if ( strlen($banner['link']) > 0 )
            {
                $link = 'href="'.$banner['link'].'"';
                $target = 'target="'.$banner['target'].'"';
            }
?>
            <li data-thumb="<?php echo base_url('/arquivos/banners/'.$banner['img_thumb']); ?>" title="<?php echo $banner['titulo'];?>">
                <a <?php echo $link; ?> <?php echo $target; ?>>
                <img alt="" src="<?php echo base_url('/arquivos/banners/'.$banner['img_banner']); ?>"/>
                </a>
            </li>
<?php
        }
    }
?>
        </ul>
    </div>
</div>
<?php
}
else
{
    echo 'Nenhum banner foi encontrado.';
}
?>