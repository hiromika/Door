<?php 

require_once 'koneksi.php';

$link = $_GET['action'];

switch ($link) {
	case 'get_user':
		$data = $proses->Get_user_all();
		echo json_encode(array('data' => $data));
		break;	
	case 'get_user_byid':
		$result = $proses->Get_user_byid();
		foreach($result as $row){
			$output["user_name"] = $row["user_name"];
			$output["user_email"] = $row["user_email"];
			$output["user_address"] = $row["user_address"];
			$output["user_phone"] = $row["user_phone"];
			$output["rule"] = $row["rule"];
			if($row["user_image"] != '')
			{
				$output['user_image'] = '<img src="'.$row["user_image"].'" class="img-thumbnail" width="50" height="35" /><input type="hidden" name="hidden_user_image" value="'.$row["user_image"].'" />';
			}
			else
			{
				$output['user_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
			}
		}
		echo json_encode($output);
		break;
	case 'in_user':
		if ($_POST['operation']=='Add') {
			$in = $proses->in_user();
			if ($in) {
			echo 'Data Inserted';
			}
		}else{
			$in = $proses->edit_user();
			if ($in) {
			echo 'Data Updated';
			}
		}
		break;
	case 'user_delete':
		$del = $proses->delete_user();
		if ($del) {
			echo "Data Deleted";
		}
		break;
	case 'get_code':
		$code = $proses->Get_code();
		echo json_encode(array('data' => $code));
		break; 
	case 'change_image':
		$ch = $proses->ch_image();
			if ($ch) {
				$proses->redirect('home.php?link=profile');
			}
		break;
	case 'save_profile':
		$ch = $proses->edit_profile();
			if ($ch) {
				$proses->redirect('home.php?link=profile');
			}
		break;
	default:
		# code...
		break;
}

	
?>