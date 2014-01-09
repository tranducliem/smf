<!-- Input Team -->
<?php echo form_open('team/process', array('class' => 'ajax_submit_form'))?>
    <input type="hidden" id="row_edit_id" name="row_edit_id" value=""/>
    <input type="hidden" id="action" name="action" value="create"/>
    <label>{{ helper:lang line="team:form_title" }}<span class="require">*</span></label>
    <?php echo form_input('title', '', 'id="title" class="span6 border-radius-none" placeholder="Title input"') ?>
    <label>{{ helper:lang line="team:form_company_id" }}<span class="require">*</span></label>
    <?php echo form_dropdown('company_id', $companies, '', 'id="company_id" class="span6"')?>
    <label>{{ helper:lang line="team:form_description" }}<span class="require">*</span></label>
    <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => '', 'rows' => 6, 'class' => 'span12 border-radius-none margin-bottom-10')) ?>
    <button type="submit" id="btnSubmit" class="btn-u pull-left">Create team</button>
<?php echo form_close()?>