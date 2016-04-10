<?php
class Sync_model extends Doctrine_Record {
	public function get_latest_timestamp(){
		$query = $this->db->query("SELECT * FROM db_sync WHERE last_updated=(SELECT MAX(last_updated) FROM db_sync)");
		$result = $query->result_array();
		return $result[0]['last_updated'];
	}

	public function get_sync_data(){
		$query = $this->db->query("SELECT * FROM db_sync ORDER BY last_updated DESC");
		$result = $query->result_array();
		return $result;
	}

	public static function get_new_data($table_name,$last_sync_time = NULL){
		 $ci =& get_instance();

		$time_constraint = (isset($last_sync_time) && $last_sync_time!='')? "WHERE added_on BETWEEN '2016-04-09 19:23:23' and NOW()" :NULL;
		// echo "SELECT * FROM $table_name $time_constraint";exit;
		// $query = $ci->db->query("SELECT * FROM $table_name $time_constraint");
		// echo "I run";exit;
		$query = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAll("SELECT * FROM $table_name $time_constraint");

		// $result = $query->result_array();
		// echo "<pre>";print_r($query);exit;
		return $query;
	}

	public static function update_last_sync($facility_code){
		$query = $this->db->query("UPDATE sync_data SET last_sync_date = NOW() WHERE facility_code = '$facility_code'");
		$run_result = $query->result_array();
		if($run_result) return true;
		else return false;
	}

}
?>