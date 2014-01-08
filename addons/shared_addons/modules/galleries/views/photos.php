{{ session:messages success="success-box" notice="notice-box" error="error-box" }}

<?php if (validation_errors()): ?>
    <div class="error-box">
        <?php echo validation_errors();?>
    </div>
<?php endif ?>

<!--=== Content Part ===-->
<div class="container">
    <div class="row-fluid gallery">
        <?php if($post){?>
            <ul class="thumbnails">
                <?php $count_item = 1; ?>
                <?php foreach($post as $image){?>
                    <li class="span3">
                        <a class="thumbnail fancybox-button zoomer" data-rel="fancybox-button" title="<?php echo $image->alt;?>" href="uploads/default/files/<?php echo $image->filename;?>">
                            <div class="overlay-zoom">
                                <img src="uploads/default/files/<?php echo $image->filename;?>" alt="<?php echo $image->alt;?>" />
                                <div class="zoom-icon"></div>
                            </div>
                        </a>
                    </li>
                    <?php
                    if($total_photo > 4){
                        if(($count_item % 4) == 0){
                            if($count_item < $total_photo){
                                echo '</ul><ul class="thumbnails">';
                            }
                        }
                    }?>
                    <?php $count_item++; ?>
                <?php } ?>
            </ul><!--/thumbnails-->
            <?php echo $pages; ?>
        <?php }else{ ?>
            <p><?php echo lang('galleries.no_galleries_error'); ?></p>
        <?php }?>
    </div><!--/row-fluid-->
</div><!--/container-->
<!--=== End Content Part ===-->