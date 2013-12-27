<div class="span9">
    <a href="<?php echo base_url();?>feedback_manager_question/create">
        <button class="btn-u">Create feedback question</button>
    </a>
    <a href="<?php echo base_url();?>feedback_manager_question/manage"><button class="btn-u">Manage feedback question</button></a>
    <br/><br/>
    <?php if($post){    ?>
        <h3>List feedback manager question</h3>
        <div class="mmm">
            <?php foreach ($post as $item): ?>
                <div class="test" style="border: solid 1px; margin: 10px; padding: 10px; width: 500px">
                    <h5>Feedback Manager Id: <?php echo $item->feedback_manager_id?></h5>
                    <h5>Question Id: <?php echo $item->question_id;?> </h5>
                </div>
            <?php endforeach?>
        </div>
    <?  } else { echo "Current No Post"; }   ?>
    <?php echo $this->pagination->create_links(); ?>
</div>