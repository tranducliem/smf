<table class="table table-striped">
    <thead>
    <tr>
        <th><input class="check-all" type="checkbox" value="" name="action_to_all"></th>
        <th>{{ helper:lang line="feedbackuser:user_name" }}</th>
        <th>{{ helper:lang line="feedbackuser:manager_title" }}</th>
        <th>{{ helper:lang line="feedbackuser:status" }}</th>
        <th>{{ helper:lang line="global:action" }}</th>
    </tr>
    </thead>
    <tbody>
    {{ posts }}
        <tr>
            <td><input type="checkbox" value="{{ id }}" name="action_to[]"></td>
            <td>{{ username }}</td>
            <td>{{ fbmanager }}</td>
            <td>{{ status }}</td>
            <td>
                <a href="javascript:void(0);" onclick="edit_record({{ id }})" title="{{ helper:lang line="global:edit" }}" class="button">{{ helper:lang line="global:edit" }}</a> |
                <a href="javascript:void(0);" onclick="delete_record({{ id }})" title="{{ helper:lang line="global:delete" }}" class="button confirm">{{ helper:lang line="global:delete" }}</a>
            </td>
        </tr>
    {{ /posts }}
    </tbody>
</table>
<button type="submit" name="btnAction" class="btn-u btn-u-red" value="delete" disabled="disabled">Delete</button>
{{ pagination }}
