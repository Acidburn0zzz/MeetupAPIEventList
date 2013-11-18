INTRODUCTION
============
This is the README.txt documentation for the PmWiki recipe MeetupAPIEventList. This recipe allows you to embed an event list from the Meetup API into PmWiki pages and format it with PmWiki markup. 

The Meetup API provides a simple interface for accessing the Meetup platform from your own apps. With this recipe, you can include a list of upcoming Meetup events into your PmWiki pages (e.g. for a selected group), using the events method of Meetup API. 


INSTALLATION
============
 *   Download meetupinc.zip and unpack it in your PmWiki directory.
 *   In your local config, write the usual include code:

require_once "$FarmD/cookbook/meetupinc.php";

 *   Generate a signed URL for your Meetup API request (see below) and include it into your local config using the following code:

$MeetupURL = "Your_Signed_URL";
      
 *   Configure caching functionality (see below)	


HOW TO OBTAIN A SIGNED MEETUP URL
=================================
 *   Visit http://www.meetup.com/meetup_api/console/?path=/2/events and sign in with your account
 *   Enter your data (usually group_urlname should be sufficient, e.g. "nylug-meetings", but other criteria is possible too) and click "Show Response"
 *   Copy and paste signed URL (should be displayed right below the input form) into your local config as described above 

Meetup API Troubleshooting
--------------------------
 *   Meetup user account required
 *   Check whether all required data is displayed below the input form
 *   Make sure that format=json is part of the signed URL
 *   URL should look something like
	
http://api.meetup.com/2/events?group_id=2659922&status=upcoming&order=time&limited_events=False&desc=false&offset=0&format=json&page=200&fields=&sig_id=xxxxxxxx&sig=yyyyyyyyyyyyyy 


USAGE
=====
If this looks too complicated, please check out the examples below!

Meetup data is included with the markup

(:meetupinc ''Custom Variables and Formatting'':)

Plain text and some basic PmWiki markup (like ''' for bold, [[<<]] for linebreaks and [[http://example.com/]] for hyperlinks) can be used.

To display Meetup data, use the following custom variables:

 *   {$$m-name} for event name
 *   {$$m-desc} for event description (enabling UTF-8 might be necessary for this to work properly)
 *   {$$m-v-name} for name of event location
 *   {$$m-v-addr} for location address
 *   {$$m-v-city} for city
 *   {$$m-v-state} for state
 *   {$$m-v-country} for country
 *   {$$m-weekday} for weekday, according to your locale (e.g. "Thursday", "jeudi", "Donnerstag") 
 *   {$$m-day} for day
 *   {$$m-year} for year (four digits)
 *   {$$m-month} for month (e.g. "8" for "August")
 *   {$$m-month-text} for name of the month, according to your locale (e.g. "January", "janvier", "January") 
 *   {$$m-time-ampm} for time in 12 hours format (e.g. "2:30 pm")
 *   {$$m-time} for time in 24 hours format (e.g. "14:30")
 *   {$$m-link} for a link to the event's Meetup page
 *   {$$m-todaytom} to include "Today, " if the event starts today or "Tomorrow, " if it starts tomorrow 


LIMITING THE NUMBER OF RESULTS
==============================
By default, (:meetupinc:) lists all events that were returned by the Meetup API query. You can limit the number of results with the (:meetupparams:) directive which needs to be included before (:meetupinc:) markup:

(:meetupparams max=Maximum Number of Events Displayed:)

If you want to display only the next upcoming event, use:

(:meetupparams max=1:)

Reset the maximum to unlimited with:

(:meetupparams max=0:)

If you want to skip a certain number of events at the beginning of the list, use:

(:meetupparams min=Number of the First Event to be Displayed:)


CACHING
=======
In order to speed up the website and to avoid exceeding Meetup's API limit, this recipe writes Meetup data to cache (if possible). By default, cache is being updated every 10 minutes.

For caching to work properly, you need to create a folder named "meetupcache" in your main PmWiki directory which needs to be writable by www-data (or whatever user it is as which your web server runs).

You can change the location of the cache file by changing $CacheFile = "meetupcache/meetup-cache.json"; to something else in meetupinc/meetuplib.php.

Cache duration can be modified in your local config file. If you want it to be 5 minutes instead of 10, simply add the line $MeetupCacheLifetime = 300; to your config. This line needs to be included before require_once "$FarmD/cookbook/meetupinc.php" to work properly.


EXAMPLES
========
The following markup:

(:meetupparams max=1:)!!!Next Event:
(:meetupinc '''{$$m-name}''' [[<<]]{$$m-todaytom} {$$m-weekday} {$$m-month-text} {$$m-day}, {$$m-time-ampm}[[<<]]{$$m-v-name}, {$$m-v-addr}, {$$m-v-city}, {$$m-v-state}[[<<]][[{$$m-link}|Sign up on Meetup]] [[<<]]:)

yields something like the following output:

	Next Event:
	An overview of MySQL forks and alternative Storage Engines
	Thursday June 13, 6:30 pm
	Google Chelsea Market Office, 75 Ninth Ave, Floor 2, New York, NY
	Sign up on Meetup

List some more events with this markup:

(:meetupparams min=2 max=5:)!!!More Events:
(:meetupinc {$$m-month-text} {$$m-day}, {$$m-time-ampm}: [[{$$m-link}|{$$m-name}]][[<<]]:)

Output:

	More Events:
	June 18, 6:00 pm: NYLUG Workshop / Hacking Society
	July 2, 6:00 pm: NYLUG Workshop / Hacking Society
	July 11, 6:30 pm: LogStash: Yes, Logging Can Be Awesome
	July 16, 6:00 pm: NYLUG Workshop / Hacking Society

Use the following markup to list all remaining events (e.g. in a separate Wiki page):

(:meetupparams min=6 max=0:)!!!Even More Events:
(:meetupinc {$$m-month-text} {$$m-day}, {$$m-time-ampm}: [[{$$m-link}|{$$m-name}]][[<<]]:) 

