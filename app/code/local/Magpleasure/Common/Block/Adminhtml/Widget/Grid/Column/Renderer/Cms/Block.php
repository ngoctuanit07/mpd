<?php
/**
 * MagPleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * MagPleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   MagPleasure
 * @package    Magpleasure_Common
 * @version    0.6.0
 * @copyright  Copyright (c) 2012-2013 MagPleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Common_Block_Adminhtml_Widget_Grid_Column_Renderer_Cms_Block
    extends Magpleasure_Common_Block_Adminhtml_Widget_Grid_Column_Renderer_Abstract
{

    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $blockId = $this->_getValue($row);
        if ($blockId) {
            $html = "";
            /** @var Mage_Cms_Model_Block $block  */
            $block = Mage::getModel('cms/block')->load($blockId);
            $title = $block->getTitle();
            $url = $this->getUrl('adminhtml/cms_block/edit', array('id'=>$blockId));
            $html .= "<a href=\"{$url}\" target=\"_blank\">{$title}</a>";
            return $html;
        } else {
            return $this->_commonHelper()->__("N/A");
        }
    }



}