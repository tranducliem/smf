<div class="span9">
    <div class="headline"><h3>{{ helper:lang line="feedback_manager:title" }}</h3></div>
    <!-- Tabs Widget -->
    <ul class="nav nav-tabs tabs">
        <li id="tab_list" class="active"><a href="#tab-1" data-toggle="tab">{{ helper:lang line="feedback_manager:list_title" }}</a></li>
        <li id="tab_form1" ><a href="#tab-2" data-toggle="tab">{{ helper:lang line="feedback_manager:create_new_title" }}</a></li>
        <li id="tab_form2" ><a href="#tab-3" data-toggle="tab">List question</a></li>
        <li id="tab_form3" ><a href="#tab-4" data-toggle="tab">Statistics</a></li>
    </ul>
    <!--/Tabs Widget-->

    <!--tab-content-->
    <div class="tab-content">
        <div class="tab-pane active" id="tab-1">
            {{ if posts }}
            <?php echo $this->load->view('partials/filters') ?>
            <div class="clear"></div>
            <?php echo form_open('feedback_manager/action', array('class' => 'ajax_delete_form')) ?>
            <div id="filter-result">
                <?php echo $this->load->view('tables/posts') ?>
            </div>
            <?php echo form_close(); ?>
            {{ else }}
            {{ helper:lang line="feedback_manager:currently_no_posts" }}
            {{ endif }}
        </div>
        <div class="tab-pane" id="tab-2">
            <div class="form_field">
                <?php echo $this->load->view('partials/form') ?>
            </div>
        </div>
        <div class="tab-pane" id="tab-3">
            <div id="question-result">
                <?php echo $this->load->view('tables/List_question');?>
            </div>
        </div>
        <div class="tab-pane" id="tab-4">
            <div id="statistics-result" style="width: 600px">
                <?php echo $this->load->view('partials/statistics');?>
            </div>
        </div>
    </div>
    <!--/tab-content-->
</div>