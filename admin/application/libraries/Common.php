<?php
class Common{
    protected $CI;
    public function __construct() {
        $this->CI = & get_instance();
    }

	/**
	 * checkRecord
	 *
	 * @access	public
	 * @param	string	the username
	 * @param	string	what row to grab
	 * @return	string	the data
	 */
	function checkRecord($tname, $field, $val){
		$query = $this->CI->db->get_where($tname,array($field => $val),1,0);
		if($query->num_rows() >= 1){
			return true;
		}
		else{
			return false;
		}
	}

	function checkRecordId($tname, $field1, $val1, $field2, $val2){
		$query = $this->CI->db->get_where($tname,array($field1 => $val1, $field2 => $val2),1,0);
		if($query->num_rows() >= 1){
			return true;
		}
		else{
			return false;
		}
	}

	function getAdvisoryName($Id){
		$query =  $this->CI->db->query("Select advisor_name from q_advisor where advisor_id=$Id and advisor_status='active'");
		$result = $query->result_array();
		return $result[0]['advisor_name'];
	}


	function getadminProfile($Id){
		$query =  $this->CI->db->query("Select admin_id,fullname,email,profile_pic from b_admin where admin_id=$Id");
		$result = $query->result_array();
		if(file_exists( FILEPATH. $result[0]['profile_pic']) && $result[0]['profile_pic'] != ""){
            $profile_pic=FILEURL.$result[0]['profile_pic'];      
        }else{
            $profile_pic =ADMIN.'/img/person.jpeg';
        } 
        $response = ['admin_id' =>  $result[0]['admin_id'],'fullname' =>  $result[0]['fullname'],'email' =>  $result[0]['email'],'profile_pic' =>  $profile_pic];
		return $response;
	}


	function do_upload($locationDir,$key,$id){
		// echo $id; die;
		$resultArr = array();
		$resultArr['name'] = '';
		if (!empty($_FILES[$key])){
			$myFile = $_FILES[$key];
			$target_dir = dirname($_SERVER["SCRIPT_FILENAME"])."/".$locationDir.'/';
			//echo $target_dir; die;
			$target_file = $target_dir . basename($myFile["name"]);
			// ensure a safe filename
			$myfilename = str_replace(' ','',$myFile["name"]);
			$name = $id.'_'.time().$myfilename;
		//	echo $name; die;
			//$name = uniqid();
			// preserve file from temporary directory
			$success = move_uploaded_file($myFile["tmp_name"],$target_dir .$name);
			if (!$success) {
					$resultArr['message'] = "An error occured!,please try again.";
			}
			else{
				$resultArr['message'] = "1";
				$resultArr['name'] = $name;
				$mimeType = mime_content_type($target_dir.'/'.$name);
				$fileType = explode('/', $mimeType)[0]; // video|image				
			}
		}
		else{
			$resultArr['message'] = "File not exist.";
		}
		return $resultArr; 
	}

	function generateRandomString($length = 6) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}


	function timeAgo($time_ago){
	    $time_ago = strtotime($time_ago);
	    $cur_time   = time();
	    $time_elapsed   = $cur_time - $time_ago;
	   // echo $time_elapsed; die;
	    $seconds    = $time_elapsed ;
	    $minutes    = round($time_elapsed / 60 );
	    $hours      = round($time_elapsed / 3600);
	    $days       = round($time_elapsed / 86400 );
	    $weeks      = round($time_elapsed / 604800);
	    $months     = round($time_elapsed / 2600640 );
	    $years      = round($time_elapsed / 31207680 );
	    // Seconds
	    if($seconds <= 60){
	        return "just now";
	    }
	    //Minutes
	    else if($minutes <=60){
	        if($minutes==1){
	            return "1 minute ago";
	        }
	        else{
	            return "$minutes minutes ago";
	        }
	    }
	    //Hours
	    else if($hours <=24){
	        if($hours==1){
	            return "an hour ago";
	        }else{
	            return "$hours hrs ago";
	        }
	    }
	    //Days
	    else if($days <= 7){
	        if($days==1){
	            return "yesterday";
	        }else{
	            return "$days days ago";
	        }
	    }
	    //Weeks
	    else if($weeks <= 4.3){
	        if($weeks==1){
	            return "a week ago";
	        }else{
	            return "$weeks weeks ago";
	        }
	    }
	    //Months
	    else if($months <=12){
	        if($months==1){
	            return "a month ago";
	        }else{
	            return "$months months ago";
	        }
	    }
	    //Years
	    else{
	        if($years==1){
	            return "one year ago";
	        }else{
	            return "$years years ago";
	        }
	    }
	}

	function responsetime($time_ago){
	//	echo $time_ago; die;
		if(!empty($time_ago) && $time_ago!='0.0'){
			$time_elapsed    = $time_ago*60 ;
		    $seconds    = $time_ago*60 ;
		    $minutes    = round($time_elapsed / 60 );
		    $hours      = round($time_elapsed / 3600);
		    $days       = round($time_elapsed / 86400 );
		    $weeks      = round($time_elapsed / 604800);
		    $months     = round($time_elapsed / 2600640 );
		    $years      = round($time_elapsed / 31207680 );
		    // Seconds
		    if($seconds <= 60){
		        return "just now";
		    }
		    //Minutes
		    else if($minutes <=60){
		        if($minutes==1){
		            return "$minutes minute";
		        }
		        else{
		            return "$minutes minutes";
		        }
		    }
		    //Hours
		    else if($hours <=24){
		        if($hours==1){
		            return "$hours hour";
		        }else{
		            return "$hours hours";
		        }
		    }
		    //Days
		    else if($days <= 7){
		        if($days==1){
		            return "$days day";
		        }else{
		            return "$days days";
		        }
		    }
		    //Weeks
		    else if($weeks <= 4.3){
		        if($weeks==1){
		            return "$weeks week";
		        }else{
		            return "$weeks weeks";
		        }
		    }
		    //Months
		    else if($months <=12){
		        if($months==1){
		            return "$months month";
		        }else{
		            return "$months months";
		        }
		    }
		    //Years
		    else{
		        if($years==1){
		            return "one year";
		        }else{
		            return "$years years";
		        }
		    }
		}else{
			return 'No history';
		}
	}







}