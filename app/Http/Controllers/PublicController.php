<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use CustomForm;

class PublicController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showForm($custom_form_id)
    {
        $data = [];
        $data['custom_form'] = CustomForm::with(['customFormField.customFormFieldOptions'])
            ->where('custom_form_id', $custom_form_id)
            ->first();

        if ($data['custom_form']) {
            return view('forms.show', $data);
        }

        return view('welcome');
    }
}
