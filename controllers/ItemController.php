<?php

namespace bariew\eventModule\controllers;

use Yii;
use bariew\eventModule\models\Item;
use bariew\eventModule\models\ItemSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Activates/deactivates model.
     * @param integer $id model id.
     * @param integer 1/0 $active whether to activate model.
     */
    public function actionActivate($id, $active)
    {
        $model = Item::findOne($id);
        $model->attributes = compact('active');
        $model->save();
    }
    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionTree($type, $id, $model_id = false)
    {
        ob_start();
        $model = $model_id ? Item::findOne($model_id) : new Item();
        $tree = $model->$type();
        if ($id == '#') {
            $id = false;
            $items = $tree;
        } else {
            $path = explode('\\', $id);
            $items = $tree;
            foreach ($path as $name) {
                $items = &$items[$name];
            }
        }
        ob_clean();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $model->createJsonTreeItems($type, $items, $id);
    }

    /**
     * Returns json event list for form DepDrop widget.
     */
    public function actionTriggerEvents()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ($post = Yii::$app->request->post('depdrop_parents'))
            ? Item::eventList($post[0])
            : [];
        $output = [];
        foreach ($result as $id => $name) {
            $output[] = compact('id', 'name');
        }
        echo Json::encode(['output' => $output, 'selected' => '']);
    }

    /**
     * Returns json handler method list for form DepDrop widget.
     */
    public function actionHandlerMethods()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ($post = Yii::$app->request->post('depdrop_parents'))
            ? Item::methodList($post[0])
            : [];
        $output = [];
        foreach ($result as $id => $name) {
            $output[] = compact('id', 'name');
        }
        echo Json::encode(['output' => $output, 'selected' => '']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTest()
    {
        return CommonTree::widget();
    }
}
