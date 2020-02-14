<?php 

if (trim($content_top) || trim($content_block1) ) : ?>
    <div class="so-spotlight1">
        <div id="yt_slideshow" class="yt_slideshow">
            <div class="slider-container-full "> 
                <?php echo $content_top; ?>
            </div>
        </div>
    </div>
<?php endif; ?>


<div class="so-spotlight2 container">
     <div class="row">
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-8 col-md-9 col-xs-12'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <?php echo $column_left; ?>
        <div id="content" class="<?php echo $class; ?>">
            <div class="row">
                <?php if (trim($content_block1)) : ?>
                <div class="slider-deal-w col-lg-9 col-md-9">
                    <?php echo $content_block1 ?>
                </div>
                <?php endif; ?>
                <?php if (trim($content_block2)) : ?>
                <div class="products-w col-lg-3 col-md-3">
                    <?php echo $content_block2 ?>
                </div>
                <?php endif; ?>
            </div>
            <?php echo $content_block3 ?>
        </div>
       <?php echo $column_right; ?>
     </div>
</div>
<?php if (trim($content_block4)) : ?>
<div class="so-spotlight3">
    <div class="container"> 
        <?php echo $content_block4; ?>
    </div>
</div>   
<?php if (trim($content_block5)) : ?>
<div class="so-spotlight4 full-wrapper">
    <div class="container">
        <?php echo $content_block5 ?>
    </div>    
</div>
<?php endif; ?> 

<?php if (trim($content_block6)) : ?>
<div class="so-spotlight5">
    <div class="container">
        <?php echo $content_block6 ?>
    </div>    
</div>
<?php endif; ?>

<?php endif; ?>
<?php if (trim($content_bottom)) : ?>
<div class="so-spotlight6">
    <div class="container">
        <div class="rows">
            <?php echo $content_bottom ?>
        </div>
    </div> 
</div>
<?php endif; ?>