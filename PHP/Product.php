<?php
	class Product {
		private $name;
		private $manufacturer;
		private $model;
		private $family;
		private $announcedDate;
		
		public function __construct($stdClass) {
			if ( property_exists($stdClass, 'product_name') ) {
				$this->name = $stdClass->product_name;
			}
			if ( property_exists($stdClass, 'manufacturer') ) {
				$this->manufacturer = $stdClass->manufacturer;
			}
			if ( property_exists($stdClass, 'model') ) {
				$this->model = $stdClass->model;
			}
			if ( property_exists($stdClass, 'family') ) {
				$this->family = $stdClass->family;
			}
			if ( property_exists($stdClass, 'announced-date') ) {
				$this->announcedDate = $stdClass->{"announced-date"};
			}
		}
		
		public function __toString() {
			return $this->name . $this->manufacturer . $this->model . $this->family . $this->announcedDate;
		}
		
		public function __get($propertyName) {
			switch ($propertyName) {
				case 'name': return $this->name;
				case 'manufacturer': return $this->manufacturer;
				case 'model': return $this->model;
				case 'family': return $this->family;
				default: trigger_error('Cannot access private property ' . __CLASS__ . '::$' . $propertyName, E_USER_ERROR);
			}
		}
	}
?>