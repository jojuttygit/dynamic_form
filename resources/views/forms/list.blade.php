@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Forms') }}</div>
                <div class="card-body">
                    <div class="col-lg-12 table-responsive">
                        <table class="table table-bordered" id="editable">
                            <thead>
                                <tr>
                                    <th>Srno</th>
                                    <th>Name</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($forms)
                                    @foreach ($forms as $form)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <a href="{{route('public.show-form', ['custom_form_id' => $form->custom_form_id])}}">
                                                {{ ucfirst($form->name) }}
                                            </a>
                                        </td>
                                        <td>{{ date("F jS, Y", strtotime($form->created_at)) }}</td>
                                        <td>
                                            <a href="{{route('forms.edit', ['form' => $form->custom_form_id])}}">edit</a>
                                        </td>                                 
                                    </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                        @if(count($forms) === 0)
                            <h5>No Result Found</h5>
                        @endif
                    </div>
                    {{ $forms->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
