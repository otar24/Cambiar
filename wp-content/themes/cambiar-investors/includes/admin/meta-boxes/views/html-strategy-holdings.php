<?php
$columns = U_Meta_Box_Strategy_Data::get_holdings_columns();
$fields = $the_strategy->{$tab_id};
$table_id = $tab_id;
include 'html-table-repeater.php';