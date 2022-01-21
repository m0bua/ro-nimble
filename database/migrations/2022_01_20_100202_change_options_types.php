<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOptionsTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('options')->whereIn('type', [
            'Integer',
            'Decimal',
            'ComboBox',
            'CheckBoxGroup',
            'List',
            'ListValues',
            'CheckBoxGroupValues',
            'CheckBox',
            'MultiText',
            'RichText',
            'RichTextVideo',
            'Suggest',
            'Text',
            'TextArea',
            'TextInput',
            'ColorPicker'
        ])
        ->update([
            'type' => DB::raw("CASE
                WHEN type = 'Integer' THEN 'number'
                WHEN type = 'Decimal' THEN 'number'

                WHEN type = 'CheckBox' THEN 'bool'

                WHEN type = 'List' THEN 'value'
                WHEN type = 'ComboBox' THEN 'value'
                WHEN type = 'ListValues' THEN 'value'
                WHEN type = 'CheckBoxGroup' THEN 'value'
                WHEN type = 'CheckBoxGroupValues' THEN 'value'

                ELSE 'text'
            END"
        )]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
