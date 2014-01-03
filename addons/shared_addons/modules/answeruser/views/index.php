<div class="span9">
    <div class="headline"><h3><?php echo lang('answeruser:answeruser_list');?></h3></div>
    <div class="clear"></div>
    <?php echo $this->load->view('partials/filters') ?>
    <div class="clear"></div>
    <?php echo form_open('answeruser/action') ?>
        <div id="filter-result">
            <?php echo $this->load->view('tables/posts') ?>
        </div>
    <?php echo form_close(); ?>
</div>