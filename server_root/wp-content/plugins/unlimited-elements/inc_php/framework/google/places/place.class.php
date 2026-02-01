<?php

class UELM_GoogleAPIPlace extends UELM_GoogleAPIModel{

	/**
	 * Get the reviews.
	 *
	 * @return UELM_GoogleAPIPlaceReview[]
	 */
	public function getReviews(){

		$reviews = $this->getAttribute("reviews", array());
		$reviews = UELM_GoogleAPIPlaceReview::transformAll($reviews);

		return $reviews;
	}

}
