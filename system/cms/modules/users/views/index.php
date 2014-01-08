<div class="span9">
    <div class="headline"><h3>{{ helper:lang line="employee:title" }}</h3></div>
    <!-- Tabs Widget -->
    <ul class="nav nav-tabs tabs">
        <li id="tab_list" class="active"><a href="#tab-1" data-toggle="tab">{{ helper:lang line="employee:list_title" }}</a></li>
        <li id="tab_form" ><a href="#tab-2" data-toggle="tab">{{ helper:lang line="employee:create_new_title" }}</a></li>
        <li id="tab_form2" ><a href="#tab-3" data-toggle="tab">{{ helper:lang line="employee:view_user" }}</a></li>
    </ul>
    <!--/Tabs Widget-->

    <!--tab-content-->
    <div class="tab-content">
        <div class="tab-pane active" id="tab-1">
            <?php echo $this->load->view('partials/filters') ?>
            <div class="clear"></div>
            <?php echo form_open('employee/action', array('class' => 'ajax_delete_form')) ?>
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
        <div class="tab-pane" id="tab-3">
            <div id="view-result" style="width: 600px"></div>
        </div>
    </div>
    <!--/tab-content-->
</div>