<div class="span9">
    <div class="headline"><h3><?php echo "Manage answer";?></h3></div>
    <?php if($post){ ?>
        <?php echo $this->load->view('partials/filters') ?>
        <?php echo form_open('answer/action') ?>
        <div id="filter-result">
            <?php echo $this->load->view('tables/posts') ?>
        </div>
        <?php echo form_close(); ?>
    <?php }else{ ?>
        <?php echo lang('answer:currently_no_posts');?>
    <?php } ?>
</div>