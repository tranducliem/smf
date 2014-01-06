function form_validate() {
    var title = $('#title');
    var type_id = $('#type_id');
    var description = $('#description');
    var start_date = $('#start_date');
    var end_date = $('#end_date');
    var require = $('#require');
    var status = $('#status');
    var user_id = $('#user_id');

    if (title.val() == "") {
        open_message_block("error", "Title is required!");
        title.focus();
        return false;
    } else if (type_id.val() == "") {
        open_message_block("error", "Type is required!");
        type_id.focus();
        return false;
    } else if (start_date.val() == "") {
        open_message_block("error", "Start date is required!");
        start_date.focus();
        return false;
    } else if (end_date.val() == "") {
        open_message_block("error", "End date is required!");
        end_date.focus();
        return false;
    }
    else if (description.val() == "") {
        open_message_block("error", "Description is required!");
        description.focus();
        return false;
    }
    else if (require.val() == "") {
        open_message_block("error", "Require is required!");
        require.focus();
        return false;
    }
    else if (status.val() == "") {
        open_message_block("error", "Status is required!");
        status.focus();
        return false;
    }
    else if(user_id.val() == ""){
        open_message_block("error", "User is required!");
        user_id.focus();
        return false;
    }
    return true;
}

function form_reset() {
    $('#tab_form1 a').html('Create new feedback manager');
    $('#btnSubmit').html('Create feedback manager');
    $('#title').val("");
    $('#type_id').val("");
    $('#start_date').val("");
    $('#end_date').val("");
    $('require').val("");
    $('status').val("");
    $('#description').val("");
    $('#user_id').val("");
}

function edit_record(id) {
    //Set id for row
    $('#row_edit_id').val(id);
    //Set action for submit
    $('#action').val('edit');
    $('#tab_form1 a').html('Edit feedback manager');
    $('#btnSubmit').html('Edit feedback manager');
    //Bidding data
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedback_manager/get_feedback_manager_by_id/" + id,
        data: {},
        success: function(data) {
            var response = $.parseJSON(data);
            $('#title').val(response.title);
            $('#type_id').val(response.type_id);
            $('#start_date').val(response.start_date);
            $('#end_date').val(response.end_date);
            $('#require').val(response.require);
            $('#status').val(response.status);
            $('#description').val(response.description);
            $('#user_id').val(response.user_id);
            //Show tab form feedback manager
            $('#tab-1').removeClass('active');
            $('#tab_list').removeClass('active');
            $('#tab-3').removeClass('active');
            $('#tab_form2').removeClass('active');
            $('#tab_form1').addClass('active');
            $('#tab-2').addClass('active');
        },
        error: function(xhr) {
            console.log("Error: " + xhr.message);
        }
    });
}

function list_record(id) {
    // Bidding data
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedback_manager/get_question_manager/" + id,
        data: {},
        success: function(data) {
            data = data.trim();
            if (data != "")
            {
                var response = $.parseJSON(data);
                var rows = '';
                for (var i = 0; i < response.length; i++)
                {
                    rows += '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + '<a href="javascript:void(0);" onclick="statistics(' + response[i].question_id + ')" title="List question" class="button">' + response[i].question_title + '</a>' + '</td>'+
                            '<td>' + response[i].question_description + '</td>' +
                            '<td id ="question_' + response[i].question_id + '"></td></tr>';
                }
                $('#question-result tbody').html(rows);
                for (var k = 0; k < response.length; k++)
                {
                    var num=k;
                    $.ajax({
                        type: "POST",
                        url: BASE_URL + "feedback_manager/statistic/" + response[num].question_id,
                        data: {},
                        success: function(data_sts) {
                            data_sts = $.parseJSON(data_sts);
                            var col= '';
                            for (var j = 0; j < data_sts["count_answer"]; j++) {
                                col += data_sts["statistics"][j]["name"]+' -> '+ Math.round(data_sts["statistics"][j]["percent"]*data_sts["count_users"])+'</br>';
                            }
                            $('#question_'+data_sts['question_id']).html(col);
                        },
                        error: function(xhr) {
                            console.log("Error: " + xhr.message);
                        }
                    });
                }
            }
            //Show tab form feedback manager
            $('#tab-1').removeClass('active');
            $('#tab_list').removeClass('active');
            $('#tab-2').removeClass('active');
            $('#tab_form1').removeClass('active');
            $('#tab_form3').removeClass('active');
            $('#tab-4').removeClass('active');
            $('#tab_form2').addClass('active');
            $('#tab-3').addClass('active');
        },
        error: function(xhr) {
            console.log("Error: " + xhr.message);
        }
    });
}

function delete_record(id) {
    var con = confirm("Are you sure ?");
    if (con) {
        $.ajax({
            type: "POST",
            url: BASE_URL + "feedback_manager/delete/" + id,
            data: {},
            success: function(data) {
                var response = $.parseJSON(data);
                if (response.status == "success") {
                    open_message_block("success", response.message);
                    list_refresh();
                } else if (response.status == "warning") {
                    open_message_block("warning", response.message);
                } else if (response.status == "error") {
                    open_message_block("error", response.message);
                }
            },
            error: function(xhr) {
                console.log("Error: " + xhr.message);
            }
        });
    }
}

function form_success(data) {
    try {
        var response = $.parseJSON(data);
        if (response.status == "success") {
            open_message_block("success", response.message);
            form_reset();
        } else if (response.status == "error") {
            open_message_block("error", response.message);
        }
    } catch (xhr) {
        console.error("Exception: " + xhr.message);
    }finally{
        //Show tab list feedback manager
        $('#tab-2').removeClass('active');
        $('#tab_form1').removeClass('active');
        $('#tab_form2').removeClass('active');
        $('#tab-3').removeClass('active');
        $('#tab_form3').removeClass('active');
        $('#tab-4').removeClass('active');
        $('#tab_list').addClass('active');
        $('#tab-1').addClass('active');
        //Refresh data
        list_refresh();
    }
}

function list_refresh() {
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedback_manager",
        data: {},
        success: function(data) {
            $('#filter-result').html(data);
            //Init
            App.initDeleteButton();
        },
        error: function(xhr) {
            console.log("Error: " + xhr.message);
        }
    });
}
