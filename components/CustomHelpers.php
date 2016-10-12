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
}