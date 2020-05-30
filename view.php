<?php defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\File\File;

/* @var $image File */

$ih = Core::make('helper/image');

if ($image) {

	$thumb = $ih->getThumbnail($image, $width, $height, $crop);
	?>
    <a class="zoomImage" href="<?= $image->getURL(); ?>" title="<?= h($image->getTitle()); ?>">
        <img class="img-responsive" src="<?= $thumb->src; ?>" width="<?= $width; ?>"
             height="<?= $height; ?>"
             title="<?= h($image->getTitle()) ?>" alt="<?= h($image->getTitle()) ?>"/></a>
<?php }?>
