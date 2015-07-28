_uact="XXXXXX-X";
urchinTracker();
//
// Get the __utmz cookie value. This is the cookies that
// stores all campaign information.
//
var z = _uGC(document.cookie, '__utmz=', ';');
//
// The cookie has a number of name-value pairs.
// Each identifies an aspect of the campaign.
//
// utmcsr  = campaign source
// utmcmd  = campaign medium
// utmctr  = campaign term (keyword)
// utmcct  = campaign content (used for A/B testing)
// utmccn  = campaign name
// utmgclid = unique identifier used when AdWords auto tagging is enabled
//
// This is very basic code. It separates the campaign-tracking cookie
// and populates a variable with each piece of campaign info.
//
var source  = _uGC(z, 'utmcsr=', '|');
var medium  = _uGC(z, 'utmcmd=', '|');
var term    = _uGC(z, 'utmctr=', '|');
var content = _uGC(z, 'utmcct=', '|');
var campaign = _uGC(z, 'utmccn=', '|');
var gclid   = _uGC(z, 'utmgclid=', '|');
//
// The gclid is ONLY present when auto tagging has been enabled.
// All other variables, except the term variable, will be '(not set)'.
// Because the gclid is only present for Google AdWords we can
// populate some other variables that would normally
// be left blank.
//
if (gclid !="-") {
      source = 'google';
      medium = 'cpc';
}
// Data from the custom segmentation cookie can also be passed
// back to your server via a hidden form field
var csegment = _uGC(document.cookie, '__utmv=', ';');
if (csegment != '-') {
      var csegmentex = /[1-9]*?\.(.*)/;
      csegment    = csegment.match(csegmentex);
      csegment    = csegment[1];
} else {
      csegment = '';
}
