<div class="span9">
    <div class="headline"><h3>{{ helper:lang line="team:title" }}</h3></div>
    <!-- Tabs Widget -->
    <ul class="nav nav-tabs tabs">
        <li class="active"><a href="#tab-1" data-toggle="tab">{{ helper:lang line="team:list_title" }}</a></li>
        <li><a href="#tab-2" data-toggle="tab">{{ helper:lang line="team:create_new_title" }}</a></li>
    </ul>
    <!--/Tabs Widget-->

    <!--tab-content-->
    <div class="tab-content">
        <div class="tab-pane active" id="tab-1">
            {{ if posts }}
                <?php echo $this->load->view('partials/filters') ?>
                <div class="clear"></div>
                <?php echo form_open('team/action') ?>
                    <div id="filter-result">
                        <?php echo $this->load->view('tables/posts') ?>
                    </div>
                <?php echo form_close(); ?>
            {{ else }}
                {{ helper:lang line="team:currently_no_posts" }}
            {{ endif }}
        </div>
        <div class="tab-pane" id="tab-2">
            <p>Vivamus imperdiet condimentum diam, eget placerat felis consectetur id. Donec eget orci metus, ac adipiscing nunc.</p>
        </div>
    </div>
    <!--/tab-content-->
</div>