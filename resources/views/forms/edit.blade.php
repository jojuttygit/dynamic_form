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
                    <form id="dynamic-form" name="dynamic-form" action="#" method="post">
                        @csrf
                        {{ method_field('PUT') }}
                        <input type="hidden" id="form_id" value="{{$custom_form->custom_form_id}}">
                        <div class="form-group row">
                            <div class="col-lg-2">
                                <label for="label" class="col-form-label">Form title</label>
                            </div>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" name="form_title" placeholder="form title" 
                                    value="{{$custom_form->name}}">
                                <span class="text-danger" id="error-form_title"></span>
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
                url: "{{route('forms.create-field')}}",
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
            let url = "{{route('forms.update', ['form' => ':id' ])}}";
            url = url.replace(':id', $('#form_id').val());
            $.ajax({
                type: "post",
                url: url,
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success == true) {
                        sweetAlertModal('success', 'Updated, form is loading..');
                        let url = "{{route('public.show-form', [ 'custom_form_id' =>  ':id' ])}}";
                        url = url.replace(':id', response.data.form_id);
                        setTimeout(function() {
                            window.location.href = url;
                        }, 2000);
                    } else {
                        console.log(response.errors);
                        showErrors(response.errors);
                    }
                },
                error: function(error) {
                    sweetAlertModal('error', 'Failed');
                }
            });
        });
    });
</script>

@endsection
