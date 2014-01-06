function form_validate(){
    var email = $('#email');
    var first_name = $('#first_name');
    var last_name = $('#last_name');
    var department_id = $('#department_id');
    var team_id = $('#team_id');

    if(email.val() == ""){
        open_message_block("error", "E-mail is required!");
        email.focus();
        return false;
    } else if(first_name.val() == ""){
        open_message_block("error", "First name is required!");
        first_name.focus();
        return false;
    } else if(last_name.val() == ""){
        open_message_block("error", "Last name is required!");
        last_name.focus();
        return false;
    }else if(department_id.val() == ""){
        open_message_block("error", "Department is required!");
        department_id.focus();
        return false;
    }else if(team_id.val() == ""){
        open_message_block("error", "Team is required!");
        team_id.focus();
        return false;
    }
    return true;
}

function form_reset(){
    $('#tab_form a').html('Create new employee');
    $('#btnSubmit').html('Create employee');
    $('#email').val("");
    $('#first_name').val("");
    $('#last_name').val("");
    $('#department_id').val("");
    $('#team_id').val("");
}

function edit_record(id){
    //Set id for row
    $('#row_edit_id').val(id);
    //Set action for submit
    $('#action').val('edit');
    $('#tab_form a').html('Edit employee');
    $('#btnSubmit').html('Edit employee');
    //Bidding data
    $.ajax({
        type: "POST",
        url: BASE_URL + "employee/get_feedback_employee_by_id/"+id,
        data: {},
        success: function(data){
            var response = $.parseJSON(data);
            $('#title').val(response.title);
            $('#apply_id').val(response.apply_id);
            $('#department_id').val(response.department_id);
            $('#description').val(response.description);
            $('#date').val(response.date);
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
            url: BASE_URL + "feedback_employee/delete/"+id,
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
            //Show tab list team
            $('#tab-2').removeClass('active');
            $('#tab_form').removeClass('active');
            $('#tab_list').addClass('active');
            $('#tab-1').addClass('active');
        }else if(response.status == "warning"){
            open_message_block("warning", response.message);
            form_reset();
            //Show tab list team
            $('#tab-2').removeClass('active');
            $('#tab_form').removeClass('active');
            $('#tab_list').addClass('active');
            $('#tab-1').addClass('active');
        }else if(response.status == "error"){
            open_message_block("error", response.message);
        }
    }catch (xhr){
        console.error("Exception: " + xhr.message);
    }finally{
        //Refresh data
        //list_refresh();
    }
}

function list_refresh(){
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedback_employee",
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


function get_user_by_department(){
    var department_id = $('#department_id').val();
    if((department_id != null) && (department_id != "")){
        $.ajax({
            type: 'POST',
            url: BASE_URL + "feedback_employee/get_users_by_department",
            data: { department_id: department_id },
            success: function(data){
                data = data.trim();
                if(data != ""){
                    data = $.parseJSON(data);
                    var rows = "";
                    for (var i = 0; i < data.length; i++) {
                        rows += '<option value="'+ data[i].user_id +'">'+ data[i].username +'</option>';
                    };
                    $('#apply_id').html(rows);
                }
            },
            error: function(xhr){
                console.log("Error: " + xhr.message);
            }
        });
    }
}