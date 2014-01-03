<table class="table table-striped">
    <thead>
    <tr>
        <th><input class="check-all" type="checkbox" value="" name="action_to_all"></th>
        <th>{{ helper:lang line="feedback_manager:form_title" }}</th>
        <th>{{ helper:lang line="feedback_manager:form_description" }}</th>
        <th>{{ helper:lang line="feedback_manager:form_start_date" }}</th>
        <th>{{ helper:lang line="feedback_manager:form_end_date" }}</th>
        <th>{{ helper:lang line="feedback_manager:form_type_id" }}</th>
        <th>{{ helper:lang line="feedback_manager:form_require" }}</th>
        <th>{{ helper:lang line="feedback_manager:form_status" }}</th>
        <th>{{ helper:lang line="global:action" }}</th>
    </tr>
    </thead>
    <tbody>
    {{ if posts }}
        {{ posts }}
        <tr>
            <td><input type="checkbox" value="{{ id }}" name="action_to[]"></td>
            <td>{{ title }}</td>
            <td>{{ description }}</td>
            <td>{{ start_date }}</td>
            <td>{{ end_date }}</td>
            <td>{{ type }}</td>
            <td>{{ require }}</td>
            <td>{{ status }}</td>
            <td>
                <a href="javascript:void(0);" onclick="list_record({{ id }})" title="List question" class="button">List question</a> |
                <a href="javascript:void(0);" onclick="edit_record({{ id }})" title="{{ helper:lang line="global:edit" }}" class="button">{{ helper:lang line="global:edit" }}</a> |
                <a href="javascript:void(0);" onclick="delete_record({{ id }})" title="{{ helper:lang line="global:delete" }}" class="button confirm">{{ helper:lang line="global:delete" }}</a>
            </td>
        </tr>
        {{ /posts }}
    {{ else }}
        <tr><td colspan="9">{{ helper:lang line="feedback_manager:currently_no_posts" }}</td></tr>
    {{ endif }}
    </tbody>
</table>
<button type="submit" name="btnAction" class="btn-u btn-u-red" value="delete" disabled="disabled">Delete</button>
{{ pagination }}