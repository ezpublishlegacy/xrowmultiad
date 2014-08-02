<?php

class xrowMultiAdOperator
{

    function xrowMultiAdOperator()
    {
    }
    
    function operatorList()
    {
        return array(
            'omsad', 'openxad', 'join_ad_basics', 'ivw_addon'
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
                                'matchID' => array( 'type' => 'string' ,  'required' => false ),
                                'node' => array( 'type' => 'string' ,  'required' => false )
                             ),
            'ivw_addon' => array()
        );
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $show_ads = xrowMultiAd::checkDisplayStatus();
        if( $show_ads )
        {
            if ( isset ( $namedParameters['node'] ) )
            {
                $node = $namedParameters['node'];
            }
            else
            {
                $node = false;
            }

            $keyword_info = xrowMultiAd::getKeyword( $node );
            $keyword = $keyword_info["keyword"];
            $path = $keyword_info["path"];
            $keyword_ivw = $keyword_info["ivw_keyword"];

            if ( empty($path) )
            {
                $appendix = ";NodeID=" . $GLOBALS['eZRequestedModuleParams']["module_name"] . "_" . $GLOBALS['eZRequestedModuleParams']["function_name"] . ";";
            }
            else
            {
                $appendix = ";NodeID=" . end($path) . ";";
                foreach( $path as $i => $path_element )
                {
                    $appendix .= "TreeL" . ++$i . "=" . $path_element . ";";
                    if( $i === 5 )
                    {
                        break;
                    }
                }
            }

            $xrowmultiadINI = eZINI::instance("xrowmultiad.ini");
            $oms_site = $xrowmultiadINI->variable( 'OmsSettings', 'OmsSite' );
            
            switch ( $operatorName )
            {
                case 'join_ad_basics':
                {
                    $operatorValue = '<script language="JavaScript" type="text/javascript">
                                        var oms_site = "' . $oms_site . '"; 
                                        var oms_zone = "' . $keyword . '";
                                      </script>
                                      <script type="text/javascript" src="/extension/xrowmultiad/design/xrowmultiad/javascript/omsvjs14_1.js"></script>
                                      <script>
                                        try
                                           {
                                          var ystr="";
                                          var y_adj="";
                                        
                                        for (var id in yl.YpResult.getAll()) {
                                        
                                        c = yl.YpResult.get(id);
                                        ystr+= \';y_ad=\'+c.id;
                                         if(c.format){
                                         y_adj=\';y_adj=\'+c.format;
                                            }
                                        }
                                        ystr+=y_adj+\';\'; 
                                        WLRCMD=WLRCMD+ystr+segQS+crtg_content;
                                         }
                                        catch(err)
                                           {}
                                          </script>';
                }
                break;
                case 'omsad':
                {
                    //the banner size
                    $size = $namedParameters['size'];
                    $size_parts = explode("x", $size);
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
                                        if(typeof(WLRCMD)=="undefined"){var WLRCMD="";}
                                        if(typeof(oms_random)=="undefined"){var oms_random=Math.floor(Math.random()*10000000000)}
                                        document.write(\'<scr\'+\'ipt language="JavaScript" src="http://ad.doubleclick.net/' . $strange_code .  '/adj/' . $oms_site . '/' . $keyword . ';oms=' . $keyword . ';nielsen=' . $nielsen_area . $appendix . $dcopt . '\'+WLRCMD+\';sz=' . $size . ';tile=' . $tile . ';ord=\'+oms_random+\'?"><\/scr\'+\'ipt>\');
                                      </script>
                                      <noscript>
                                        <a href="http://ad.doubleclick.net/' . $strange_code .  '/jump/' . $oms_site . '/' . $keyword . ';oms=' . $keyword . ';nielsen=' . $nielsen_area . $appendix . 'sz=' . $size . ';tile=' . $tile . ';ord=' . $random_number . '?" target="_blank">
                                        <img src="http://ad.doubleclick.net/' . $strange_code .  '/ad/' . $oms_site . '/' . $keyword . ';oms=' . $keyword . ';nielsen=' . $nielsen_area . $appendix . 'sz=' . $size . ';tile=' . $tile . ';ord=' . $random_number . '?" border="0" width="' . $size_parts[0] . '" height="' . $size_parts[1] . '">
                                        </a>
                                      </noscript>';
                }
                break;
                case 'openxad':
                {
                    //the banner size
                    $size = $namedParameters['size'];
                    $size_parts = explode("x", $size);
                    $type = $namedParameters['type'];
                    $random_number = rand();
                    $adservURL = $xrowmultiadINI->variable( "OpenXSettings", "AdserverURL" );
                    $zone_id = $xrowmultiadINI->variable( $type . '_' . $keyword, 'ZoneID' );
                    $iframe_url = $adservURL . "/delivery/afr.php?zoneid=" . $zone_id . "&amp;cb=". $random_number;

                    //check if there is a banner
                    if ( function_exists( 'curl_init' ) )
                    {
                        $url = $iframe_url;
                        $curl_is_set = true;
                        $ch = curl_init();
                        curl_setopt( $ch, CURLOPT_URL, $url );
                        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt( $ch, CURLOPT_HEADER, 0 );
                        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                        curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
                    
                        $remote_content = curl_exec( $ch );
                        $info = curl_getinfo( $ch );
                        if ( $info['http_code'] != 200 )
                        {
                            $remote_content = false;
                            eZDebug::writeError( "URL ($url) is not available ", __METHOD__ );
                        }
                        curl_close( $ch );
                        eZDebug::writeDebug( "URL ($url) included", __METHOD__ );
                    }
                    else
                    {
                        $remote_content = file_get_contents( $url );
                        if( strlen( trim( $remote_content ) ) == 0 )
                        {
                            $remote_content = false;
                            eZDebug::writeError( "URL ($url) doesn't returned content", __METHOD__ );
                        }
                        eZDebug::writeDebug( "URL ($url) included", __METHOD__ );
                    }

                    $xml = simplexml_load_string($remote_content);
                    $body = $xml->body;

                    //modify width and height depending on the existance
                    if ( isset( $body[0] ) and $body[0]->count() >= 1)
                    {
                        $width = $size_parts[0];
                        $height = $size_parts[1];

                        //trying to fix the width and height if the size is not on maximum
                        if ( (int)$xml->body->a->img[0]["width"] AND (int)$xml->body->a->img[0]["width"] != 0)
                        {
                            $width = (int)$xml->body->a->img[0]["width"];
                        }
                        
                        if ( (int)$xml->body->a->img[0]["height"] AND (int)$xml->body->a->img[0]["height"] != 0)
                        {
                            $height = (int)$xml->body->a->img[0]["height"];
                        }
                    }
                    else
                    {
                        $width = 0;
                        $height = 0;
                    }

                    $html_snippet = "<iframe id='ad_" . $zone_id . '_' . $random_number . "' name='ad_" . $zone_id . "' src='" . $iframe_url . "' frameborder='0' scrolling='no' width='" . $width . "' height='" . $height . "'></iframe>";

                    //add ad-sign wrapper
                    if ( $width != 0 )
                    {
                        $html_snippet = '<div class="ad_wrapper" style="width: ' . $width . 'px;"><span>' . ezpI18n::tr( 'extension/xrowmultiad', 'Advertisement' ) . '</span>' . $html_snippet . '</div>'; 
                    }

                    $operatorValue = $html_snippet;
                }
                break;
            }
        }
        if ($operatorName == "ivw_addon")
        {
            if ( !isset($keyword_ivw) )
            {
                if ( isset ( $namedParameters['node'] ) )
                {
                    $node = $namedParameters['node'];
                }
                else
                {
                    $node = false;
                }
                
                $keyword_info = xrowMultiAd::getKeyword( $node );
                $keyword_ivw = $keyword_info["ivw_keyword"];
            }

            //hotix
            $keyword_info["ivw_sv"] = "ke";

            $operatorValue = '<!-- SZM VERSION="2.0" --> 
                                <script type="text/javascript"> 
                                var iam_data = { 
                                "mg":"yes", // Migrationsmodus AKTIVIERT 
                                "st":"hannovin", // site
                                "cp":"' . $keyword_ivw . '", // code SZMnG-System 2.0
                                "oc":"' . $keyword_ivw . '", // code SZM-System 1.5 
                                "sv":"' . $keyword_info["ivw_sv"] . '" 
                                } 
                                iom.c(iam_data); 
                                </script> 
                                <!--/SZM -->';
        }
    }
}

?>