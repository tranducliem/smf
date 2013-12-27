<div class="span9">
    <a class="btn-u" href="<?php echo base_url();?>answer/create">
        Create answer
    </a>
    <a class="btn-u" href="<?php echo base_url();?>answer/manage">Manage answer</a>
    <br/><br/>
    <?php if($post){    ?>
        <h3>List answer </h3>
        <div class="mmm">
            <?php foreach ($post as $item): ?>
                <div class="test" style="border: solid 1px; margin: 10px; padding: 10px; width: 500px">
                    <a href="<?php echo base_url();?>answer/view/<?php echo $item->id?>">
                        <h4><?php echo $item->title?></h4>
                    </a>
                    <div class="date">
                        <span><?php echo $item->created;?></span>
                    </div>
                </div>
            <?php endforeach?>
        </div>
    <?  } else { echo "Current No Post"; }   ?>
    <?php echo $this->pagination->create_links(); ?>
</div>
