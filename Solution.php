<?php
	include 'PHP/Product.php';
	include 'PHP/Listing.php';
	include 'PHP/Result.php';
	define('DEBUG', 1);

	class Solution {
		public function __construct() {
		}
		
		public function __invoke($productsFileName, $listingsFileName, $resultsFileName) {
			// Set Up
			{ 
				// from products.txt to an array of JSON objects
				$JSONProducts = $this->fromTextFileToJSONArray($productsFileName);
				
				// from listings.txt to an array of JSON objects
				$JSONListings = $this->fromTextFileToJSONArray($listingsFileName);
				
				
				
				// from array of JSON objects to Product array
				$products = array();
				foreach ($JSONProducts as $JSONProduct) {
					array_push( $products, new Product($JSONProduct) );
				}
				
				// from array of JSON objects to Listing array
				$listings = array();
				foreach ($JSONListings as $JSONListing) {
					array_push( $listings, new Listing($JSONListing) );
				}
				
				// debug check
				if (DEBUG) echo count($products) . " Products\n" . count($listings) . " Listings\n";
				
				// build Result array
				$results = array();
				foreach ($products as $product) {
					array_push( $results, new Result($product->name) );
				}
			}
			
			// TIME TO DO SOME MAGIC
			{
				$productsCount = count($products);
				$worstCaseIterations = count($listings) * count($products);
				$unmatchedCount = 0;
				$matchedCount = 0;
				foreach ($listings as $listingIndex=>$listing) {
					$matched = false;
					foreach($products as $productIndex=>$product) {
						echo $this->getProgressString( $listingIndex * count($products) + $productIndex, count($listings) * count($products) ) . "\tExplored:" . ($matchedCount + $unmatchedCount) . ", Matched:$matchedCount, Unmatched:$unmatchedCount";
						
						$manufacturerMatch = false;
						$familyMatch = false;
						$modelMatch = false;
						
						if (strpos($listing->title, $product->manufacturer) !== false) {
							$manufacturerMatch = true;
						}
						if ($product->family == NULL || strpos($listing->title, $product->family) !== false) {
							$familyMatch = true;
						}
						if (strpos($listing->title, $product->model) !== false) {
							$modelMatch = true;
						}
						
						if ($manufacturerMatch && $familyMatch && $modelMatch) {
							$results[$productIndex]->addListing($listing);
							$matched = true;
							$matchedCount++;
							break;
						}
					}
					if (!$matched) {
						$unmatchedCount++;
					}
				}
				echo "\n";
				
			}
		
			// Closing
			{
				// write results to file
				$resultsFile = fopen($resultsFileName,'w');
				if ($resultsFile) {
					foreach ($results as $result) {
						if (fwrite( $resultsFile, json_encode($result) . "\n" ) === FALSE) {
							echo "Error writing to $resultsFileName.";
							exit;
						}
					}
					fclose($resultsFile);
					echo "Output written to $resultsFileName.";
				} else {
					echo "The file \"$resultsFileName\" could not be opened for writing.";
					exit;
				}
			}
		}
		
		private function fromTextFileToJSONArray($filename) {
			$JSONArray = null;
			$file = @fopen($filename,'r');
			if ($file) {
				$JSONArray = array();
				while ( ( $line = fgets($file) ) !== false ) {
					array_push($JSONArray, json_decode($line));
				}
				fclose($file);
			} else {
				$JSONArray = null;
				echo "The file \"$filename\" could not be opened for reading.";
				exit;
			}
			return $JSONArray;
		}
		
		private function getProgressString($completed, $total) {
			static $startTime;
			
			if($completed > $total) return;

			if( empty($startTime) ) $startTime = time();
			$now = time();

			$perc=(double)($completed/$total);
			$disp=number_format($perc*100, 0);
			
			$outputString = "\r$disp%\t$completed/$total";

			if ($completed > 0) {
				$rate = ($now-$startTime)/$completed;
				$left = $total - $completed;
				$eta = round($rate * $left, 2);
				$elapsed = $now - $startTime;
				$outputString .= "\tRemaining:" . number_format($eta) . " sec. Elapsed:" . number_format($elapsed) . " sec.";
			}
			
			return $outputString;
		}

		public function help() {
			echo 'Usage: php Solution.php productsFileName listingsFileName resultsFileName';
		}
	}
	
	$solution = new Solution();
	if (count($argv) != 4) {
		$solution->help();
	} else {
		$solution($argv[1], $argv[2], $argv[3]);
	}
?>