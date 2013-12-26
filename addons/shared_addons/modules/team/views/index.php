<div class="span9">
    <div class="headline"><h3><?php echo lang('team:list_title');?></h3></div>
    <?php if($posts){ ?>
        <?php echo $this->load->view('partials/filters') ?>
        <?php echo form_open('team/action') ?>
            <div id="filter-result">
                <?php echo $this->load->view('tables/posts') ?>
            </div>
        <?php echo form_close(); ?>
    <?php }else{ ?>
        <?php echo lang('team:currently_no_posts');?>
    <?php } ?>
</div>