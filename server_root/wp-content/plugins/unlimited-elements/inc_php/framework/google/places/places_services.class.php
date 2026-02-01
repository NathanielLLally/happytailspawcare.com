<?php

/**
 * @link https://developers.google.com/maps/documentation/places/web-service/overview
 */
class UELM_GoogleAPIPlacesService extends UELM_GoogleAPIClient{

	/**
	 * Get the place details.
	 *
	 * @param string $placeId
	 * @param array $params
	 *
	 * @return UELM_GoogleAPIPlace
	 */
	public function getDetails($placeId, $params = array()){
		
		$params["place_id"] = $placeId;
		
		$lang = UELM_UniteFunctionsUC::getVal($params, "lang");
		
		if(!empty($lang))
			$params["language"] = $lang;
		else
			$params["reviews_no_translations"] = true;
		
		$response = $this->get("/details/json", $params);
		
		
		$response = UELM_GoogleAPIPlace::transform($response["result"]);
		
		return $response;
	}

	/**
	 * Get the base URL for the API.
	 *
	 * @return string
	 */
	protected function getBaseUrl(){

		return "https://maps.googleapis.com/maps/api/place";
	}

}
