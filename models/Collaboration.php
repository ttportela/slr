<?php

namespace app\models;

use Yii;
use dektrium\user\models\User;

/**
 * This is the model class for table "collaboration".
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property int $role
 *
 * @property Project $project
 * @property User $user
 */
class Collaboration extends \yii\db\ActiveRecord
{
    
    public static $ROLE_VIEW = 1;
    public static $ROLE_EDIT = 2;

    public $username;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'collaboration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id', 'role'], 'required'],
            [['project_id', 'user_id', 'role'], 'integer'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['username'],'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['username' => 'username']],
            ['project_id', 'unique', 'targetAttribute' => ['project_id', 'user_id']]
        ];
    }

    public function my_required($attribute_name, $params)
    {
        $user = User::findByCondition(['username' => $this->username]);
        if (empty($this->username)) {
            $this->addError($attribute_name, Yii::t('app', 'The user is required. Choose an username.'));
            return false;
        } else if (empty($user)) {
            $this->addError($attribute_name, Yii::t('app', 'The user does not exist.'));
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project'),
            'user_id' => Yii::t('app', 'User'),
            'username' => Yii::t('app', 'User'),
            'role' => Yii::t('app', 'Role'),
        ];
    }
    
    public static function find()
    {
        return new CollaborationQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function beforeValidate(){
        if ($this->isNewRecord) {
            $user = User::findByCondition(['username' => $this->username])->one();
            $this->user_id = isset($user)? $user->id : '';
        }
        return parent::beforeValidate();
    }
}
