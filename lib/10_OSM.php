<?php

function OSM_by_way($id){
	return SQL_query_as_array("Select lat, lon from nodes,
		(select nodeid as id, sequence from ways_nodes WHERE ways_nodes.wayid = $id) As ref
		Where nodes.id = ref.id order by sequence;");
}

function OSM_by_node($id){
	return SQL_query_as_array("Select lat, lon from nodes
		Where id = $id");
}

function OSM_find_node($name){
	return SQL_query_as_array("select -id as way, 'way' as role, v as name from node_tags
where k = 'name' and v LIKE '$name%'");
}

function OSM_find_way($name){
	return SQL_query_as_array("SELECT id as way, 'way' as role, `v` as name FROM `way_tags` WHERE 
`way_tags`.`k` = 'name' AND `way_tags`.`v` LIKE '$name%'");
}

function OSM_find_relation($name){
	return SQL_query_as_array("Select wayid as way, role, name from member_way,
(Select id, v as name from relation_tags Where
k = 'name' AND v LIKE '$name%') as rel
Where rel.id = member_way.relid");
}


function OSM_find_all($name){
	$way = OSM_find_way($name);
	$rel = OSM_find_relation($name);
	$nod = OSM_find_node($name);
	
	if ($way == null) $way = array();
	if ($rel == null) $rel = array();
	if ($nod == null) $nod = array();
	
	return array_merge($way,$rel,$nod);
}