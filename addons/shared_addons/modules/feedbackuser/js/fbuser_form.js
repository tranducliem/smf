/**
 * Created by liemtd on 12/27/13.
 */

function form_validate(){
    var feedback_manager_id = $('#feedback_manager_id');
    var user_id = $('#user_id');

    if(feedback_manager_id.val() == ""){
        open_message_block("error", "Feedback manager is required!");
        feedback_manager_id.focus();
        return false;
    }
    if(user_id.val() == ""){
        open_message_block("error", "User is required!");
        user_id.focus();
        return false;
    }
    return true;
}

function form_reset(){
    $('#tab_form a').html('Create new Feedback User');
    $('#btnSubmit').html('Create Feedback User');
    $('#feedback_manager_id').val("");
    $('#user_id').val("");
}

function edit_record(id){
    //Set id for row
    $('#row_edit_id').val(id);
    //Set action for submit
    $('#action').val('edit');
    $('#tab_form a').html('Edit Feedback User');
    $('#btnSubmit').html('Edit Feedback User');
    //Bidding data
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedbackuser/get_fbuser_by_id/"+id,
        data: {},
        success: function(data){
            var response = $.parseJSON(data);
            $('#feedback_manager_id').val(response.feedback_manager_id);
            $('#user_id').val(response.user_id);
            //Show tab form team
            $('#tab-1').removeClass('active');
            $('#tab_list').removeClass('active');
            $('#tab_form').addClass('active');
            $('#tab-2').addClass('active');
        },
        error: function(xhr){
            console.log("Error: " + xhr.message);
        }
    });
}

function delete_record(id){
    var con = confirm("Are you sure ?");
    if(con){
        $.ajax({
            type: "POST",
            url: BASE_URL + "feedbackuser/delete/"+id,
            data: {},
            success: function(data){
                var response = $.parseJSON(data);
                if(response.status == "success"){
                    open_message_block("success", response.message);
                    list_refresh();
                }else if(response.status == "warning"){
                    open_message_block("warning", response.message);
                }else if(response.status == "error"){
                    open_message_block("error", response.message);
                }
            },
            error: function(xhr){
                console.log("Error: " + xhr.message);
            }
        });
    }
}

function form_success(data){
    var response = $.parseJSON(data);
    if(response.status == "success"){
        open_message_block("success", response.message);
        form_reset();
        //Show tab list team
        $('#tab-2').removeClass('active');
        $('#tab_form').removeClass('active');
        $('#tab_list').addClass('active');
        $('#tab-1').addClass('active');
        //Refresh data
        list_refresh();
    }else if(response.status == "error"){
        open_message_block("error", response.message);
    }
}

function list_refresh(){
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedbackuser",
        data: {},
        success: function(data){
            $('#filter-result').html(data);
            //Init
            App.initDeleteButton();
        },
        error: function(xhr){
            console.log("Error: " + xhr.message);
        }
    });
}
