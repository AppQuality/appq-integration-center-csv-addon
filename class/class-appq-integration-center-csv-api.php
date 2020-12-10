<?php
class CSVRestApi extends IntegrationCenterRestApi
{
    private $_CAMPAIGN_ID;

    function __construct( $cp_id ) {
        $this->_CAMPAIGN_ID = $cp_id;
        
        parent::__construct( $cp_id, "csv", "Csv Export" );
    }
}