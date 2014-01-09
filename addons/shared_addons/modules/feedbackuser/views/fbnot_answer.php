<div class="span9">
    <div class="headline"><h3>{{ helper:lang line="feedbackuser:types_title" }}</h3></div>
    <!-- Tabs Widget -->
    <ul class="nav nav-tabs tabs">
        <li id="tab_list" class="active"><a href="#tab-1" data-toggle="tab">{{ helper:lang line="feedbackuser:list_fbnot_answer" }}</a></li>
        <li id="tab_form" ><a href="#tab-2" data-toggle="tab">{{ helper:lang line="feedbackuser:answer_fb" }}</a></li>
    </ul>
    <!--/Tabs Widget-->

    <!--tab-content-->
    <div class="tab-content">
        <div class="tab-pane active" id="tab-1">
            <div id="filter-result">
                <?php echo $this->load->view('tables/not_answers') ?>
            </div>

        </div>
        <div class="tab-pane" id="tab-2">
            <div class="form_field">
                <?php echo $this->load->view('partials/form_answer') ?>
            </div>
        </div>
    </div>
    <!--/tab-content-->
</div>