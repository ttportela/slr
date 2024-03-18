<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Reference;
use app\models\ReferenceQuery;
use app\models\ProjectQuery;

/**
 * ReferenceController implements the CRUD actions for Reference model.
 */
class ReferenceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return ProjectQuery::accessView();
                        }
//                        'expression' => ProjectQuery::accessView(),
                    ],
                    [
                        'actions' => ['update','create','print','printall'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return ProjectQuery::owner() || ProjectQuery::accessEdit();
                        }
//                        'expression' => ProjectQuery::accessEdit(),
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return ProjectQuery::owner();
                        }
//                        'expression' => ProjectQuery::accessEdit(),
                    ],
                    [
                        'actions' => ['index','included','excluded'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return ProjectQuery::accessView();
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Reference models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$searchModel = new Reference();
    	
    	
    	$dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            Reference::find()->actualProject()
        );
      
        /*$dataProvider = $searchModel->search(
            Yii::$app->request->get(), 
            $searchModel::find()->actualProject());*/
    
    	
    
    
        /*$dataProvider = new ActiveDataProvider([
            'query' => Reference::find()->actualProject(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);*/
        
        /*$searchModel = new Reference();
      
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            $searchModel::find()->actualProject());*/
    
        return $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Reference models.
     * @return mixed
     */
    public function actionPrint()
    {
        $searchModel = new Reference();
      
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            $searchModel::find()->actualProject()->active()->byYear());
        
        $dataProvider->pagination = false;
        
        $this->layout='';
        
        ob_start();
        echo($this->renderPartial('print', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]));
        return ob_get_clean();
    }

    /**
     * Lists all Reference models.
     * @return mixed
     */
    public function actionPrintall()
    {
        $searchModel = new Reference();
      
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            $searchModel::find()->actualProject()->active()->byYear());
        
        $dataProvider->pagination = false;
        
        $this->layout='';
        
        ob_start();
        echo($this->renderPartial('printall', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]));
        return ob_get_clean();
    }

    /**
     * Lists all Reference models.
     * @return mixed
     */
    public function actionIncluded()
    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Reference::find()->active()->actualProject(),
//        ]);
//
//        return $this->render('index', [
//            'dataProvider' => $dataProvider,
//        ]);
        $searchModel = new Reference();
      
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            $searchModel::find()->actualProject()->active());
//        $dataProvider->sort = ['defaultOrder' => ['year'=>SORT_ASC]];
    
        return $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Reference models.
     * @return mixed
     */
    public function actionExcluded()
    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Reference::find()->active(false)->actualProject(),
//        ]);
//
//        return $this->render('index', [
//            'dataProvider' => $dataProvider,
//        ]);
        $searchModel = new Reference();
      
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams, 
            $searchModel::find()->actualProject()->active(false));
    
        return $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reference model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Reference model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!ProjectQuery::actualProject()) {
            Yii::$app->session->setFlash('error', "Select a project on menu or create one before continue.");
            return $this->redirect(['index']);
        }
        
        $model = new Reference();
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Reference model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Reference model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Reference model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reference the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reference::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
