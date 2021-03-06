<?php
/**
 * MageWorx
 * MageWorx SeoMarkup Extension
 *
 * @category   MageWorx
 * @package    MageWorx_SeoMarkup
 * @copyright  Copyright (c) 2015 MageWorx (http://www.mageworx.com/)
 */

class MageWorx_SeoMarkup_Helper_Json_Breadcrumbs extends Mage_Core_Helper_Abstract
{
    protected $_product;

    public function getJsonBreadcrumbsData($breadcrumbs)
    {
        if (!Mage::helper('mageworx_seomarkup/config')->isBreadcrumbsRichsnippetEnabled()) {
            return false;
        }

        if (!Mage::helper('mageworx_seomarkup/config')->isBreadcrumbsJsonLdMethod()) {
            return false;
        }

        if (!$breadcrumbs) {
            return false;
        }

        $crumbs = array_values($this->_getBreadcrumbs($breadcrumbs));

        if (!$crumbs) {
            return false;
        }

		$listitems = array();

        $data = array();
        $data['@context'] = 'http://schema.org';
        $data['@type']    = 'BreadcrumbList';

        for ($i = 0; $i < count($crumbs); $i++) {

            if (!empty($crumbs[$i]['link'])) {
                $listItem['@type']        = 'ListItem';
                $listItem['item']['@id']  = $crumbs[$i]['link'];
                $listItem['item']['name'] = $crumbs[$i]['label'];
                $listItem['position']     = $i;

                $listitems[] = $listItem;
            }
        }

        $data['itemListElement'] = $listitems;

        return $data;
	}

    protected function _getBreadcrumbs($breadcrumbs)
    {
        $cacheKeyInfo = $breadcrumbs->getCacheKeyInfo();
        if (!empty($cacheKeyInfo['crumbs'])) {
            $crumbs = unserialize(base64_decode($cacheKeyInfo['crumbs']));
        } else {
            $crumbs = array();
            switch (Mage::helper('mageworx_seomarkup')->getCurrentFullActionName()) {
                case 'catalog_product_view':
                case 'catalog_category_view':
                    $crumbs = $this->_getCatalogBreadcrumbs($crumbs);
                    break;
                default:
                    $crumbs = $this->_getHomeBreadcrumbs($crumbs);
                    break;
            }
        }

        return $crumbs;
    }

    protected function _getCatalogBreadcrumbs($crumbs)
    {
        $crumbs = $this->_getHomeBreadcrumbs($crumbs);
        $path   = Mage::helper('catalog')->getBreadcrumbPath();

        foreach ($path as $name => $breadcrumb) {
            $crumbs = $this->_addCrumb($name, $breadcrumb, $crumbs);
        }
        return $crumbs;
    }

    protected function _getHomeBreadcrumbs($crumbs)
    {
        $crumbs = $this->_addCrumb(
            'home',
            array(
                'label' => Mage::helper('catalog')->__('Home'),
                'title' => Mage::helper('catalog')->__('Go to Home Page'),
                'link'  => Mage::getBaseUrl()
            ),
            $crumbs
        );

        return $crumbs;
    }

    protected function _addCrumb($crumbName, $crumbInfo, $crumbs, $after = false)
    {
        $crumbInfo = $this->_prepareArray($crumbInfo, array('label', 'title', 'link', 'first', 'last', 'readonly'));
        if ((!isset($crumbs[$crumbName])) || (!$crumbs[$crumbName]['readonly'])) {
           $crumbs[$crumbName] = $crumbInfo;
        }
        return $crumbs;
    }

    /**
     * Set required array elements
     *
     * @param   array $arr
     * @param   array $elements
     * @return  array
     */
    protected function _prepareArray(&$arr, array $elements=array())
    {
        foreach ($elements as $element) {
            if (!isset($arr[$element])) {
                $arr[$element] = null;
            }
        }
        return $arr;
    }
}