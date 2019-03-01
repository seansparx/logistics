<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	* Name:  Twilio
	*
	* Author: Ben Edmunds
	*		  ben.edmunds@gmail.com
	*         @benedmunds
	*
	* Location:
	*
	* Created:  03.29.2011
	*
	* Description:  Twilio configuration settings.
	*
	*
	*/

	/**
	 * Mode ("sandbox" or "prod")
	 **/
	//$config['mode']   = 'sandbox';
        $config['mode']   = (TWILIO_MODE=='1')?'prod':'sandbox';
	/**
	 * Account SID
	 **/
	//$config['account_sid']   = 'AC6b74ba806f7bf85ab4ebbcf44a287017';
        $config['account_sid']   = TWILIO_SID;
	/**
	 * Auth Token
	 **/
	//$config['auth_token']    = 'f222dd6943d36736bcbe8be8530a69ed';
        $config['auth_token']=TWILIO_AUTH_TOKEN;
	/**
	 * API Version
	 **/
	$config['api_version']   = '2010-04-01';

	/**
	 * Twilio Phone Number
	 **/
	$config['number']        = '+15005550006';


/* End of file twilio.php */