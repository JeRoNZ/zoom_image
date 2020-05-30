<?php namespace Application\Block\ZoomImage;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = ['width'];
    protected $btExportFileColumns = ['image'];
    protected $btTable = 'btZoomImage';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btIgnorePageThemeGridFrameworkContainer = false;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $pkg = false;
    
    public function getBlockTypeDescription()
    {
        return t("Zoom Image");
    }

    public function getBlockTypeName()
    {
        return t("Zoom Image");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->width;
        $content[] = $this->height;
        return implode(" ", $content);
    }

    public function view()
    {
        
        if ($this->image && ($f = File::getByID($this->image)) && is_object($f)) {
            $this->set("image", $f);
        } else {
            $this->set("image", false);
        }
        $crop_options = [
            '0' => "No",
            '1' => "Yes"
        ];
        $this->set("crop_options", $crop_options);
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
    }

    protected function addEdit()
    {
        $this->set("crop_options", [
                '0' => "No",
                '1' => "Yes"
            ]
        );
        $this->requireAsset('core/file-manager');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        $args['width'] = trim($args['width']) != "" ? number_format(floatval(str_replace(',', '.', $args['width'])), 2, ".", "") : "";
        $args['height'] = trim($args['height']) != "" ? number_format(floatval(str_replace(',', '.', $args['height'])), 2, ".", "") : "";
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("image", $this->btFieldsRequired) && (trim($args["image"]) == "" || !is_object(File::getByID($args["image"])))) {
            $e->add(t("The %s field is required.", t("Image")));
        }
        if (trim($args['width']) != "") {
            $args['width'] = str_replace(',', '.', $args['width']);
            
        } elseif (in_array("width", $this->btFieldsRequired)) {
            $e->add(t("The %s field is required.", t("Thumbnail Width")));
        }
        if (trim($args['height']) != "") {
            $args['height'] = str_replace(',', '.', $args['height']);
            
        } elseif (in_array("height", $this->btFieldsRequired)) {
            $e->add(t("The %s field is required.", t("Thumbnail height")));
        }
        if ((in_array("crop", $this->btFieldsRequired) && (!isset($args["crop"]) || trim($args["crop"]) == "")) || (isset($args["crop"]) && trim($args["crop"]) != "" && !in_array($args["crop"], ["0", "1"]))) {
            $e->add(t("The %s field has an invalid value.", t("Crop")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }


	public function registerViewAssets($outputContent = '') {
		$this->requireAsset('javascript', 'jquery');

		$content=<<<HERE

$(document).ready(function () {
	if (typeof $.fn.fancyZoom === 'function'){ 
		$('a.zoomImage').fancyZoom();
	}
});

HERE;
		$this->addFooterItem('<script>' . $content . '</script>');
	}
}