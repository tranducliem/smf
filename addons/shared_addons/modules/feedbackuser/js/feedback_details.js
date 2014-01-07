/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function feedback_details(id) {
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedbackuser/feedback_not_answer/details_feedback/" + id,
        data: {},
        success: function(data) {
            var response = $.parseJSON(data);
            $('#title_feedback').html(response['feedback']['title']);
            $('#feedback_id').val(response['feedback']['id']);
            $('#desc_feedback').html(response['feedback']['description']);
            var questions = '';
            for (var i = 0; i < response['questions'].length; i++) {
                questions += (i + 1) + '. ' + response['questions'][i]['title'] + '<br/><div class="answers">';
                answers = response['questions'][i]['answers'];
                for (var j = 0; j < answers.length; j++) {
                    questions += '<input class="radio_item" type="radio" name="' + response['questions'][i]['id'] + '" value="' + answers[j]['id'] + '">' + answers[j]['title'] + '<br/>';
                }
                questions += '</div>'

            }
            $('#questions').html(questions);
            //Show tab form team
            $('#tab-1').removeClass('active');
            $('#tab_list').removeClass('active');
            $('#tab_form').addClass('active');
            $('#tab-2').addClass('active');
        },
        error: function(xhr) {
            console.log("Error: " + xhr.message);
        }
    });
}

function form_validate() {
    return true;
}

function form_success(data) {
    var response = $.parseJSON(data);
    if (response.status == "success") {
        open_message_block("success", response.message);
        $('#tab-2').removeClass('active');
        $('#tab_form').removeClass('active');
        $('#tab_list').addClass('active');
        $('#tab-1').addClass('active');
    } else if (response.status == "error") {
        open_message_block("error", response.message);
    }
}
