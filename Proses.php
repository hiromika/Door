<?php 
/**
* prosess
*/
class Proses{
	private $db;
	
	function __construct($conn){
		$this->db = $conn;
    date_default_timezone_set("Asia/Bangkok");

	}

	public function login($uname,$upass){
    $pass = md5($upass);
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM tb_login WHERE username=:uname AND password=:pass LIMIT 1");
          $stmt->execute(array(':uname'=>$uname, ':pass'=>$pass));
          $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0){
             
            $_SESSION['user'] = array( 
            	'id' 		    => $userRow['id'],
            	'username'	=> $userRow['username'],
            	'level'		  => $userRow['level'],
            	);
            return true;
             
          }else{
            return false;
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
 
   public function is_loggedin(){
      if(isset($_SESSION['user'])){
         return true;
      }
   }
 
   public function redirect($url){
       header("Location: $url");
   }
 
   public function logout(){
        session_destroy();
        unset($_SESSION['user']);
        return true;
   }


   /* USERS */
   public function Get_user_all(){
      try {
        
        $stmt = $this->db->prepare("SELECT *,b.id as idr FROM tb_user a LEFT JOIN tb_rule b ON b.id = a.rule  WHERE a.status = 1");
        $stmt->execute();
        $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $userData;

      } catch (PDOException $e) {
        echo $e->getMessage();
      }

   }
  public function Get_user_byid(){
      try {
        
        $stmt = $this->db->prepare("SELECT * FROM tb_user WHERE user_id=:user_id");
        $stmt->execute( array(':user_id' => $_POST['user_id'], ));
        $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $userData;

      } catch (PDOException $e) {
        echo $e->getMessage();
      }

   } 
   public function user_byid($id){
      try {
        
        $stmt = $this->db->prepare("SELECT * FROM tb_user WHERE user_id=:user_id");
        $stmt->execute( array(':user_id' => $id, ));
        $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $userData;

      } catch (PDOException $e) {
        echo $e->getMessage();
      }

   }

   public function Get_role(){
      try {
        $stmt = $this->db->prepare("SELECT * FROM tb_rule");
        $stmt->execute();
        $role = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $role;
        
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
   }

  function upload_image(){
      if(isset($_FILES["user_image"])){
        $extension = explode('.', $_FILES['user_image']['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = './assets/img/' . $new_name;
        move_uploaded_file($_FILES['user_image']['tmp_name'], $destination);
        return $destination;
      }
    }

  public function ch_image(){
    try {
      $image = '';
      if($_FILES["user_image"]["name"] != ''){
        $image = self::upload_image();
        $stmt = $this->db->prepare("UPDATE tb_user SET user_image = :image WHERE user_id = :id");
        $result = $stmt->execute(array(
          ':image'  => $image,
          ':id'     => $_POST['id'],

          ));
        if ($result) {
          return true;
        }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }

  }

  public function edit_profile(){
    try {
    

      if (isset($_POST['user_password'])) {
          $statement = $this->db->prepare("UPDATE tb_login SET password=:password WHERE id=:user_id");
          $result = $statement->execute(array(
              ':user_id'    => $_POST['user_id'],
              ':password'   => md5($_POST["user_password"]),
            ));
      }

      if ($result) {
          $statement2 = $this->db->prepare("
            UPDATE tb_user SET user_name=:user_name, user_email=:user_email, user_address=:user_address, user_phone=:user_phone WHERE user_id=:user_id
          ");
          $result2 = $statement2->execute(
            array(
              ':user_id'          =>  $_POST['user_id'],
              ':user_name'        =>  $_POST["user_name"],
              ':user_email'       =>  $_POST["user_email"],
              ':user_address'     =>  $_POST["user_address"],
              ':user_phone'       =>  $_POST["user_phone"],
            )
          );
          if(!empty($result2)){
            return true;
          }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
 
  }

  public function in_user(){
    try {
      $image = '';
      if($_FILES["user_image"]["name"] != ''){
        $image = self::upload_image();
      }

      $statement = $this->db->prepare("INSERT INTO tb_login (username, password, level) VALUES(:user_name, :password, :rule)");
      $result = $statement->execute(array(
          ':user_name'  => $_POST["user_name"],
          ':password'   => md5($_POST["user_password"]),
          ':rule'       => $_POST["role"],
        ));
      if ($result) {
          $id = $this->db->lastInsertId();
          $statement2 = $this->db->prepare("
            INSERT INTO tb_user (user_id, user_name, user_email, user_address, user_phone, user_image, rule) 
            VALUES (:user_id, :user_name, :user_email, :user_address, :user_phone, :user_image, :rule)
          ");
          $result2 = $statement2->execute(
            array(
              ':user_id'          =>  $id,
              ':user_name'        =>  $_POST["user_name"],
              ':user_email'       =>  $_POST["user_email"],
              ':user_address'     =>  $_POST["user_address"],
              ':user_phone'       =>  $_POST["user_phone"],
              ':rule'             =>  $_POST["role"],
              ':user_image'       =>  $image,
            )
          );
          if(!empty($result2)){
            return true;
          }
      }
     
    } catch (PDOException $e) {
      echo $e->getMessage();
      echo $id;
    }
 
   }

  public function edit_user(){
    try {
      $image = '';
      if($_FILES["user_image"]["name"] != ''){
        $image = self::upload_image();
      }else{
        $image = $_POST["hidden_user_image"];
      }

      if (isset($_POST['user_password'])) {
          $statement = $this->db->prepare("UPDATE tb_login SET password=:password, level=:rule WHERE id=:user_id");
          $result = $statement->execute(array(
              ':user_id'    => $_POST['user_id'],
              ':password'   => md5($_POST["user_password"]),
              ':rule'       => $_POST["role"],
            ));
      }else{
          $statement = $this->db->prepare("UPDATE tb_login SET level=:rule WHERE id=:user_id");

          $result = $statement->execute(array(
              ':user_id'    =>  $_POST['user_id'],
              ':rule'       => $_POST["role"],
            ));
      }
      if ($result) {
          $statement2 = $this->db->prepare("
            UPDATE tb_user SET user_name=:user_name, user_email=:user_email, user_address=:user_address, user_phone=:user_phone, user_image=:user_image, rule = :rule WHERE user_id=:user_id
          ");
          $result2 = $statement2->execute(
            array(
              ':user_id'          =>  $_POST['user_id'],
              ':user_name'        =>  $_POST["user_name"],
              ':user_email'       =>  $_POST["user_email"],
              ':user_address'     =>  $_POST["user_address"],
              ':user_phone'       =>  $_POST["user_phone"],
              ':rule'             =>  $_POST["role"],
              ':user_image'       =>  $image,
            )
          );
          if(!empty($result2)){
            return true;
          }
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
 
  }

  public function delete_user(){
    try {
      $stmt = $this->db->prepare("UPDATE tb_login SET status = 0 WHERE id=:user_id");
      $stmt->execute(array(':user_id' => $_POST['user_id'])); 

      $stmt2 = $this->db->prepare("UPDATE tb_user SET status = 0 WHERE user_id=:user_id");
      $stmt2->execute(array(':user_id' => $_POST['user_id']));
      return true;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function Get_code(){
    try {
  
    $code = mt_rand(100000,999999);
    $time = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s")." +5 minutes"));
    $idu = $_SESSION['user'];
    $statement = $this->db->prepare("INSERT INTO tb_code (id_user,code,expire) VALUES(:idu,:code,:exp)");

    $result = $statement->execute(array(
        ':idu'        => $idu['id'],
        ':code'       => $code,
        ':exp'       => $time
      ));

      if ($result) {
          return $code;
      }
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function check_code($kode){

      try {
        $time = date("Y-m-d H:i:s");
        $stmt = $this->db->prepare("SELECT * FROM tb_code WHERE code = :kode AND expire > :exp");
        $stmt->execute( array(
              ':kode'   => $kode,
              ':exp'    => $time,
              ));
        if ($stmt->rowCount() > 0) {
          return true;
        }else{
          return false;
        }
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    }
  

   
}?>