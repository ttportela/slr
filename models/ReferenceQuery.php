<?php
// file CommentQuery.php
namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use app\models\ProjectQuery;

class ReferenceQuery extends ActiveQuery
{
    
    
//    public function init()
//    {
//        $this->joinWith("project")->andOnCondition(['user_id' => Yii::$app->user->identity->id]);
////        $this->joinWith([
////            'project' => function ($query) {
////                $query->andOnCondition(['user_id' => Yii::$app->user->identity->id]);
////            },
////        ]);
//        parent::init();
//    }
    public function init()
    {
//        $this->orderBy(['id'=>SORT_DESC]);
        parent::init();
    }
    
    public function byYear()
    {
        return $this->orderBy(['year'=>SORT_DESC]);
    }
    
    public function actualProject()
    {
        /*if (Yii::$app->session->get('project') !== null) {
            $this->where(['project_id' => ProjectQuery::actualProject()]);//->andOnCondition(['project_id' => ProjectQuery::actualProject()]);
        }*/
        return $this->where(['project_id' => ProjectQuery::actualProject()]);
    }
    
    public function project($id)
    {
        return $this->andWhere(['project_id' => $id]);
    }

    // ... add customized query methods here ...
    public function active($state = true)
    {
        return $this->andWhere(['active' => $state]);
    }

    // ... add customized query methods here ...
    public function bib($state = true)
    {
        return $this->andWhere(['active' => $state])->orderBy(['category'=>SORT_ASC, 'year'=>SORT_ASC]);
    }
    
}