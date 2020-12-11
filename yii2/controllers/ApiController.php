<?php


namespace app\controllers;


use app\components\response\PostCollection;
use app\models\Posts;
use app\models\Users;
use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ApiController extends Controller
{
    public function actionIndex($userId = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = $userId ?: '5eb6d2c3e3ea8e2b33505787';
        $parts = Yii::$app->request->get('parts', []);
        $limit = Yii::$app->request->get('limit', 50);
        $offset = Yii::$app->request->get('offset', 0);
        if (!$user = Users::findOne($userId)) {
            throw new NotFoundHttpException('User not found');
        }
        return Yii::$app->cache->getOrSet([
            'news',
            'userId' => $userId,
            'parts' => $parts,
            'limit' => $limit,
            'offset' => $offset,
        ], static function () use ($user, $parts, $limit, $offset) {
            $postsQuery = Posts::getWithoutOldPosts(
                $user,
                $limit,
                $offset, false, false, null, '', $parts);
            return (new PostCollection($postsQuery))->forNews($user->id);
        }, 120);
    }
}
