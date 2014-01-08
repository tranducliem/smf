<?php
/**
 * Created by JetBrains PhpStorm.
 * User: duyliempro
 * Date: 5/12/13
 * Time: 2:53 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<!-- Latest Shots -->
<?php if($images): ?>
    <div class="span4">
        <div class="headline"><h3>Galleries</h3></div>
        <div id="myCarousel" class="carousel slide">
            <div class="carousel-inner">
                <?php $check = true; ?>
                <?php foreach($images as $gallery): ?>
                    <?php if (!empty($gallery->filename)): ?>
                        <?php if($check){ ?>
                            <div class="item active">
                                <img src="<?php echo $gallery->path;?>" alt="Photo 1" />
                                <div class="carousel-caption">
                                    <p>Cras justo odio, dapibus ac facilisis in, egestas.</p>
                                </div>
                            </div>
                        <?php $check = false;}else{ ?>
                            <div class="item">
                                <img src="<?php echo $gallery->path;?>" alt="Photo 1">
                                <div class="carousel-caption">
                                    <p>Cras justo odio, dapibus ac facilisis in, egestas.</p>
                                </div>
                            </div>
                        <?php }?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="carousel-arrow">
                <a class="left carousel-control" href="#myCarousel" data-slide="prev"><i class="icon-angle-left"></i></a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next"><i class="icon-angle-right"></i></a>
            </div>
        </div>
    </div><!--/span4-->
<?php endif; ?>
<!-- Latest Shots -->