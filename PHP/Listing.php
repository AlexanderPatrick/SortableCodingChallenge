<?php
	class Listing implements JsonSerializable {
		private $title;
		private $manufacturer;
		private $currency;
		private $price;
		
		public function __construct($stdClass) {
			if ( property_exists($stdClass, 'title') ) {
				$this->title = $stdClass->title;
			}
			if ( property_exists($stdClass, 'manufacturer') ) {
				$this->manufacturer = $stdClass->manufacturer;
			}
			if ( property_exists($stdClass, 'currency') ) {
				$this->currency = $stdClass->currency;
			}
			if ( property_exists($stdClass, 'price') ) {
				$this->price = $stdClass->price;
			}
		}
		
		public function __toString() {
			return $this->title . $this->manufacturer . $this->currency . $this->price;
		}
		
		public function __get($propertyName) {
			switch ($propertyName) {
				case 'title': return $this->title;
				default: trigger_error('Cannot access private property ' . __CLASS__ . '::$' . $propertyName, E_USER_ERROR);
			}
		}
		
		public function jsonSerialize() {
			return array('title'=>$this->title, 'manufacturer'=>$this->manufacturer, 'currency'=>$this->currency, 'price'=>$this->price);
		}
	}
?>