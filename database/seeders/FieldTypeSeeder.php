<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB, FieldType;

class FieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $field_types = [
            'Text',
            'Number',
            'Select'
        ];

        FieldType::truncate();
        DB::beginTransaction();

        try {
            foreach ($field_types as $field_type) {
                $model_field_type = new FieldType;
                $model_field_type->name = $field_type;
                $model_field_type->slug = Str::slug($field_type , "-");
                $model_field_type->save();
            }
            DB::commit();
            $this->command->info('Field Types Successfully Inserted');
        } catch (Exception $e) {
            DB::rollBack();
            $this->command->info('Failed to Insert Field Types');
        }
    }
}
