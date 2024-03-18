<?php

namespace app\models;

use Yii;
use dektrium\user\models\User;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 *
 * @property User $user
 * @property Reference[] $references
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'user_id' => Yii::t('app', 'User'),
        ];
    }
    
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }
    
    public function beforeValidate() {
        if ($this->isNewRecord) {
            $this->user_id = Yii::$app->user->identity->id;
        }
        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferences()
    {
        return $this->hasMany(Reference::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaboration()
    {
        return $this->hasOne(Collaboration::className(), ['project_id' => 'id'])->andOnCondition(['collaboration.user_id' => Yii::$app->user->identity->id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborations()
    {
        return $this->hasMany(Collaboration::className(), ['project_id' => 'id'])->andOnCondition(['collaboration.user_id' => Yii::$app->user->identity->id]);
    }
}
