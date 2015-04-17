<?php 
class Google_Snippets_Block_Snippets extends Mage_Core_Block_Template
{
	const XML_PATH_GOOGLE_SNIPPETS_ENABLED = 'googlesnippets/settings/enabled';
	const XML_PATH_GOOGLE_SNIPPETS_PRODUCT_IMAGE = 'googlesnippets/settings/product_image';
	const XML_PATH_GOOGLE_SNIPPETS_PRODUCT_DESCRIPTION = 'googlesnippets/settings/product_description';
	const XML_PATH_GOOGLE_SNIPPETS_PRODUCT_RATINGS = 'googlesnippets/settings/product_ratings';
	const XML_PATH_GOOGLE_SNIPPETS_PRODUCT_PRICE = 'googlesnippets/settings/product_price';
	const XML_PATH_GOOGLE_SNIPPETS_PRODUCT_AVAILABILITY = 'googlesnippets/settings/product_availability';
	const XML_PATH_GOOGLE_SNIPPETS_PRODUCT_SKU = 'googlesnippets/settings/product_sku';
	
	public function isEnabled() {
		return Mage::getStoreConfig(self::XML_PATH_GOOGLE_SNIPPETS_ENABLED);
	}

	public function showProductImage() {
		return Mage::getStoreConfig(self::XML_PATH_GOOGLE_SNIPPETS_PRODUCT_IMAGE);
	}

	public function enableDescription() {
		return Mage::getStoreConfig(self::XML_PATH_GOOGLE_SNIPPETS_PRODUCT_DESCRIPTION);
	}

	public function enableRatings() {
		return Mage::getStoreConfig(self::XML_PATH_GOOGLE_SNIPPETS_PRODUCT_RATINGS);
	}

	public function enablePrice() {
		return Mage::getStoreConfig(self::XML_PATH_GOOGLE_SNIPPETS_PRODUCT_PRICE);
	}

	public function enableAvailability() {
		return Mage::getStoreConfig(self::XML_PATH_GOOGLE_SNIPPETS_PRODUCT_AVAILABILITY);
	}

	public function enableSku() {
		return Mage::getStoreConfig(self::XML_PATH_GOOGLE_SNIPPETS_PRODUCT_SKU);
	}

	public function getProduct() {
		return Mage::registry('product');
	}
}
