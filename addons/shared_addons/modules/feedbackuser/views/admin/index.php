<div class="one_full">
    <section class="title">
        <h4><?php echo lang('feedbackuser:types_title') ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php if ($feedbackusers) : ?>
                <?php echo $this->load->view('admin/partials/filters') ?>
                <?php echo form_open('admin/feedbackuser/action') ?>
                    <div id="filter-stage">
                        <?php echo $this->load->view('admin/tables/feedbackusers') ?>
                    </div>
                <?php echo form_close() ?>
            <?php else : ?>
                <div class="no_data"><?php echo lang('feedbackuser:currently_no_feedback') ?></div>
            <?php endif ?>
        </div>
    </section>
</div>