<?php
/**
 * Created by JetBrains PhpStorm.
 * User: duyliempro
 * Date: 8/17/13
 * Time: 2:02 AM
 * To change this template use File | Settings | File Templates.
 */

if($images){
    foreach($images as $item){
        ?>
        <li>
            <div class="thumb_img">
                <img class="image_thumbnail" src="<?php echo base_url().'files/large/'.$item->id;?>" onclick="selectThumbnail('<?php echo $item->id;?>')">
            </div>
        </li>
        <?php
    }
}
?>