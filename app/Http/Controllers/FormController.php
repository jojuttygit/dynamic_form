<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use DB, CustomForm, CustomFormField, CustomFormFieldOption, FieldType, Log;

class FormController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = CustomForm::select('custom_form_id', 'name', 'created_at')
            ->where('user_id', auth()->user()->id)
            ->simplePaginate(config('constant.pagination'));
        $data = [
            'forms' => $forms
        ];
        
        return view('forms.list', $data);
    }

    /**
     * Show the form for creating a new field.
     *
     * @return \Illuminate\Http\Response
     */
    public function createField(Request $request)
    {
        $data = [];
        
        $field_types = FieldType::all();
        $data['field_types'] = $field_types;
        $data['form_value'] = $request->form_value;

        return view('forms.includes.field', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $field_types = FieldType::all();
        $data['field_types'] = $field_types;
        $data['form_value'] = 1;

        return view('forms.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = false;
        $message = 'Fail to create the form';
        $data = [];

        $validator = Validator::make($request->all(), [
            'form_title' => 'required|string',
            'field_types' => 'required|array|min:1',
            'field_types.*' => 'required|exists:field_types,slug',
            'labels' => 'required|array|min:1',
            "labels.*"  => "required|string|",
            'field_select_box_options' => 'sometimes|array',
            "field_select_box_options.*"  => "required_if:field_types.*,select|string",
        ]);

        $field_types = FieldType::pluck('field_type_id', 'slug')->all();

        if ($validator->fails()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'errors'  => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();
            $custom_form = new CustomForm;
            $custom_form->user_id = auth()->user()->id;
            $custom_form->name = $request->form_title;
            $custom_form->save();
            
            foreach ($request->labels as $key => $label) {
                $custom_form_fields = new CustomFormField;
                $custom_form_fields->custom_form_id = $custom_form->custom_form_id;
                $custom_form_fields->field_type_id = $field_types[$request->field_types[$key]];
                $custom_form_fields->label = $label;
                $custom_form_fields->save();

                if ($custom_form_fields->field_type_id == FieldType::SELECT) {
                    $select_box_options = explode(",", $request->field_select_box_options[$key]);

                    foreach ($select_box_options as $option) {
                        $custom_form_field_option = new CustomFormFieldOption;
                        $custom_form_field_option->custom_form_field_id = $custom_form_fields->custom_form_field_id;
                        $custom_form_field_option->option_value = $option;
                        $custom_form_field_option->save();
                    }
                }
            }

            DB::commit();
            $success = true;
            $data = [
                'form_id' => $custom_form->custom_form_id
            ];
            $message = 'Form successfully created';
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($message, $request->all());
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($custom_form_id)
    {
        $data = [];
        $field_types = FieldType::all();
        $data['field_types'] = $field_types;
        $data['form_value'] = 1;
        $data['custom_form'] = CustomForm::with(['customFormField.customFormFieldOptions'])
            ->where('custom_form_id', $custom_form_id)
            ->first();

        return view('forms.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->request->add(['custom_form_id' => $id]);

        $success = false;
        $message = 'Fail to update the form';
        $data = [];

        $validator = Validator::make($request->all(), [
            'custom_form_id' => 'required|exists:custom_forms,custom_form_id',
            'form_title' => 'required|string',
            'field_types' => 'required|array|min:1',
            'field_types.*' => 'required|exists:field_types,slug',
            'labels' => 'required|array|min:1',
            "labels.*"  => "required|string|",
            'field_select_box_options' => 'sometimes|array',
            "field_select_box_options.*"  => "required_if:field_types.*,select|string",
        ]);

        $field_types = FieldType::pluck('field_type_id', 'slug')->all();

        if ($validator->fails()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'errors'  => $validator->errors()
            ]);
        }

        $custom_form = CustomForm::find($id);

        try {
            DB::beginTransaction();
            $custom_form->name = $request->form_title;
            $custom_form->save();

            foreach ($custom_form->customFormField as $custom_form_field) {
                $custom_form_field->customFormFieldOptions()->delete();
            }

            $custom_form->customFormField()->delete();
            
            foreach ($request->labels as $key => $label) {
                $custom_form_fields = new CustomFormField;
                $custom_form_fields->custom_form_id = $custom_form->custom_form_id;
                $custom_form_fields->field_type_id = $field_types[$request->field_types[$key]];
                $custom_form_fields->label = $label;
                $custom_form_fields->save();

                if ($custom_form_fields->field_type_id == FieldType::SELECT) {
                    $select_box_options = explode(",", $request->field_select_box_options[$key]);

                    foreach ($select_box_options as $option) {
                        $custom_form_field_option = new CustomFormFieldOption;
                        $custom_form_field_option->custom_form_field_id = $custom_form_fields->custom_form_field_id;
                        $custom_form_field_option->option_value = $option;
                        $custom_form_field_option->save();
                    }
                }
            }

            DB::commit();
            $success = true;
            $data = [
                'form_id' => $custom_form->custom_form_id
            ];
            $message = 'Form successfully updated';
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($message, $request->all());
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
