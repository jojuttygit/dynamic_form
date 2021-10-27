<div class="form-group row" id="form-group-{{$form_value}}">
    <div class="col-lg-2">
        <label for="label" class="col-form-label">Label</label>
    </div>
    <div class="col-lg-4">
        <input class="form-control form-field-label" id="{{$form_value}}" type="text" name="labels[{{$form_value}}]" placeholder="field name">
        @error('labels.' . $form_value)
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-4">
        <select class="form-control" name="field_types[{{$form_value}}]" onchange="changeInFieldType({{$form_value}})" id="field-type-{{$form_value}}">
            @isset($field_types)
                @foreach ($field_types as $field_type)
                    <option value="{{$field_type->slug}}">
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
            
        </div>
    </div>
</div>