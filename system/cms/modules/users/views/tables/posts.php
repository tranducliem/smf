
<table class="table table-striped">
    <thead>
    <tr>
        <th><input class="check-all" type="checkbox" value="" name="action_to_all"></th>
        <th>{{ helper:lang line="employee:form_first_name" }}</th>
        <th>{{ helper:lang line="employee:form_last_name" }}</th>
        <th>{{ helper:lang line="employee:form_department" }}</th>
        <th width="120">{{ helper:lang line="global:action" }}</th>
    </tr>
    </thead>
    <tbody>
    {{ if posts }}
        {{ posts }}
            <tr>
                <td><input type="checkbox" value="{{ id }}" name="action_to[]"></td>
                <td>{{ first_name }}</td>
                <td>{{ last_name }}</td>
                <td>{{ department }}</td>
                <td>
                    <a href="javascript:void(0);" onclick="detail_record({{ id }})" title="{{ helper:lang line="global:view" }}" class="button">{{ helper:lang line="global:view" }}</a> |
                    <a href="javascript:void(0);" onclick="delete_record({{ id }})" title="{{ helper:lang line="global:delete" }}" class="button confirm">{{ helper:lang line="global:delete" }}</a>
                </td>
            </tr>
        {{ /posts }}
    {{ else }}
        <tr><td colspan="8">{{ helper:lang line="employee:currently_no_posts" }}</td></tr>
    {{ endif }}
    </tbody>
</table>
<button type="submit" name="btnAction" class="btn-u btn-u-red" value="delete" disabled="disabled">Delete</button>
{{ pagination }}
