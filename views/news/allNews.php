<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'All News';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Create News', Url::toRoute(['news/create']), ['class' => 'btn btn-success']) ?>
    </p>
</div>
<body>
<?php
$all_news = $dataProvider->query->all();
?>
<div class="container text-center">
    <div class="row">
        <?php
        if (!empty($all_news)):
            foreach ($all_news as $news): ?>
                <div class="col-sm-4">
                    <a href="<?php echo Url::toRoute(['news/view', 'id' => $news->id]) ?>">
                        <h3><?php echo $news->title; ?></h3>
                        <img src="<?php echo $news->image; ?>" class="img-responsive" style="width:100%" alt="Image">
                        <p><?php echo $news->description; ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<br>

