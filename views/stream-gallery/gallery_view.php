<?php
use yii\helpers\Html;
use humhub\modules\gallery\widgets\CustomGalleryContent;
use humhub\modules\gallery\widgets\StreamGalleryContent;

$bundle = \humhub\modules\gallery\Assets::register($this);
$this->registerJsVar('galleryMediaUploadUrl', 'unused');
?>
<div id="galleryContainer" class="panel panel-default">
    <?php echo Html::beginForm(null, null, ['data-target' => '#globalModal', 'id' => 'gallery-form']); ?>
    <div class="panel-body">
        <h1><strong><?php echo Html::encode($gallery->title); ?></strong></h1>
        <div class="row">
            <div class="col-sm-12 gallery-description">
                <?php echo Html::encode($gallery->description); ?><br />
            </div>
        </div>
        <div class="row button-action-menu">
            <div class="col-sm-4">
                <a class="btn btn-default" data-target="#globalModal"
                    href="<?php echo $this->context->contentContainer->createUrl('edit', ['open-gallery-id' => $gallery->id, 'item-id' => $gallery->getItemId()]); ?>">Edit
                    Gallery</a>
            </div>
        </div>
        <div class="row">
            <div id="logContainer" class="col-sm-12" style="display: none">
                <ul class="alert alert-danger">
                </ul>
            </div>
            <?php echo StreamGalleryContent::widget([ 'gallery' => $gallery, 'context' => $this->context ]); ?>
        </div>
    </div>
    <?php echo Html::endForm(); ?>
</div>