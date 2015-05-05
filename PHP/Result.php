<?php
	class Result implements JsonSerializable {
		private $productName;
		private $listings;
		
		public function __construct($productName) {
			$this->productName = $productName;
			$this->listings = array();
		}
		
		public function jsonSerialize() {
			return array('product_name'=>$this->productName, 'listings'=>$this->listings);
		}
		
		public function addListing($listing) {
			array_push($this->listings, $listing);
		}
	}
?>