<div class="span9">
    <div class="headline"><h3>{{ helper:lang line="answer:title" }}</h3></div>
    <!-- Tabs Widget -->
    <ul class="nav nav-tabs tabs">
        <li id="tab_list" class="active"><a href="#tab-1" data-toggle="tab">{{ helper:lang line="answer:list_title" }}</a></li>
        <li id="tab_form" ><a href="#tab-2" data-toggle="tab">{{ helper:lang line="answer:create_new_title" }}</a></li>
    </ul>
    <!--/Tabs Widget-->

    <!--tab-content-->
    <div class="tab-content">
        <div class="tab-pane active" id="tab-1">
            <?php echo $this->load->view('partials/filters') ?>
            <div class="clear"></div>
            <?php echo form_open('answer/action', array('class' => 'ajax_delete_form')) ?>
                <div id="filter-result">
                    <?php echo $this->load->view('tables/posts') ?>
                </div>
            <?php echo form_close(); ?>
        </div>
        <div class="tab-pane" id="tab-2">
            <div class="form_field">
                <?php echo $this->load->view('partials/form') ?>
            </div>
        </div>
    </div>
    <!--/tab-content-->
</div>