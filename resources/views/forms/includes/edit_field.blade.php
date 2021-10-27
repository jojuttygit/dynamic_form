<div class="form-group row" id="form-group-{{$form_value}}">
    <div class="col-lg-2">
        <label for="label" class="col-form-label">Label</label>
    </div>
    <div class="col-lg-4">
        <input class="form-control form-field-label" id="{{$form_value}}" type="text" 
            name="labels[{{$form_value}}]" placeholder="field name" value="{{$custom_form_field->label}}">
        <span class="text-danger" id="{{'error-labels.' . $form_value}}"></span>
    </div>
    <div class="col-lg-4">
        <select class="form-control" name="field_types[{{$form_value}}]" onchange="changeInFieldType({{$form_value}})" id="field-type-{{$form_value}}">
            @isset($field_types)
                @foreach ($field_types as $field_type)
                    <option value="{{$field_type->slug}}" {{ ($field_type->field_type_id == $custom_form_field->field_type_id) ? 'selected' : '' }}>
                        {{$field_type->name}}
                    </option>
                @endforeach
            @endisset
        </select>
    </div>
    <div class="col-lg-2">
        <button class="btn btn-danger" onclick="removeField({{$form_value}})" type="button">-</button>
    </div>
    <div class="form-group row mt-2">
        <div class="col-lg-10 offset-lg-7" id="field-type-properties-{{$form_value}}">
            @if ($custom_form_field->field_type_id == FieldType::SELECT)
                @php
                    $text_area = '';
                    foreach ($custom_form_field->customFormFieldOptions as $key => $option) {
                        $text_area = $text_area . $option->option_value . ',';
                    }
                @endphp
                <textarea class="form-control" id="field-select-box-options-{{$form_value}}" placeholder="Add options separated ' + 
                    'by comma" name="field_select_box_options[{{$form_value}}]">{{ rtrim($text_area,',') }}</textarea>
            @endif
        </div>
    </div>
</div>