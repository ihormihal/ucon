<?php

namespace yii\helpers;
use Yii;

class CustomHelpers
{
	public static function findObject($array, $prop, $value){
		$found = array_filter($array, function($item) use($prop, $value){
			return $item->$prop == $value;
		});
		return array_values($found)[0];
	}
    public static function autocompleteValues($input, $toJson = false){
        if(is_array($input)){
            $array = $input;
        }else{
            $array = json_decode($input, true);
        }
        $values = [];
        if($array){
            foreach($array as $key => $item){
                $values[] = ['value' => $item] ;
            }
        }
        if($toJson){
            return json_encode($values);
        }else{
            return $values;
        }
    }
}