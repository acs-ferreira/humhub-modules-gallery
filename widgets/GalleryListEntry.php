<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use humhub\modules\file\models\File;
use humhub\modules\gallery\libs\FileUtils;
use humhub\modules\gallery\models\BaseGallery;
use humhub\modules\gallery\models\Media;
use humhub\modules\gallery\models\SquarePreviewImage;
use \yii\base\Widget;
use \Yii;

/**
 * Widget that renders an entry inside a list in the gallery module
 *
 * @package humhub.modules.gallery.widgets
 * @since 1.0
 * @author Sebastian Stumpf
 */
class GalleryListEntry extends Widget
{

    public $entryObject;
    public $parentGallery;

    public function run()
    {
        if ($this->entryObject instanceof Media) {
            $metaData = $this->getMediaMetaData($this->entryObject);
        } elseif ($this->entryObject instanceof File) {
            $metaData = $this->getFileMetaData($this->entryObject);
        } elseif ($this->entryObject instanceof BaseGallery) {
            $metaData = $this->entryObject->getMetaData();
        } else {
            return;
        }

        $metaData['creatorUrl'] = !empty($metaData['creator']) ? $metaData['creator']->createUrl() : '';
        $metaData['creatorThumbnailUrl'] = !empty($metaData['creator'])  ? $metaData['creator']->getProfileImage()->getUrl() : '';
        $metaData['uiGalleryId'] = $this->parentGallery ? "GalleryModule-Gallery-" . $this->parentGallery->id : '';

        return $this->render('galleryListEntry', $metaData);
    }

    private function getMediaMetaData(Media $media)
    {
        $contentContainer = Yii::$app->controller->contentContainer;

        return [
            'creator' => '', // not in use
            'title' => $media->description,
            'wallUrl' => $media->getWallUrl(),
            'deleteUrl' => $contentContainer->createUrl('/gallery/custom-gallery/delete-multiple', ['openGalleryId' => $this->parentGallery->id, 'itemId' => $media->getItemId()]),
            'editUrl' => $contentContainer->createUrl('/gallery/media/edit', ['openGalleryId' => $this->parentGallery->id, 'itemId' => $media->getItemId()]),
            'downloadUrl' =>$media->getUrl(true),
            'fileUrl' => $media->getUrl(),
            'thumbnailUrl' => $media->getSquarePreviewImageUrl(),
            'writeAccess' => Yii::$app->controller->canWrite(false),
            'contentObject' => $media,
            'footerOverwrite' => false,
            'alwaysShowHeading' => false,
            'imagePadding' => ''
        ];
    }

    private function getFileMetaData(File $file)
    {
        $contentObject = FileUtils::getBaseObject($file);
        $content = $contentObject->content;

        return [
            'creator' => '', // not in use
            'title' => $contentObject->message,
            'wallUrl' => $content->getUrl(),
            'deleteUrl' => '',
            'editUrl' => '',
            'downloadUrl' => $file->getUrl(['download' => true]),
            'fileUrl' =>  $file->getUrl(),
            'thumbnailUrl' => SquarePreviewImage::getSquarePreviewImageUrlFromFile($file),
            'writeAccess' => false,
            'contentObject' => $contentObject,
            'footerOverwrite' => false,
            'alwaysShowHeading' => false,
            'imagePadding' => ''
        ];
    }
}
