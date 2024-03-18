<?php
namespace app\models;

use Yii;
use yii\db\ActiveQuery;

class ProjectQuery extends ActiveQuery
{
    
    /* conditions appended by default (can be skipped)
    public function init()
    {
//        $this->andWhere(['user_id' => Yii::$app->user->identity->id]);
        $this->joinWith("collaborations c")->andWhere(['or',
           ['project.user_id'=>Yii::$app->user->identity->id],
           ['c.user_id'=>Yii::$app->user->identity->id]
        ]);
        parent::init();
    }*/
    
    public function my()
    {
        if (Yii::$app->user->isGuest)
            $id = null;
        else 
            $id = Yii::$app->user->identity->id;
        return $this->andOnCondition(['user_id' => $id]);
    }
    
    public function collaborations() {
        return $this->joinWith("collaborations")->where(['collaboration.user_id' => Yii::$app->user->identity->id]);
    }
    
    public function mineOrOthers() {
        return $this->joinWith("collaborations")
            ->andWhere(['or',
               ['project.user_id' => Yii::$app->user->identity->id],
               ['collaboration.user_id' => Yii::$app->user->identity->id]
            ]);
            //->where(['collaboration.user_id' => Yii::$app->user->identity->id]);
    }    
    
    public static function actualProject() {
        $cookie = Yii::$app->request->cookies;
        if ($cookie->has('project') && $cookie->get('project')->value !== null) {
            $project_id = $cookie->get('project')->value;
            if (static::hasAccess($project_id))
                return $project_id;
            else
                return false;
        } else {
            return false;
        }
    }  
    
    public static function findActualProject() {
        $project_id = static::actualProject();
        if ($project_id && !Yii::$app->user->isGuest) {
            $project = Project::findOne($project_id);
            return $project;
        }
        return false;
    } 
    
    public static function setActualProject($project) {
        $cookies = Yii::$app->response->cookies;

        // adicionar um novo cookie a resposta que será enviado
        $cookies->add(new \yii\web\Cookie([
            'name' => 'project',
            'value' => $project->id,
        ]));
//        $cookies->add(new \yii\web\Cookie([
//            'name' => 'project_name',
//            'value' => $project->name,
//        ]));
//        Yii::$app->request->cookies->set('project',$project->id);
//        Yii::$app->request->cookies->set('project_name',$project->name);
    }
    
    public static function accessView() {
        $project_id = static::actualProject();
        return static::hasAccess($project_id);
    }
    
    public static function accessEdit() {
        $project_id = static::actualProject();
//        echo var_dump(Project::findOne($project)->collaboration); die;
        if ($project_id && !Yii::$app->user->isGuest) {
            $project = Project::findOne($project_id);
            if ($project->collaboration->user_id == Yii::$app->user->identity->id &&
                $project->collaboration->role == Collaboration::$ROLE_EDIT)
                    return true;
        }
        return false;
    }
    
    public static function hasAccess($project_id) {
        if ($project_id && !Yii::$app->user->isGuest) {
            $project = Project::findOne($project_id);
            //echo print_r($project); die;
            if ($project->user_id == Yii::$app->user->identity->id ||
                ($project->collaboration->user_id == Yii::$app->user->identity->id &&
                ($project->collaboration->role == Collaboration::$ROLE_EDIT ||
                $project->collaboration->role == Collaboration::$ROLE_VIEW)))
                    return true;
        }
        return false;
    }
    
    public static function owner() {
        $project_id = static::actualProject();
        return static::mine($project_id);
    }
    
    public static function mine($project_id) {
        if ($project_id && !Yii::$app->user->isGuest
           && Project::findOne($project_id)->user_id == Yii::$app->user->identity->id) {
            return true;
        }
        return false;
    }
    
}