<?php
/**
 * Created by JetBrains PhpStorm.
 * User: duyliempro
 * Date: 5/12/13
 * Time: 2:53 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<!--=== Slider ===-->
<?php if($images): ?>
    <div class="slider-inner">
        <div id="da-slider" class="da-slider">
            <?php foreach($images as $gallery): ?>
                <?php if (!empty($gallery->filename)): ?>
                    <div class="da-slide">
                        <h2><i>CLEAN &amp; FRESH</i> <br /> <i>FULLY RESPONSIVE</i> <br /> <i>DESIGN</i></h2>
                        <p><i>Lorem ipsum dolor amet</i> <br /> <i>tempor incididunt ut</i> <br /> <i>veniam omnis </i></p>
                        <div class="da-img">
                            <?php echo img(array('src' => site_url('files/large/'.$gallery->file_id), 'alt' => $gallery->alt)); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <!--<div class="da-slide">
                <h2><i>RESPONSIVE VIDEO</i> <br /> <i>SUPPORT AND</i> <br /> <i>MANY MORE</i></h2>
                <p><i>Lorem ipsum dolor amet</i> <br /> <i>tempor incididunt ut</i></p>
                <div class="da-img span6">
                    <div class="span6">
                        <iframe src="http://player.vimeo.com/video/47911018" width="100%" height="320" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                    </div>
                </div>
            </div>-->

            <nav class="da-arrows">
                <span class="da-arrows-prev"></span>
                <span class="da-arrows-next"></span>
            </nav>
        </div><!--/da-slider-->
    </div><!--/slider-->
<?php endif; ?>
<!--=== End Slider ===-->