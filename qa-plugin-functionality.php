<?php
function get_qa_requests()
{
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}qa_checkboxes`");
} 

function get_history($request_id)
{
	 global $wpdb;
     return $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}qa_history` WHERE `request_id`=".$request_id."  ORDER BY `id` DESC LIMIT 5");
}

function update_qa_requests_comment()
{
    $data = json_decode(file_get_contents('php://input'), true);
    global $wpdb;
    $table_name = $wpdb->prefix . 'qa_checkboxes';
    $dbResult = $wpdb->update($table_name, array('comment' => $data['comment']), array('id' => $data['id']));

}

function update_checklist() {
	$data = json_decode(file_get_contents('php://input'), true);
	global $wpdb;
    $table_primary = $wpdb->prefix . 'qa_checkboxes';
	$table_history = $wpdb->prefix . 'qa_history';
	$dbResult = $wpdb->update($table_primary, array('checked' => $data['checked']), array('id' => $data['id']));
	$dbResult = $wpdb->insert($table_history, array('checked' => $data['checked'],'username'=>$data['name'],'request_id' => $data['id']));
	$history =  $wpdb->get_results("SELECT * FROM `{$wpdb->base_prefix}qa_history` WHERE `request_id`=". $data['id']."  ORDER BY `id` DESC LIMIT 5");
	$historyText = '';
	if($history != null ) {
		
		foreach($history as $i=>$hisRow){
			$historyText .= ($hisRow->checked == 1) ? ($i+1).'. '.$hisRow->username.' has checked </br>' : ($i+1).'. '.$hisRow->username.' has unchecked </br>';
		}	
	}
	return json_encode($historyText);
		
}

function insert_request() {
	$data = json_decode(file_get_contents('php://input'), true);
		
	global $wpdb;
	$table_name = $wpdb->prefix . 'qa_checkboxes';
	$data = $wpdb->insert(
			$table_name,
			$data
		);
	return json_encode($data);
}

function delete() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'qa_checkboxes';
	$table_history = $wpdb->prefix . 'qa_history';
	
	$wpdb->query("TRUNCATE TABLE ".$table_history);
	$wpdb->query("UPDATE ".$table_name." SET `comment` = NULL WHERE 1=1");
	$wpdb->query("UPDATE ".$table_name." SET `checked` = 0 WHERE 1=1");
}
