<?php
         /*
         |--------------------------------------------------------------------------------
         | Lang settings for routes
         |--------------------------------------------------------------------------------
         | We create it dynamically at  addion or updation of any language from admin end
         |
         */
	$route["^en/(.+)$"] = "$1";
	$route["^no/(.+)$"] = "$1";
	$route["^en$"] = $route["default_controller"];
	$route["^no$"] = $route["default_controller"];
?>