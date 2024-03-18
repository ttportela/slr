<?php

namespace app\models;

use Yii;
use dektrium\user\models\User;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

/**
 * This is the model class for table "reference".
 *
 * @property int $id
 * @property int $user_id
 * @property int $active
 * @property string $bib
 * @property string $category
 * @property string $base
 * @property string $title
 * @property string $author
 * @property string $citation
 * @property int $year
 * @property string $publisher
 * @property string $abstract
 * @property string $description
 * @property string $threat
 * @property string $conclusion
 * @property string $data
 * @property string $comment
 * @property string $exclusion
 *
 * @property User $user
 */
class Reference extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reference';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id'], 'required'],
//            [['user_id'], 'default', 'value'=> Yii::$app->user->identity->id,'on'=>'insert'],
            [['project_id', 'active', 'year'], 'integer'],
            [['bib', 'citation', 'abstract', 'classification', 'validation','concept','strategy', 'datasource', 'objective', 'description', 'threat', 'conclusion', 'data', 'comment', 'exclusion'], 'string'],
            [['category', 'base', 'title', 'author', 'publisher'], 'string', 'max' => 255],
            [['active'], 'default', 'value'=> 1,'on'=>'insert'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['id', 'project_id', 'base', 'title', 'author', 'publisher', 'active', 'year','bib', 'citation', 'abstract', 'classification', 'validation','concept','strategy', 'datasource', 'objective', 'description', 'threat', 'conclusion', 'data', 'comment', 'exclusion'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project'),
            'active' => Yii::t('app', 'Included'),
            'bib' => Yii::t('app', 'BibTex Entry'),
            'category' => Yii::t('app', 'Category'),
            'base' => Yii::t('app', 'Base'),
            'title' => Yii::t('app', 'Title'),
            'author' => Yii::t('app', 'Authors'),
            'cite' => Yii::t('app', 'Citation'),
            'citation' => Yii::t('app', 'Cite'),
            'year' => Yii::t('app', 'Year'),
            'publisher' => Yii::t('app', 'Publisher'),
            'abstract' => Yii::t('app', 'Abstract'),
            'classification' => Yii::t('app', 'Class'),
            'validation' => Yii::t('app', 'Validation'),
            'concept' => Yii::t('app', 'Concepts'),
            'strategy' => Yii::t('app', 'Strategies'),
            'datasource' => Yii::t('app', 'Sources of Data'),
            'objective' => Yii::t('app', 'Research End'),
            'description' => Yii::t('app', 'Description'),
            'threat' => Yii::t('app', 'Threats to Validity'),
            'conclusion' => Yii::t('app', 'Conclusions'),
            'data' => Yii::t('app', 'Data for Research Question'),
            'comment' => Yii::t('app', 'Comments'),
            'exclusion' => Yii::t('app', 'Exclusion Reason'),
        ];
    }
    
    public function getCite()
    {
        return str_replace(' and',';', $this->author) . '. ' .
               $this->title . '. <i>' .
               $this->publisher . '</i>. ' .
               $this->year . '.';
    }
    
    public function search($params, $query) {
        
//        var_dump($params); die;
//        $sort = new Sort([
//            'attributes' => [
//                $params->sort,
//            ],
//        ]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=>['id'=>SORT_DESC]]
        ]);

        //$this->load($params);
        if (!($this->load($params) && $this->validate())) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
////            'project_id' => $this->project_id,
////            'active' => $this->active,
//            'bib' => $this->bib,
//            'base' => $this->base,
//            'title' => $this->title,
//            'author' => $this->author,
//            'citation' => $this->citation,
//            'year' => $this->year,
//            'publisher' => $this->publisher,
//            'abstract' => $this->abstract,
//            'description' => $this->description,
//            'threat' => $this->threat,
//            'conclusion' => $this->conclusion,
//            'data' => $this->data,
//            'comment' => $this->comment,
//            'exclusion' => $this->exclusion,
//        ]);

        $query
//                ->andFilterWhere(['like', 'project_id', $this->project_id])
//                ->andFilterWhere(['like', 'active', $this->active])
                ->andFilterWhere(['like', 'bib', $this->bib])
                ->andFilterWhere(['like', 'category', $this->category])
                ->andFilterWhere(['like', 'base', $this->base])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'author', $this->author])
                ->andFilterWhere(['like', 'citation', $this->citation])
                ->andFilterWhere(['like', 'year', $this->year])
                ->andFilterWhere(['like', 'publisher', $this->publisher])
                ->andFilterWhere(['like', 'abstract', $this->abstract])
                ->andFilterWhere(['like', 'classification', $this->classification])
                ->andFilterWhere(['like', 'validation', $this->validation])
                ->andFilterWhere(['like', 'concept', $this->concept])
                ->andFilterWhere(['like', 'strategy', $this->strategy])
                ->andFilterWhere(['like', 'datasource', $this->datasource])
                ->andFilterWhere(['like', 'objective', $this->objective])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'threat', $this->threat])
                ->andFilterWhere(['like', 'conclusion', $this->conclusion])
                ->andFilterWhere(['like', 'data', $this->data])
                ->andFilterWhere(['like', 'comment', $this->comment])
                ->andFilterWhere(['like', 'exclusion', $this->exclusion]);

        return $dataProvider;
    }
    
    public static function find()
    {
        return new ReferenceQuery(get_called_class());
    }
    
    public function beforeValidate() {
        if ($this->isNewRecord) {
            $this->project_id = ProjectQuery::actualProject();
        }
        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
    
    public function isActive()
    {
        return $this->active == 1;
    }
}
