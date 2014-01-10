<?php

$Module = $Params['Module'];
#$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();
#$db = eZDB::instance();

#$tpl->setVariable( 'var_name', eZUserAddition::recallUserID() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:test.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => 'OMS Test-Modul' ) );
?>