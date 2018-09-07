<?php
$columns = U_Meta_Box_Strategy_Data::get_section_columns($tab_id);
$fields = $the_strategy->{$tab_id};
$table_id = $tab_id;
if ( 'attributes' == $tab_id ) {
	include 'html-strategy-attributes-table-repeater.php';
} else {
	include 'html-table-repeater.php';
}