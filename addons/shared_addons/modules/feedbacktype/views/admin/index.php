<div class="one_full">
    <section class="title">
        <h4><?php echo lang('feedbacktype:types_title') ?></h4>
    </section>

    <section class="item">
        <div class="content">
            <?php if ($types) : ?>
                <?php echo $this->load->view('admin/partials/filters') ?>
                <?php echo form_open('admin/feedbacktype/action') ?>
                    <div id="filter-stage">
                        <?php echo $this->load->view('admin/tables/feedbacktypes') ?>
                    </div>
                <?php echo form_close() ?>
            <?php else : ?>
                <div class="no_data"><?php echo lang('feedbacktype:currently_no_type') ?></div>
            <?php endif ?>
        </div>
    </section>
</div>