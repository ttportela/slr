<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Qualis extends Model 
{
    public $issn;
    public $titulo;
    public $area;
    public $extrato;
    
    public static $data;
    
    public static function data()
    {
        $input = utf8_encode(file_get_contents(Yii::getAlias('@app/data/qualis.xls', "\t")));
        $s = preg_split( '/(\n|\t)/', $input);
        $s = array_chunk($s, 4);
        unset($s[0]);
        
        $data = [];
        foreach ($s as $item) {
            $o = new Qualis();
            $o->issn = $item[0];
            $o->titulo = $item[1];
            $o->area = $item[2];
            $o->extrato = trim($item[3]);
            $data[] = $o;
        }
        
        return $data;
    }
    
    public static function find()
    {
        return Qualis::$data;
    }
    
    public function rules()
    {
        return [
            [['issn','titulo','area','extrato'], 'string'],   
            ['issn' => 'id'],         
        ];
    }
}