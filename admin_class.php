<?php
session_start();
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login() {
        extract($_POST);
        $response = ['status' => 0, 'message' => 'Login failed', 'picture_path' => ''];
        
        $qry = $this->db->query("SELECT * FROM users WHERE username = '" . $this->db->real_escape_string($username) . "' ");
    
        if ($qry->num_rows > 0) {
            $user = $qry->fetch_array();
            $this->db->query("UPDATE users SET online_status = 1 WHERE username = '" . $this->db->real_escape_string($username) . "'");
    
            $user_details = $this->db->query("SELECT id, name, picture_path, type FROM users WHERE username = '" . $this->db->real_escape_string($username) . "' ");
            $user_info = $user_details->fetch_assoc();
    
            foreach ($user_info as $key => $value) {
                $_SESSION['login_' . $key] = $value;
            }
    
            $user_type = ($_SESSION['login_type'] == 1) ? "Admin" : "Students";
            $user_id = $_SESSION['login_id'];
            date_default_timezone_set('Asia/Manila');
            $login_time = date('Y-m-d H:i:s A');
            $sql = "INSERT INTO login_logs (user_type, user_id, login_time) VALUES ('$user_type', '$user_id', '$login_time')";
            $this->db->query($sql);
    
            // Set response status and picture path
            $response['status'] = ($_SESSION['login_type'] == 1) ? 1 : 2;
            $response['message'] = 'Login successful';
            $response['picture_path'] = $user_info['picture_path']; // Assuming picture_path column exists
        } else {
            $response['status'] = 3;
            $response['message'] = 'User not found';
        }
        
        echo json_encode($response);
        exit;
    }    

	function loginadmin()
    {
        extract($_POST);
        $hashed_password = md5($password);

        $username = $this->db->real_escape_string($username);
        $hashed_password = $this->db->real_escape_string($hashed_password);

        $checkAdmin = "SELECT * FROM users WHERE type = 1";
        $adminResult = $this->db->query($checkAdmin);
        if ($adminResult->num_rows == 0) {
            $insertAdmin = "INSERT INTO users (username, password, type) VALUES ('$username', '$hashed_password', 1)";
            $this->db->query($insertAdmin);
        }

        $qry = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
        $result = $this->db->query($qry);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            $online_status = 1;
            $qry_update_status = "UPDATE users SET online_status = '$online_status' WHERE username = '$username'";
            $this->db->query($qry_update_status);

            foreach ($user as $key => $value) {
                $_SESSION['login_' . $key] = $value;
            }
            $user_type = ($_SESSION['login_type'] == 1) ? "Admin" : "Students";
            $user_id = $_SESSION['login_id'];

            date_default_timezone_set('Asia/Manila');
            $login_time = date('Y-m-d H:i:s A');

            $sql = "INSERT INTO login_logs (user_type, user_id, login_time) VALUES ('$user_type', '$user_id', '$login_time')";
            $this->db->query($sql);

            if ((int)$_SESSION['login_type'] == 1) {
                return 1; // Admin user
            } else {
                return 2; // Regular user
            }
        } else {
            return 3; // User not found
        }
    }


	function logout()
	{
		// Set the timezone to Asia/Manila
		date_default_timezone_set('Asia/Manila');

		// Get the current user's user ID and user type from the session
		$user_id = isset($_SESSION['login_id']) ? (int)$_SESSION['login_id'] : 0;
		$userType = isset($_SESSION['login_type']) ? (int)$_SESSION['login_type'] : 0;

		// Connect to the database
		include('db_connect.php');

		// Get the current timestamp with the Manila timezone
		$logout_time = date('Y-m-d H:i:s');

		// Update the logout time in the login_logs table for the current user
		$conn->query("UPDATE login_logs SET logout_time = '$logout_time' WHERE user_id = $user_id AND logout_time IS NULL");

		// Clear the session
		$_SESSION = array();

		// Update online_status to 'offline' in the database
		$conn->query("UPDATE users SET online_status = 'offline' WHERE id = $user_id");

		// Redirect based on user type
		if ($userType == 1) {
			// Admin logout, redirect to admin page
			header("location: admin.php");
			exit(); // Stop further execution
		} elseif ($userType == 2) {
			// Regular user logout, redirect to trylogin.php
			header("location: trylogin.php");
			exit(); // Stop further execution
		}
	}

	function save_files()
	{
		extract($_POST);
		if (empty($id)) {
			if ($_FILES['upload']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['upload']['name'];
				$move = move_uploaded_file($_FILES['upload']['tmp_name'], 'assets/uploads/' . $fname);

				if ($move) {
					$file = $_FILES['upload']['name'];
					$file = explode('.', $file);
					$chk = $this->db->query("SELECT * FROM files where SUBSTRING_INDEX(name,' ||',1) = '" . $file[0] . "' and folder_id = '" . $folder_id . "' and file_type='" . $file[1] . "' ");
					if ($chk->num_rows > 0) {
						$file[0] = $file[0] . ' ||' . ($chk->num_rows);
					}
					$data = " name = '" . $file[0] . "' ";
					$data .= ", folder_id = '" . $folder_id . "' ";
					$data .= ", description = '" . $description . "' ";
					$data .= ", user_id = '" . $_SESSION['login_id'] . "' ";
					$data .= ", file_type = '" . $file[1] . "' ";
					$data .= ", file_path = '" . $fname . "' ";
					if (isset($is_public) && $is_public == 'on')
						$data .= ", is_public = 1 ";
					else
						$data .= ", is_public = 0 ";

					$save = $this->db->query("INSERT INTO files set " . $data);
					if ($save)
						return json_encode(array('status' => 1));
				}
			}
		} else {
			$data = " description = '" . $description . "' ";
			if (isset($is_public) && $is_public == 'on')
				$data .= ", is_public = 1 ";
			else
				$data .= ", is_public = 0 ";
			$save = $this->db->query("UPDATE files set " . $data . " where id=" . $id);
			if ($save)
				return json_encode(array('status' => 1));
		}
	}
	function save_user()
    {
        extract($_POST);

        // Handle file upload
        $picture_path = '';
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'image/'; // Assuming the 'image' folder is in the current directory

            // Generate a unique filename for the uploaded image
            $file_extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION); // Extract file extension
            $unique_filename = uniqid('img_', true) . '.' . $file_extension; // Create unique filename

            $uploaded_file = $upload_dir . $unique_filename; // Use the unique filename
            move_uploaded_file($_FILES['picture']['tmp_name'], $uploaded_file);
            $picture_path = $uploaded_file;
        }

        $data = " name = '$name' ";
        $data .= ", username = '$username' ";
        $data .= ", picture_path = '$picture_path' "; // Save the file path to the database
        $data .= ", department = '$department' ";
        $data .= ", course = '$course' ";
        $data .= ", type = '$type' ";

        // Conditionally include the password only if the user type is "admin" and password is not empty
        if ($type == 1 && !empty($password)) {
            $hashed_password = md5($password);
            $data .= ", password = '$hashed_password' "; // Store the hashed password
        }

        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users SET " . $data);
        } else {
            $save = $this->db->query("UPDATE users SET " . $data . " WHERE id = " . $id);
        }

        if ($save) {
            return 1;
        }
    }


	function save_admin()
	{
		include 'db_connect.php'; // Include database connection

		// Extract form data
		extract($_POST);
		// Extract file data
		extract($_FILES);

		// Handle file upload
		$picture_path = '';
		if (isset($picture) && $picture['error'] == UPLOAD_ERR_OK) {
			$upload_dir = 'image/'; // Assuming the 'image' folder is in the current directory
			$uploaded_file = $upload_dir . basename($picture['name']);
			if (move_uploaded_file($picture['tmp_name'], $uploaded_file)) {
				$picture_path = $uploaded_file;
			} else {
				// Handle file upload error
				return 0; // Return 0 to indicate failure
			}
		}

		// Hash password if it's not empty and type is 1
		$hashed_password = ($type == 1 && !empty($password)) ? md5($password) : '';

		// Construct the data string for SQL query
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", picture_path = '$picture_path' "; // Save the file path to the database
		$data .= ", type = '$type' ";
		if (!empty($hashed_password)) {
			$data .= ", password = '$hashed_password' ";
		}

		// Perform database query to save or update user data
		if (empty($id)) {
			$save_query = "INSERT INTO users SET " . $data;
		} else {
			$save_query = "UPDATE users SET " . $data . " WHERE id = " . $id;
		}

		// Execute the query
		$save = $conn->query($save_query);

		if ($save) {
			return 1;
		} else {
			return 0;
		}
	}

	function save_category()
	{
		extract($_POST);
		$data = " category = '$category' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO category_list set " . $data);
			if ($save)
				return 1;
		} else {
			$save = $this->db->query("UPDATE category_list set " . $data . " where id =" . $id);
			if ($save)
				return 2;
		}
	}
	function save_partylist()
	{
		extract($_POST);
		$data = " partylist = '$partylist' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO party_list set " . $data);
			if ($save)
				return 1;
		} else {
			$save = $this->db->query("UPDATE party_list set " . $data . " where id =" . $id);
			if ($save)
				return 2;
		}
	}
	function delete_category()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM category_list where id=" . $id);
		if ($delete)
			return 1;
	}
	function delete_partylist()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM party_list where id=" . $id);
		if ($delete)
			return 1;
	}

	function save_voting()
	{
		extract($_POST);
		// Construct the data string for SQL query
		$data = " title = '$title' ";
		$data .= " , description = '$description' ";
		$data .= " , votedate = '$votedate' ";
		$data .= " , starttime = '$starttime' ";
		$data .= " , endtime = '$endtime' ";

		if (empty($id)) {
			// If id is empty, perform an INSERT operation
			$save = $this->db->query("INSERT INTO voting_list SET " . $data);
			if ($save)
				return 1; // Return 1 for successful insertion
		} else {
			// If id is not empty, perform an UPDATE operation
			$save = $this->db->query("UPDATE voting_list SET " . $data . " WHERE id =" . $id);
			if ($save)
				return 2; // Return 2 for successful update
		}
	}


	function get_voting()
	{
		extract($_POST);
		$get = $this->db->query("SELECT * FROM voting_list where id=" . $id);
		if ($get->num_rows > 0) {
			return json_encode($get->fetch_assoc());
		}
	}
	function delete_voting()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM voting_list where id=" . $id);
		if ($delete)
			return 1;
	}

	function update_voting()
	{
		extract($_POST);
		$this->db->query("UPDATE users SET has_voted = 0");
		$this->db->query("UPDATE voting_list set is_default = 0 where id !=" . $id);
		$update = $this->db->query("UPDATE voting_list set is_default = 1 where id= " . $id);
		if ($update)
			return 1;
	}
	function save_opt()
	{
		extract($_POST);
		$data = " category_id = '" . $category_id . "' ";
		$data .= ", opt_txt = '" . $opt_txt . "' ";
		$data .= ", can_course = '" . $can_course . "' ";
		$data .= ", voting_id = '" . $voting_id . "' ";
		$data .= ", partylist_id = '" . $partylist_id . "' ";

		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/img/' . $fname);
			$data .= ", image_path = '" . $fname . "' ";
			if (!empty($id)) {
				$path = $this->db->query("SELECT * FROM voting_opt where id=" . $id)->fetch_array()['image_path'];
				if (!empty($path))
					unlink('assets/img/' . $path);
			}
		}

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO voting_opt set " . $data);
			if ($save)
				return 1;
		} else {
			$save = $this->db->query("UPDATE voting_opt set " . $data . " where id=" . $id);
			if ($save)
				return 2;
		}
	}

	function delete_candidate()
	{
		extract($_POST);
		$path = $this->db->query("SELECT * FROM voting_opt where id=" . $id)->fetch_array()['image_path'];
		$delete = $this->db->query("DELETE FROM voting_opt where id=" . $id);
		if ($delete) {
			unlink('assets/img/' . $path);
			return 1;
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " category_id = $category_id ";
		$data .= ", voting_id = $voting_id ";
		$data .= ", max_selection = $max_selection ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO voting_cat_settings set " . $data);
		} else {
			$save = $this->db->query("UPDATE voting_cat_settings set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}

	function submit_vote()
	{
		extract($_POST);
		$set = 0;
		foreach ($opt_id as $k => $val) {
			foreach ($val as $key => $v) {
				$data = " voting_id = $voting_id ";
				$data .= ", category_id = $k ";
				$data .= ", user_id = '" . $_SESSION['login_id'] . "' ";
				$data .= ", voting_opt_id = $v ";
				// echo $data.'<br>';
				$save[] = $this->db->query("INSERT INTO votes SET " . $data);
				$set++;
			}
		}

		// Update has_voted column for the user
		$this->db->query("UPDATE users SET has_voted = 1 WHERE id = '" . $_SESSION['login_id'] . "'");

		// If there are no votes saved, return 0 or handle accordingly
		if (empty($save)) {
			return 0;
		}

		// If all votes are saved successfully, return 1
		return 1;
	}



	// function save_time(){
	// 	extract($_POST);
	// 	$time_duration = $_POST['time_duration'];

	// 	// You might want to do some validation or sanitization of the input here

	// 	// Check if a record already exists in the voting_list table
	// 	$check_query = $conn->query("SELECT * FROM voting_list WHERE id = '$id'"); 
	// 	$row_count = $check_query->num_rows;

	// 	if ($row_count > 0) {
	// 		// Update the existing record
	// 		$conn->query("UPDATE voting_list SET timer_duration = '$time_duration' WHERE id = '$id'");
	// 		echo 2; // 2 indicates successful update
	// 	} else {
	// 		// Insert a new record
	// 		$conn->query("INSERT INTO voting_list (timer_duration) VALUES ('$time_duration')");
	// 		echo 1; // 1 indicates successful insert
	// 	}
	// }




	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users WHERE id=" . $id);
		if ($delete) {
			return 1;
		} else {
			return $this->db->error; // Return the error for debugging
		}
	}
}
