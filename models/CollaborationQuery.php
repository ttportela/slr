<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * CollaborationQuery represents the model behind the search form of `app\models\Collaboration`.
 */
class CollaborationQuery extends ActiveQuery
{
    
    // conditions appended by default (can be skipped)
    public function my()
    {
        return $this->joinWith("project")->andWhere(['project.user_id' => Yii::$app->user->identity->id])->orWhere(['collaboration.user_id' => Yii::$app->user->identity->id]);
    }
    
}
