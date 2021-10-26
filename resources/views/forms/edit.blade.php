@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Form') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-success" id="add_more">+</button>
                        </div>
                    </div>
                    <form id="dynamic-form" name="dynamic-form" action="{{ route('forms.store') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-2">
                                <label for="label" class="col-form-label">Form title</label>
                            </div>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" name="form_title" placeholder="form title" 
                                    value="{{$custom_form->name}}">
                                @error('form_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div id="dynamic-field">
                        @foreach ($custom_form->customFormField as $custom_form_field)
                            @php
                                $form_value = $loop->index + 1;
                            @endphp
                            @include('forms.includes.edit_field')
                        @endforeach
                        </div>
                        <div class="row">
                            <div class="col-lg-10 text-right">
                                <button class="btn btn-primary" type="submit" id="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '#add_more', function(e) {
            let field_count = $(".form-field-label").last().attr('id');
            $.ajax({
                type: 'GET',
                url: 'create-field',
                data: {'form_value' : parseInt(field_count) + 1},
                success: function (response) {
                    $('#dynamic-field').append(response);
                },
                error: function(error) { 
                    console.log(error);
                }
            });
        });

        $('#dynamic-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "{{ route('forms.store') }}",
                data: $(this).serialize(),
                success: function(response) {
                    sweetAlertModal('success', 'Success, form is loading..');
                    let url = "{{route('public.show-form', [ 'custom_form_id' =>  ':id' ])}}";
                    url = url.replace(':id', response.data.form_id);
                    setTimeout(function() {
                        window.location.href = url;
                    }, 2000);
                },
                error: function(error) {
                    sweetAlertModal('error', 'Failed');
                }
            });
        });
    });

    function removeField(form_field_value) {
        $("#form-group-" + form_field_value).remove();
    }

    function changeInFieldType(form_field_value) {

        console.log($("#test1234"));

        let field_type_properties = $('#field-type-properties-' + form_field_value);
        let field_type = $('#field-type-' + form_field_value).val();
        let html = '';

        if (field_type == 'select') {
            html = '<textarea class="form-control" id="field-select-box-options-" ' + form_field_value + ' placeholder="Add options separated ' + 
                'by comma" name="field_select_box_options[' + form_field_value + ']"></textarea>';
        }

        field_type_properties.append(html);
    }

    function sweetAlertModal(icon_type, title) {
        swal.fire({
            position: 'top-end',
            icon: icon_type,
            title: title,
            showConfirmButton: confirm,
            timer: 2000
        })
    }

</script>

@endsection
