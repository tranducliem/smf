<?php echo form_open('feedbackuser/feedback_not_answer/answer_feedback', array('class' => 'ajax_submit_form'))?>
    <input type="hidden" id="feedback_id" name="feedback_id" value=""/>
    <div id="title_feedback"></div>
    <div id="desc_feedback"></div>
    <div id="questions"></div>
    <button type="submit" id="btnAnswer" name="btnAnswer" class="btn-u pull-left">Answer Feedback</button>
<?php echo form_close()?>
