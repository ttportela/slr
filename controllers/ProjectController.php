<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Project;
use app\models\ProjectQuery;
use app\models\Reference;
use app\models\Collaboration;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
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
                        'actions' => ['index','included','excluded'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['update','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return ProjectQuery::mine($_GET['id']);
                        }
                    ],
                    [
                        'actions' => ['view','select','bib'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return ProjectQuery::mine($_GET['id']) 
                                || ProjectQuery::hasAccess($_GET['id']);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Project::find()->my(),
        ]);
        $dataProviderCollab = new ActiveDataProvider([
            //'query' => Project::find()->collaborations(),
            'query' => Collaboration::find()->my(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderCollab' => $dataProviderCollab,
        ]);
    }

    /**
     * Displays a single Project model.
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

    public function actionSelect($id)
    {
        $model = $this->findModel($id);
        
        ProjectQuery::setActualProject($model);
        return $this->redirect(['reference/index', 'id'=>$model->id]);
    }

    public function actionBib($id)
    {
        $model = Reference::find()->project($id)->bib()->all();
        $down = false;
        if (isset($_GET['down']) && $_GET['down'] == '1') {
            $down = true;
        }
        $bibtex = "";
        $cat = "";
        foreach ($model as $r) {
            if ($cat != $r->category) {
                $cat = trim($r->category);
                $bibtex = $bibtex. "%%% " . $cat . " %%%" . ($down? "<br/><br/>" : "\n\n");
            }

            $bibtex = $bibtex. $this->formatBib($r->bib, $down);
        }
        
        if ($down) {
            $this->layout='';
        } else {
            header('Content-Type:text/plain; charset=UTF-8');
            header('Content-Disposition: attachment; filename="references.bib"');
//            ob_start();
//            echo($bibtex);
//            return ob_get_clean();
        }
//        Yii::app()->end();
        ob_start();
        echo($bibtex);
        return ob_get_clean();
    }

    private function formatBib($bib, $download)
    {
        $bib = trim($bib);

        if ($download) {
            $line_break = "<br/>";
            $bib = str_replace("\n",$line_break."\n",$bib);
        } else {
            $line_break = "\n";
        }

        return $bib . $line_break . $line_break;
    }
    
    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
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
     * Deletes an existing Project model.
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
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::find()->mineOrOthers()->where(['project.id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
