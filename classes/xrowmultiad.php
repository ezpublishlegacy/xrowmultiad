<?php

class xrowMultiAd
{

    public static function checkDisplayStatus()
    {
        $xrowmultiadINI = eZINI::instance("xrowmultiad.ini");
        if ( $xrowmultiadINI->hasVariable( 'GeneralSettings', 'Display' ) )
        {
            $display_in_siteaccess = $xrowmultiadINI->variable( 'GeneralSettings', 'Display' );
        }
        else
        {
            $display_in_siteaccess = $xrowmultiadINI->variable( 'GeneralSettings', 'DisplayDefault' );
        }

        //check if the siteaccess is allowed to use ads
        if ( $display_in_siteaccess != "disabled")
        {
            $Module = $GLOBALS['eZRequestedModule'];
            $namedParameters = $Module->NamedParameters;

            if ( isset($namedParameters["NodeID"]) && is_numeric($namedParameters["NodeID"]) )
            {
                //check if its a single page exclude
                $node_id = $namedParameters["NodeID"];
                $single_page_excludes = $xrowmultiadINI->variable( 'GeneralSettings', 'SinglePageExcludes' );
                if ( in_array( $node_id, $single_page_excludes ) )
                {
                    return false;
                }

                //check if the node is excluded by a tree exclude
                $tree_excludes = $xrowmultiadINI->variable( 'GeneralSettings', 'TreeExcludes' );
                $tpl = eZTemplate::instance();
                $path = array();
                
                if ( $tpl->hasVariable('module_result') )
                {
                    $moduleResult = $tpl->variable('module_result');
                    foreach ( $moduleResult["path"] as $element )
                    {
                        $path[] = $element["node_id"];
                    }
                    
                }
                else if ( isset( $tpl->Variables[""]["node"] ) )
                {
                    //fallback just in case
                    $path = $tpl->Variables[""]["node"]->pathArray();
                }

                foreach ( $path as $path_element )
                {
                    if ( isset($path_element) && in_array( $path_element, $tree_excludes ) )
                    {
                        return false;
                    }
                }
            }
            //return true if no condition kicked us out before
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function getKeyword( $node = false )
    {
        //checks the path array reversive for a matching keyword inside the ini
        $tpl = eZTemplate::instance();
        $xrowmultiadINI = eZINI::instance("xrowmultiad.ini");
        $path = array();
        $uri = "";
        //activate this to run testmode everywhere
        //return "test";

        if ( $tpl->hasVariable('module_result') )
        {
            $moduleResult = $tpl->variable('module_result');
            $uri = $moduleResult["uri"];

            foreach ( $moduleResult["path"] as $element )
            {
                if ( isset( $element["node_id"] ) )
                {
                    $path[] = $element["node_id"];
                }
            }
        }
        else if ( isset( $tpl->Variables[""]["node"] ) )
        {
            //fallback just in case
            $path = $tpl->Variables[""]["node"]->pathArray();
            $uri = $GLOBALS["request_uri"];
        }
        else if ($node != false && $node instanceof eZContentObjectTreeNode )
        {
            //fallback of the fallback
            $path = explode("/", $node->PathString);
            $uri = $node->urlAlias();
        }

        $keywords = $xrowmultiadINI->variable( 'KeywordSettings', 'KeywordMatching' );
        //write "test" zone for test module
        if ( $uri == "/oms/test" )
        {
            return array( "keyword" => "test", "path" => $path );
        }
        foreach ( array_reverse( $path ) as $path_element )
        {
            if ( isset($path_element) && array_key_exists($path_element, $keywords) )
            {
                //stop the foreach and return the matching keyword
                return array( "keyword" => $keywords[$path_element], "path" => $path );
            }
        }

        //no keyword found, use the default!
        if ( $xrowmultiadINI->hasVariable( 'KeywordSettings', 'SiteaccessKeywordDefault' ) )
        {
            $default_keyword = $xrowmultiadINI->variable( 'KeywordSettings', 'SiteaccessKeywordDefault' );
        }
        else
        {
            $default_keyword = $xrowmultiadINI->variable( 'KeywordSettings', 'KeywordDefault' );
        }
        return array( "keyword" => $default_keyword, "path" => $path );
    }
}