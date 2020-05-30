<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php
    if (isset($image) && $image > 0) {
        $image_o = File::getByID($image);
        if (!is_object($image_o)) {
            unset($image_o);
        }
    } ?>
    <?php echo $form->label($view->field('image'), t("Image")); ?>
    <?php echo isset($btFieldsRequired) && in_array('image', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-zoom_image-image-' . $identifier_getString, $view->field('image'), t("Choose Image"), $image_o); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('width'), t("Thumbnail Width")); ?>
    <?php echo isset($btFieldsRequired) && in_array('width', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('width'), $width, []); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('height'), t("Thumbnail height")); ?>
    <?php echo isset($btFieldsRequired) && in_array('height', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('height'), $height, []); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('crop'), t("Crop")); ?>
    <?php echo isset($btFieldsRequired) && in_array('crop', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->select($view->field('crop'), $crop_options, $crop, []); ?>
</div>