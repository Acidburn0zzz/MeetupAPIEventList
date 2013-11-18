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

$RecipeInfo['PagelistCount']['Version'] = '2013-06-15';

include_once ("cookbook/meetupinc/meetuplib.php");

Markup('meetupinc', 'inline', "/\\(:meetupinc (.*?):\\)/e","meetup_output('$1')");
Markup('meetupparams', 'inline', "/\\(:meetupparams (.*?):\\)/e","get_meetup_params('$1')");

function get_meetup_params ($param)
	{
	unlink ($param_args);
	$param_args = ParseArgs ($param);
	if ($param_args['min'] > 0) $GLOBALS ['MeetupMin'] = $param_args['min'];
		else $GLOBALS ['MeetupMin'] = 0;
	if ($param_args['max'] > 0) $GLOBALS ['MeetupMax'] = $param_args['max'];
		else $GLOBALS ['MeetupMax'] = 0;
	if ($param_args['venueid'] > 0) 
		{
		$GLOBALS ['MeetupVenueID'] = $param_args['venueid'];
		}
	if ($param_args['venueflag'] == "false") $GLOBALS ['MeetupVenueFlag'] = false;
	if ($param_args['venueflag'] == "true") $GLOBALS ['MeetupVenueFlag'] = true;
	return "";
	}

function meetup_output($param)
	{
	$meetup_announce = "";
	$GLOBALS['MeetupCount'] = 0;
	$MeetupMax = $GLOBALS['MeetupMax'];
	$MeetupMin = $GLOBALS['MeetupMin'];
	if ($GLOBALS['MeetupJsonData'] == "") $GLOBALS['MeetupJsonData'] = read_meetup_data();
	$meetup_array=json_decode($GLOBALS['MeetupJsonData'], true);

	if (count ($meetup_array)>0)
		{	
		foreach ($meetup_array["results"] as $json_event)
			{			
			$event_v_id = $json_event['venue']['id'];
			if ($GLOBALS['MeetupCount'] + 1 > $MeetupMax AND $MeetupMax >0) break;
			$Meetup_String = create_meetup_string ($json_event,$param);
			if ($GLOBALS['MeetupCount'] >= $MeetupMin) $meetup_announce = $meetup_announce.$Meetup_String;
			}
		}	
		return $meetup_announce;
	}


