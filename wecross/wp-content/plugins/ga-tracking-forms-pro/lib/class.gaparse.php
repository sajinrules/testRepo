<?php 
////////////////////////////////////////////////////
// GA_Parse - PHP Google Analytics Parser Class 
//
// Version 1.0
// Date: 17 September 2009
//
// Define a PHP class that can be used to parse 
// Google Analytics cookies currently with support
// for __utmz (campaign data) and __utma (visitor data)
//
// Author: Joao Correia, GAQI - http://joaocorreia.pt
//
// License: LGPL
//
////////////////////////////////////////////////////

class GA_Parse
{

  var $campaign_source;    	// Campaign Source
  var $campaign_name;  		// Campaign Name
  var $campaign_medium;    	// Campaign Medium
  var $campaign_content;   	// Campaign Content
  var $campaign_term;      	// Campaign Term

  var $first_visit;      	// Date of first visit
  var $previous_visit;		// Date of previous visit
  var $current_visit_started;	// Current visit started at
  var $times_visited;		// Times visited
  
  
  function __construct($cookie) {
       $this->utmz = $cookie["__utmz"];
       $this->utma = $cookie["__utma"];
       $this->ParseCookies();
  }

  function ParseCookies(){
  // Parse __utmz cookie
  list($domain_hash,$timestamp, $session_number, $campaign_numer, $campaign_data) = split('[\.]', $this->utmz);

  // Parse the campaign data
  $campaign_data = parse_str(strtr($campaign_data, "|", "&"));

  $this->campaign_source = $utmcsr;
  $this->campaign_name = $utmccn;
  $this->campaign_medium = $utmcmd;
  $this->campaign_term = $utmctr;
  $this->campaign_content = $utmcct;

  // You should tag you campaigns manually to have a full view
  // of your adwords campaigns data. 
  // The same happens with Urchin, tag manually to have your campaign data parsed properly.
  
  if($utmgclid) {
    $this->campaign_source = "google";
    $this->campaign_name = "";
    $this->campaign_medium = "cpc";
    $this->campaign_content = "";
    $this->campaign_term = $utmctr;
  }

  // Parse the __utma Cookie
  list($domain_hash,$random_id,$time_initial_visit,$time_beginning_previous_visit,$time_beginning_current_visit,$session_counter) = split('[\.]', $this->utma);

  $this->first_visit = date("d M Y - H:i",$time_initial_visit);
  $this->previous_visit = date("d M Y - H:i",$time_beginning_previous_visit);
  $this->current_visit_started = date("d M Y - H:i",$time_beginning_current_visit);
  $this->times_visited = $session_counter;

 // End ParseCookies
 }  

// End GA_Parse
}

?>
