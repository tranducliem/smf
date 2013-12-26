
<a href="<?php echo base_url();?>feedback_manager/create">
    <button class="btn-u">Create feedback</button>
</a>
<a href="<?php echo base_url();?>feedback_manager/manage"><button class="btn-u">Manage feedback</button></a>
<br/><br/>
<?php if($post){    ?>
    <h3>List feedback </h3>
    <div class="mmm">
        <?php foreach ($post as $item): ?>
            <div class="test" style="border: solid 1px; margin: 10px; padding: 10px; width: 500px">
                    <a href="<?php echo base_url();?>feedback_manager/view/<?php echo $item->id?>">
                        <h4><?php echo $item->title?></h4>
                    </a>
                    <div class="date">
                        <span><?php echo $item->created;?></span>
                    </div>
                    <button class="btn-u" style="float: right; margin-top: -30px">
                        <?php
                            if($item->status == 0) echo "Not start";
                            else if($item->status == 1) echo "Processing";
                            else if($item->status == 2) echo "Done";
                        ?>
                    </button>
            </div>
        <?php endforeach?>
    </div>
<?  } else { echo "Current No Post"; }   ?>