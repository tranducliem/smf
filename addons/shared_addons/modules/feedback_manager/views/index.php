
<h3>List feedback </h3>
<?php if($posts){    ?>
    <div class="mmm">
        <?php foreach ($posts as $item): ?>
            <div class="test" style="border: solid 1px; margin: 10px; padding: 10px">
                    <a href="<?php echo base_url();?>feedback_manager/<?php echo $item->id?>"><?php echo $item->title?></a>
                    <div class="date">
                        <span><?php echo $item->created;?></span>
                    </div>
            </div>
        <?php endforeach?>
    </div>
<?  } else { echo "Current No Post"; }   ?>