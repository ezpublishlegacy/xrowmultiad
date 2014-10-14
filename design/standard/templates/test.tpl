{def $start_node = fetch("content", "node", hash( "node_id", 82 ) )
     $zones = $start_node.data_map.flowblock.content.zones
}
<h1>this is a test page</h1>
{node_view_gui view='full' content_node=$start_node}
{* an alternative is it, to rebuild the zone template *}