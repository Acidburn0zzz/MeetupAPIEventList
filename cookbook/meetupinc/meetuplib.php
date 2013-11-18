<?php if (!defined('PmWiki')) exit();

/*
+----------------------------------------------------------------------+
| See cookbook/meetupinc/README.txt for information.
| See cookbook/meetupinc/LICENSE.txt for licence.
+----------------------------------------------------------------------+
| Copyright 2013 S.Schulte
| This program is free software; you can redistribute it and/or modify
| it under the terms of the GNU General Public License, Version 2, as
| published by the Free Software Foundation.
| http://www.gnu.org/copyleft/gpl.html
| This program is distributed in the hope that it will be useful,
| but WITHOUT ANY WARRANTY; without even the implied warranty of
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
| GNU General Public License for more details.
+----------------------------------------------------------------------+
*/

$MeetupCacheFile = "meetupcache/meetup-cache.json";
if (!(trim($MeetupCacheLifetime) > 0)) $MeetupCacheLifetime = 600; // in seconds
$MeetupJsonData = "";
$MeetupMax = 0;		
$MeetupMin = 0;		
$MeetupVenueID = 0;
$MeetupVenueFlag = true;



function testtodaytomorrow ($test_date)
        {
        $today = time();
        $tomorrow = $today+24*60*60;
        if (date ("Y",$test_date)==date ("Y",$today) AND date ("z",$test_date)==date ("z",$today)) return "Today, ";
        elseif (date ("Y",$test_date)==date ("Y",$tomorrow) AND date ("z",$test_date)==date ("z",$tomorrow)) return "Tomorrow, ";
        else return "";
        }



function create_meetup_string ($json_event,$param)
	{
	$return_string = "";
	$event_v_id = $json_event['venue']['id'];
 	if ($GLOBALS['MeetupVenueID'] == $event_v_id AND $GLOBALS['MeetupVenueFlag'] == false) return "";
	if ($GLOBALS['MeetupVenueID'] != $event_v_id AND $GLOBALS['MeetupVenueFlag'] == true AND $GLOBALS['MeetupVenueID'] != 0) return "";
	$event_id = $json_event["id"];
	$event_date=($json_event["time"])/1000;
	$event_link = $json_event['event_url'];	
	$json_event['description'] = str_replace ("\n","<br>",$json_event['description']);
	$param = str_replace ('\\',"\n",$param);	
	$param = str_replace ('{$$m-name}',$json_event['name'],$param);	
	$param = str_replace ('{$$m-desc}',$json_event['description'],$param);	
	$param = str_replace ('{$$m-v-name}',$json_event['venue']['name'],$param);	
	$param = str_replace ('{$$m-v-addr}',$json_event['venue']['address_1'],$param);	
	$param = str_replace ('{$$m-v-city}',$json_event['venue']['city'],$param);	
	$param = str_replace ('{$$m-v-state}',$json_event['venue']['state'],$param);	
	$param = str_replace ('{$$m-v-country}',$json_event['venue']['country'],$param);	
	$param = str_replace ('{$$m-v-id}',$json_event['venue']['id'],$param);	
	$param = str_replace ('{$$m-weekday}',strftime("%A",$event_date),$param);	
	$param = str_replace ('{$$m-day}',date("j",$event_date),$param);	
	$param = str_replace ('{$$m-year}',date("Y",$event_date),$param);	
	$param = str_replace ('{$$m-month}',date("n",$event_date),$param);	
	$param = str_replace ('{$$m-month-text}',strftime("%B",$event_date),$param);	
	$param = str_replace ('{$$m-time-ampm}',date("g:i a",$event_date),$param);	
	$param = str_replace ('{$$m-time}',date("G:i",$event_date),$param);	
	$param = str_replace ('{$$m-link}',$event_link,$param);	
	$param = str_replace ('{$$m-todaytom}',testtodaytomorrow($event_date),$param);	
	$GLOBALS['MeetupCount'] = $GLOBALS['MeetupCount'] + 1;
	$return_string = $param;
	return $return_string;
	}

function read_meetup_data ()
	{
	$cache_time = 0;
	$json_file_data = "";
	if (file_exists($GLOBALS["MeetupCacheFile"]))
		{
		$jsondata = file_get_contents($GLOBALS["MeetupCacheFile"]);
		$cache_time = filemtime ($GLOBALS["MeetupCacheFile"]);
		}

	if (time()-$cache_time > $GLOBALS["MeetupCacheLifetime"]) 
		{
		$opts = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Accept-Charset: utf-8\r\n" 
		  )
		);

		$context = stream_context_create($opts);

		$json_file_data=@file_get_contents($GLOBALS['MeetupURL'], false, $context);
		}

	if ($json_file_data!="") 
		{
		if (substr ($json_file_data,0,11)=="{\"results\":")
			{
			$handle = @fopen ($GLOBALS["MeetupCacheFile"],"w");
			@fwrite ($handle, $json_file_data);
			@fclose ($handle);
			$jsondata = $json_file_data;
			}
		}
	return $jsondata;
	}

