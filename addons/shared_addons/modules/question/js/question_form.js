/**
 * Created by liemtd on 12/27/13.
 */

function form_validate(){
    var title = $('#title');
    var description = $('#description');

    if(title.val() == ""){
        open_message_block("error", "Title is required!");
        title.focus();
        return false;
    } else if(description.val() == ""){
        open_message_block("error", "Description is required!");
        description.focus();
        return false;
    }
    return true;
}

function form_reset(){
    $('#tab_form a').html('Create new question');
    $('#btnSubmit').html('Create question');
    $('#title').val("");
    $('#description').val("");
}

function edit_record(id){
    //Set id for row
    $('#row_edit_id').val(id);
    //Set action for submit
    $('#action').val('edit');
    $('#tab_form a').html('Edit question');
    $('#btnSubmit').html('Edit question');
    //Bidding data
    $.ajax({
        type: "POST",
        url: BASE_URL + "question/get_question_by_id/"+id,
        data: {},
        success: function(data){
            var response = $.parseJSON(data);
            $('#title').val(response.title);
            $('#description').val(response.description);
            //Show tab form question
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
            url: BASE_URL + "question/delete/"+id,
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
        //Show tab list question
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
        url: BASE_URL + "question",
        data: {},
        success: function(data){
            $('#filter-result').html(data);
        },
        error: function(xhr){
            console.log("Error: " + xhr.message);
        }
    });
}
