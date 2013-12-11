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

$RecipeInfo['MeetupAPIEventList']['Version'] = '2013-06-28';

include_once ("cookbook/meetupinc/meetuplib.php");

Markup('meetupinc', 'inline', "/\\(:meetupinc (.*?):\\)/e","meetup_pmwiki_output('$1')");
Markup('meetupparams', 'inline', "/\\(:meetupparams (.*?):\\)/e","get_meetup_params('$1')");

function meetup_pmwiki_output($param)
	{
	$meetup_announce = meetup_output($param, $GLOBALS['MeetupMin'], $GLOBALS['MeetupMax']);
	return $meetup_announce;
	}

function get_meetup_params ($param)
	{
	unset ($param_args);
	$param_args = ParseArgs ($param);
	if ($param_args['min'] > 0) $GLOBALS ['MeetupMin'] = $param_args['min'];
		else $GLOBALS ['MeetupMin'] = 0;
	if ($param_args['max'] > 0) $GLOBALS ['MeetupMax'] = $param_args['max'];
		else $GLOBALS ['MeetupMax'] = 0;
	if ($param_args['venueid'] > 0) $GLOBALS ['MeetupVenueID'] = $param_args['venueid'];
                elseif ($param_args['venueid'] == -1) $GLOBALS ['MeetupVenueID'] = 0;
	if ($param_args['venueflag'] == "false") $GLOBALS ['MeetupVenueFlag'] = false;
	if ($param_args['venueflag'] == "true") $GLOBALS ['MeetupVenueFlag'] = true;
	if ($param_args['groupid'] > 0) $GLOBALS ['MeetupGroupID'] = $param_args['groupid'];
                elseif ($param_args['groupid'] == -1) $GLOBALS ['MeetupGroupID'] = 0;
	if ($param_args['groupflag'] == "false") $GLOBALS ['MeetupGroupFlag'] = false;
	if ($param_args['groupflag'] == "true") $GLOBALS ['MeetupGroupFlag'] = true;
	$GLOBALS ['MeetupAlt'] = $param_args['alt'];
	return "";
	}



