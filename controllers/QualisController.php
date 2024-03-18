<?php 
namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\Qualis;
use app\components\CsvDataProvider;
use yii\data\ArrayDataProvider;

class QualisController extends Controller
{
    
    public static $data;
    
    public function beforeAction($action)
    {
      if(!isset(self::$data))
        self::data();
      return parent::beforeAction($action);
    }
    
    public static function data()
    {
        $input = file_get_contents(Yii::getAlias('@app/data/qualis.xls', "\t"));
        $input = mb_convert_encoding($input, 'UTF-8', 'ISO-8859-1');
        $input = str_replace(['"', chr(19), chr(25)], '', $input);
        $input = preg_split( '/(\n|\t)/', $input);
        $input = array_chunk($input, 4);
        unset($input[0]);
        
//        echo '<pre>';
//        print_r($input);
//        echo '</pre>'; die;
    
        $data = [];
        foreach ($input as $item) {
            $o = new Qualis();
            $o->issn = $item[0];
            $o->titulo = $item[1];
            $o->area = $item[2];
            $o->extrato = trim($item[3]);
            $data[] = $o;
        }
        
        self::$data = $data;
    }
    
    public function actionIndex($page=0)
    {
        return array_slice(self::$data, (10*$page), (10*$page)+10);
    }
    
    public function actionView($q=null)
    {
        if (isset($_GET['id']))
                return self::$data[$_GET['id']];
        else if (isset($_GET['issn'])) {
            $q = $_GET['issn'];
            foreach (self::$data as $item) {
                if ($item->issn == $q)
                    return $item;
            }
        } else if (isset($_GET['titulo'])) {
            $q = strtolower($_GET['titulo']);
            foreach (self::$data as $item) {
                if (strtolower($item->titulo) == $q)
                    return $item;
            }
        } else if (isset($_GET['q'])) {
            $q = strtolower($_GET['q']);
            $r = null;
            $percent = -1;
            foreach (self::$data as $item) {
                similar_text(strtolower($item->titulo), $q, $aux); 
                if ($aux > $percent) {
                    $percent = $aux;
                    $r = $item;
                    if ($percent >= 100) break;
                }
                
            }
            return $r;
        }
        return null;
    }
}