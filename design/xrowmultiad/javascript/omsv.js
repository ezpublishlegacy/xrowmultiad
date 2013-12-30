if(typeof oms_site=='undefined'){oms_site=''};
if(typeof btcode=='undefined'){btcode=''};
if(typeof oms_zone=='undefined'){oms_zone=''};
if(typeof WLRCMD=='undefined'){WLRCMD=''};
if(typeof WLRCMD_AMP=='undefined'){WLRCMD_AMP=''};
var wsite=oms_site;
var ccat=btcode;
var oms_random=Math.floor(Math.random()*10000000000);

document.write('<scr'+'ipt src="http://oms.nuggad.net/rc?nuggn=1615459509&nuggtg='+encodeURIComponent(oms_zone)+'" type="text/javascript"></scr'+'ipt>');

if (WLRCMD.length > 3)  {
    WLRCMD_AMP=WLRCMD.replace(/;/g, '&');
    if (WLRCMD_AMP.indexOf('&') != 0)
        WLRCMD_AMP = '&' + WLRCMD_AMP;
        WLRCMD_AMP = encodeURIComponent(WLRCMD_AMP);
}



var wlCus = "13015,13016,13029,13027,13030,13032,13028,13031,13019,13020,13021,13023,13024,13025,13017,13018";
var wlOrd = new Date().getTime();

try {
    document.write('<scr' + 'ipt type="text/javascript" language="JavaScript" src="http://req.connect.wunderloop.net/AP/1626/6628/13015/js?cus=' + wlCus + '&ord=' + wlOrd + '"></sc' + 'ript>');
} catch(err) { }






  var rsi_segs = [];
  var segs_beg=document.cookie.indexOf('rsi_segs=');
  if (segs_beg>=0){
    segs_beg=document.cookie.indexOf('=',segs_beg)+1;
    if(segs_beg>0){
      var segs_end=document.cookie.indexOf(';',segs_beg);
      if(segs_end==-1) segs_end=document.cookie.length;
      rsi_segs=document.cookie.substring(segs_beg,segs_end).split('|');
    }
  }
  var segLen=20;
  var segQS="";
  if (rsi_segs.length<segLen){segLen=rsi_segs.length}
  for (var i=0;i<segLen;i++){
    segQS+=("rsi"+"="+rsi_segs[i]+";")
  }
  

document.write('<scr'+'ipt src="http://js.revsci.net/gateway/gw.js?csid=F12349&auto=t&oms_zone='+oms_zone+'"></scr'+'ipt>');

(function() {
  var times = Math.floor((Math.random()*10)+1);
  if (times % 10 !== 0) {
    return;
  }

  var getScriptUrl = function() {
    var regCheck = /ad.de.doubleclick.net\/adj\/(.*?);/i,
        scriptUrl = document.location.href.split("://")[0]+'://s236.meetrics.net/bb-mx/prime/mtrcs_646343.js?pjid=646343&size=all',
        zone;

    var scriptTags = document.getElementsByTagName('script');
    for (var i=0; i < scriptTags.length; ++i) {
      if (scriptTags[i].src && (zone = regCheck.exec(scriptTags[i].src))) {
        scriptUrl += '&adc='+zone[1];
        return scriptUrl;
      }
    }
  };

  var tries = 30;

  var poll_for_script_tag = function() {
    var url = getScriptUrl();
    if (url) {
      var newScript = document.createElement('script');
      newScript.type = 'text/javascript';
      newScript.src = url;
      newScript.async = 'async';
document.getElementsByTagName('head')[0].appendChild(newScript);
    }
    else {
      if (tries--) {
        setTimeout(poll_for_script_tag, 500);
      }
    }
  }

  poll_for_script_tag();

})();