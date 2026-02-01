<?php

if ( ! defined( 'ABSPATH' ) ) exit;


class UELM_UniteProviderCoreFrontUC_Elementor extends UELM_UniteProviderFrontUC{
	
	private $objFiltersProcess;

	
	/**
	 *
	 * the constructor
	 */
	public function __construct(){
		
		UELM_HelperProviderCoreUC_EL::globalInit();
		
		//run front filters process
		
		$this->objFiltersProcess = new UELM_UniteCreatorFiltersProcess();
		$this->objFiltersProcess->initWPFrontFilters();
		
		
		/*
		$disableFilters = UELM_HelperProviderCoreUC_EL::getGeneralSetting("disable_autop_filters");
		$disableFilters = UELM_UniteFunctionsUC::strToBool($disableFilters);
		
		if($disableFilters == true)
			$this->disableWpFilters();
		*/
		
		parent::__construct();
						
	}
	
	
}
