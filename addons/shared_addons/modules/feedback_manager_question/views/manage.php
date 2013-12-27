<div class="span9">
    <div class="headline"><h3><?php echo "Manage feedback question";?></h3></div>
    <div class="clear"></div>
    <?php if($post){ ?>
        <?php echo $this->load->view('partials/filters') ?>
        <div class="clear"></div>
        <?php echo form_open('feedback_manager_question/action') ?>
        <div id="filter-result">
            <?php echo $this->load->view('tables/posts') ?>
        </div>
        <?php echo form_close(); ?>
    <?php }else{ ?>
        <?php echo lang('feedback_manager_question:currently_no_posts');?>
    <?php } ?>
</div>