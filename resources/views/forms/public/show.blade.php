@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <h2>{{$custom_form->name}} </h2>
                    <hr>
                    <form id="public-form" name="public-form" method="post">
                        @csrf
                        @foreach ($custom_form->customFormField as $custom_form_field)
                            <div class="form-group row">
                                <div class="col-lg-2">
                                    <label for="label" class="col-form-label">{{ $custom_form_field->label }}</label>
                                </div>
                                <div class="col-lg-4">
                                    @if ($custom_form_field->field_type_id == FieldType::TEXT)
                                        <input type ="text" class="form-control">
                                    @elseif ($custom_form_field->field_type_id == FieldType::NUMBER)
                                        <input type ="text" class="form-control">
                                    @elseif ($custom_form_field->field_type_id == FieldType::SELECT)
                                        <select class="form-control">
                                            @foreach ($custom_form_field->customFormFieldOptions as $option_value)
                                                <option>
                                                    {{$option_value->option_value}}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
