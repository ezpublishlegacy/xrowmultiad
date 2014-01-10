<?php

class xrowMultiAdOperator
{

    function xrowMultiAdOperator()
    {
    }
    
    function operatorList()
    {
        return array(
            'omsad', 'openxad', 'join_ad_basics'
        );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array(
            'join_ad_basics' => array(),
            'omsad' => array( 'type' => array( 'type' => 'string' ,  'required' => true ),
                              'size' => array( 'type' => 'string' ,  'required' => true ),
                              'matchID' => array( 'type' => 'string' ,  'required' => false ),
                              'tile' => array( 'type' => 'integer' ,  'required' => false, "default" => 1 )
                             ),
            'openxad' => array( 'type' => array( 'type' => 'string' ,  'required' => false ), 
                                'size' => array( 'type' => 'string' ,  'required' => true),
                                'matchID' => array( 'type' => 'string' ,  'required' => false )
                             )
        );
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $show_ads = xrowMultiAd::checkDisplayStatus();
        if( $show_ads )
        {
            $keyword = xrowMultiAd::getKeyword();
            //the banner size
            $size = $namedParameters['size'];
            $size_parts = explode("x", $size);
            $xrowmultiadINI = eZINI::instance("xrowmultiad.ini");
            $oms_site = $xrowmultiadINI->variable( 'OmsSettings', 'OmsSite' );
            
            switch ( $operatorName )
            {
                case 'join_ad_basics':
                {
                    $operatorValue = '<script type="text/javascript">
                                        var oms_site = "' . $oms_site . '"; 
                                        var oms_zone = "' . $keyword . '";
                                      </script>
                                      <script type="text/javascript" src="/extension/xrowmultiad/design/xrowmultiad/javascript/omsv.js"></script>
                                      <script type="text/javascript">
                                        if (typeof(wl13015camp) != "undefined"){
                                        if(wl13015camp > 0){
                                        WLRCMD+=\'rect=1;\';
                                        }}
                                        if (typeof(wl13016camp) != "undefined"){
                                        if(wl13016camp > 0){
                                        WLRCMD+=\'lead=1;\';
                                        }}
                                        if (typeof(wl13027camp) != "undefined"){
                                        if(wl13027camp > 0){
                                        WLRCMD+=\'sky=1;\';
                                        }}
                                        if (typeof(wl13028camp) != "undefined"){
                                        if(wl13028camp > 0){
                                        WLRCMD+=\'wall=1;\';
                                        }}
                                        if (typeof(wl13029camp) != "undefined"){
                                        if(wl13029camp > 0){
                                        WLRCMD+=\'layer=1;\';
                                        }}
                                        if (typeof(wl13030camp) != "undefined"){
                                        if(wl13030camp > 0){
                                        WLRCMD+=\'band=1;\';
                                        }}
                                        if (typeof(wl13032camp) != "undefined"){
                                        if(wl13032camp > 0){
                                        WLRCMD+=\'half=1;\';
                                        }}
                                        if (typeof(wl13031camp) != "undefined"){
                                        if(wl13031camp > 0){
                                        WLRCMD+=\'tandem=1;\';
                                        }}
                                        if (typeof(wl13019camp) != "undefined"){
                                        if(wl13019camp > 0){
                                        WLRCMD+=\'p1=1;\';
                                        }}
                                        if (typeof(wl13020camp) != "undefined"){
                                        if(wl13020camp > 0){
                                        WLRCMD+=\'p2=1;\';
                                        }}
                                        if (typeof(wl13021camp) != "undefined"){
                                        if(wl13021camp > 0){
                                        WLRCMD+=\'p3=1;\';
                                        }}
                                        if (typeof(wl13023camp) != "undefined"){
                                        if(wl13023camp > 0){
                                        WLRCMD+=\'bill=1;\';
                                        }}
                                        if (typeof(wl13024camp) != "undefined"){
                                        if(wl13024camp > 0){
                                        WLRCMD+=\'eWp=1;\';
                                        }}
                                        if (typeof(wl13025camp) != "undefined"){
                                        if(wl13025camp > 0){
                                        WLRCMD+=\'eSk=1;\';
                                        }}
                                        if (typeof(wl13017camp) != "undefined"){
                                        if(wl13017camp > 0){
                                        WLRCMD+=\'eSb=1;\';
                                        }}
                                        if (typeof(wl13018camp) != "undefined"){
                                        if(wl13018camp > 0){
                                        WLRCMD+=\'t_hp=1;\';
                                        }}
                                        WLRCMD=WLRCMD+segQS;
                                    </script>';
                }
                break;
                case 'omsad':
                {
                    $dcopt = $xrowmultiadINI->variable( 'OmsSettings', 'dcopt' );
                    if ( $dcopt == "false")
                    {
                        $dcopt = "";
                    }
                    else
                    {
                        $dcopt = "dcopt=" . $dcopt . ";";
                    }
                    $random_number = rand();
                    $tile = $namedParameters['tile'];
                    //todo?
                    $strange_code = "N5766";
                    $nielsen_area = "1";
                    $operatorValue = '<script type="text/javascript">
                                        if(typeof(oms_random)=="undefined"){var oms_random=Math.floor(Math.random()*10000000000)}
                                        document.write(\'<scr\'+\'ipt language="JavaScript" src="http://ad.doubleclick.net/' . $strange_code .  '/adj/' . $oms_site . '/' . $keyword . ';oms=' . $keyword . ';nielsen=' . $nielsen_area . ';' . $dcopt . 'sz=' . $size . ';tile=' . $tile . ';ord=\'+oms_random+\'?"><\/scr\'+\'ipt>\');
                                      </script>
                                      <noscript>
                                        <a href="http://ad.doubleclick.net/' . $strange_code .  '/jump/' . $oms_site . '/' . $keyword . ';oms=' . $keyword . ';nielsen=' . $nielsen_area . ';sz=' . $size . ';tile=' . $tile . ';ord=' . $random_number . '?" target="_blank">
                                        <img src="http://ad.doubleclick.net/' . $strange_code .  '/ad/' . $oms_site . '/' . $keyword . ';oms=' . $keyword . ';nielsen=' . $nielsen_area . ';sz=' . $size . ';tile=' . $tile . ';ord=' . $random_number . '?" border="0" width="' . $size_parts[0] . '" height="' . $size_parts[1] . '">
                                        </a>
                                      </noscript>';
                }
                break;
                case 'openxad':
                {
                    $type = $namedParameters['type'];
                    $random_number = rand();
                    $adservURL = $xrowmultiadINI->variable( "OpenXSettings", "AdserverURL" );
                    $zone_id = $xrowmultiadINI->variable( $type . '_' . $keyword, 'ZoneID' );

                    $operatorValue = "<iframe id='ad_" . $zone_id . "' name='ad_" . $zone_id . "' src='" . $adservURL . "/delivery/afr.php?zoneid=" . $zone_id . "&amp;cb=". $random_number ."' frameborder='0' scrolling='no' width='" . $size_parts[0] . "' height='" . $size_parts[1] . "'>
                                        <a href='" . $adservURL . "/delivery/ck.php?n=ad_" . $zone_id . "&amp;cb=". $random_number ."' target='_blank'>
                                            <img src='" . $adservURL . "/delivery/avw.php?zoneid=" . $zone_id . "&amp;cb=". $random_number ."&amp;n=ad_" . $zone_id . "' border='0' alt='' />
                                        </a>
                                      </iframe>";
                }
                break;
            }
        }
    }
}

?>