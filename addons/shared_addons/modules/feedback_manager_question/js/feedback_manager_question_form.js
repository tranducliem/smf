function form_validate(){
    var feedback_manager_id = $('#feedback_manager_id');
    var question_id = $('#question_id');

    if(feedback_manager_id.val() == ""){
        open_message_block("error", "Feedback manager is required!");
        feedback_manager_id.focus();
        return false;
    } else if(question_id.val() == ""){
        open_message_block("error", "Company is required!");
        question_id.focus();
        return false;
    }
    return true;
}

function form_reset(){
    $('#tab_form a').html('Create new feedback manager question');
    $('#btnSubmit').html('Create feedback manager question');
    $('#action').val("create");
    $('#row_edit_id').val("");
    $('#feedback_manager_id').val("");
    $('#question_id').val("");
}

function edit_record(id){
    //Set id for row
    $('#row_edit_id').val(id);
    //Set action for submit
    $('#action').val('edit');
    $('#tab_form a').html('Edit feedback manager question');
    $('#btnSubmit').html('Update feedback manager question');
    //Bidding data
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedback_manager_question/get_feedback_manager_question_by_id/"+id,
        data: {},
        success: function(data){
            var response = $.parseJSON(data);
            $('#feedback_manager_id').val(response.feedback_manager_id);
            $('#question_id').val(response.question_id);
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
            url: BASE_URL + "feedback_manager_question/delete/"+id,
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
    try{
        var response = $.parseJSON(data);
        if(response.status == "success"){
            open_message_block("success", response.message);
            form_reset();
        }else if(response.status == "error"){
            open_message_block("error", response.message);
        }
    }catch (xhr){
        console.error("Exception: " + xhr.message);
    }finally{
        //Show tab list team
        $('#tab-2').removeClass('active');
        $('#tab_form').removeClass('active');
        $('#tab_list').addClass('active');
        $('#tab-1').addClass('active');
        //Refresh data
        list_refresh();
    }
}

function list_refresh(){
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedback_manager_question",
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
