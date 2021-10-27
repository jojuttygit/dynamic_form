function removeField(form_field_value) {
    $("#form-group-" + form_field_value).remove();
}

function changeInFieldType(form_field_value) {
    let field_type_properties = $('#field-type-properties-' + form_field_value);
    field_type_properties.empty();
    let field_type = $('#field-type-' + form_field_value).val();
    let html = '';

    if (field_type == 'select') {
        html = '<textarea class="form-control" placeholder="Add options separated ' + 
            'by comma" name="field_select_box_options[' + form_field_value + ']"></textarea>';
    }

    field_type_properties.append(html);
}

function sweetAlertModal(icon_type, title) {
    swal.fire({
        position: 'top-end',
        icon: icon_type,
        title: title,
        showConfirmButton: false,
        timer: 2000
    })
}

function showErrors(errors, id = null) {
    $.each(errors, function(field, error) {
        if (id) {
            $("#error-" + field + '_' + id).text(error);
        } else {
            $("#error-" + field).text(error);
        }
    });
}