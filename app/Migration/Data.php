<?php

namespace App\Migration;


class Data
{
    public static function addData($data, $columnName, $model)  {
        foreach ($data as $datum) {
            $model = new $model();
            $model->setAttribute($columnName, $datum);
            $model->save();
        }
    }  
}
