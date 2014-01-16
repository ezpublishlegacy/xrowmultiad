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
                if ( $tpl->hasVariable('module_result') )
                {
                    $moduleResult = $tpl->variable('module_result');
                    foreach ( $moduleResult["path"] as $path_element )
                    {
                        if ( isset($path_element["node_id"]) && in_array( $path_element["node_id"], $tree_excludes ) )
                        {
                            return false;
                        }
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

    public static function getKeyword()
    {
        //checks the path array reversive for a matching keyword inside the ini
        $tpl = eZTemplate::instance();
        $xrowmultiadINI = eZINI::instance("xrowmultiad.ini");
        if ( $tpl->hasVariable('module_result') )
        {
            $keywords = $xrowmultiadINI->variable( 'KeywordSettings', 'KeywordMatching' );
            $moduleResult = $tpl->variable('module_result');
            //write "test" zone for test module
            if ( $moduleResult["uri"] == "/oms/test" )
            {
                return "test";
            }
            foreach ( array_reverse($moduleResult["path"]) as $path_element )
            {
                if ( isset($path_element["node_id"]) && array_key_exists($path_element["node_id"], $keywords) )
                {
                    //stop the foreach and return the matching keyword
                    return $keywords[$path_element["node_id"]];
                }
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
        return $default_keyword;
    }
}