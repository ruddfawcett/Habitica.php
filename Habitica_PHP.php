<?php
	/*
     * ORIGINAL AUTHOR: RUDD FAWCETT
     * MODIFIED BY: DARIUS LAM
     * COMPATIBLE WITH HABITICA API V2
	 */

class Habitica{
	public $userId;
	public $apiToken;
	public $apiURL;
	
	/**
	 * Creates a new Habitica instance
	 */
	 
	public function __construct ($userId,$apiToken) {

		$this->userId = $userId;
		$this->apiToken = $apiToken;
		$this->apiURL = "https://habitica.com/api/v2/user";
		
		if(!extension_loaded("cURL")) {
			throw new Exception("This Habitica PHP API class requires cURL in order to work.");
		}
	}
	
	/**
	 * Creates a new task for the userId and apiToken HabitRPG is initiated with
	 * @param array $newTaskParams required keys: type and text (title)
	 * @param array $newTaskParams optional keys: value, note
	 */
	
	public function newTask($newTaskParams) {
		if(is_array($newTaskParams)) {
			if(!empty($newTaskParams['type']) && !empty($newTaskParams['text'])) {
				$newTaskParamsEndpoint=$this->apiURL."/tasks";
				$newTaskPostBody=array();
				$newTaskPostBody['type'] = $newTaskParams['type'];
                $newTaskPostBody['text'] = $newTaskParams['text'];
				if(!empty($newTaskParams['value'])) {
					$newTaskPostBody['value']=$newTaskParams['value'];
				}
				if(!empty($newTaskParams['note'])) {
					$newTaskPostBody['note']=$newTaskParams['note'];
				}
				
				$newTaskPostBody=json_encode($newTaskPostBody);
				
				return $this->curl($newTaskParamsEndpoint,"POST",$newTaskPostBody);
			}
			else {
				throw new Exception("Required keys of $newTaskParams are null.");
			}
		}
		else {
			throw new Exception("newTask takes an array as it's parameter.");
		}
	}
	
    /**
     * Returns a task's id using it's title/text
     * @param string $taskName   
     */
    
    public function getTaskId($taskName){
        $all_tasks = $this->userTasks()['habitRPGData'];
        foreach($all_tasks as $task){
            if($task['text'] == $taskName){
                return $task['id'];
            }
        }
        return 'No task found with that name';
    }
    
	/**
	 * Up votes or down votes a task by taskId using apiToken and userId
	 * @param array $scoringParams required keys: taskId and direction ('up' or 'down')
	 * @param array $scoringParams optional keys: title, service and icon
	 */
	
	public function taskScoring($scoringParams) {
		if(is_array($scoringParams)) {
			if(!empty($scoringParams['taskId']) && !empty($scoringParams['direction'])) {
				$scoringEndpoint="https://habitica.com/api/v2/user/tasks/".$scoringParams['taskId']."/".$scoringParams['direction'];
				$scoringPostBody=array();
				$scoringPostBody['apiToken']=$this->apiToken;
				if(!empty($scoringParams['title'])) {
					$scoringPostBody['title']=$scoringParams['title'];
				}
				if(!empty($scoringParams['service'])) {
					$scoringPostBody['service']=$scoringParams['service'];
				}
				if(!empty($scoringParams['icon'])) {
					$scoringPostBody['icon']=$scoringParams['icon'];
				}
				
				$scoringPostBody=json_encode($scoringPostBody);
				
				return $this->curl($scoringEndpoint,"POST",$scoringPostBody);
			}
			else {
				throw new Exception("Required keys of $scoringParams are null.");
			}
		}
		else {
			throw new Exception("taskScoring takes an array as it's parameter.");
		}
	}	
	
	/**
	 * Grabs all a user's info using the apiToken and userId
	 * @function userStats() no parameter's required, uses userId and apiToken
	 */
	
	public function userStats() {
		return $this->curl($this->apiURL,"GET",NULL);
	}
	
	/**
	 * Gets a JSON feed of all of a users task using apiToken and userId
	 * @param string $userTasksType ex. habit,todo,daily (optional null value)
	 * @param string $userTasksType allows to output only certain type of task
	 */
	
	public function userTasks($userTasksType=NULL) {
		$userTasksEndpoint=$this->apiURL."/tasks";
		if($userTasksType != NULL) {
			$userTasksEndpoint=$this->apiURL."/tasks?type=".$userTasksType;
		}
			return $this->curl($userTasksEndpoint,"GET",NULL);
	}	
	
	/**
	 * Get's info for a certain task only for the apiToken and userId passed
	 * @param string $taskId taskId for user task, which can be grabbed from userTasks()
	 */
	
	public function userGetTask($taskId) {
		if(!empty($taskId)) {
			$userGetTaskEndpoint=$this->apiURL."/tasks/".$taskId;
			
			return $this->curl($userGetTaskEndpoint,"GET");
		}
		else {
			throw new Exception("userGetTask needs a value as it's parameter.");
		}
	}
	
	/**
	 * Updates a task's for a userId and apiToken combo and a taskId
	 * @param array $updateParams required keys: taskId and text
	 */
	 	
	public function updateTask($updateParams) {
		if(is_array($updateParams)) {
			if(!empty($updateParams['taskId']) && !empty($updateParams['text'])) {
				$updateParamsEndpoint=$this->apiURL."/tasks/".$updateParams['taskId'];
				$updateTaskPostBody=array();
				$updateTaskPostBody['text'] = $updateParams['text'];
				
				$updateTaskPostBody=json_encode($updateTaskPostBody);
				
				return $this->curl($updateParamsEndpoint,"PUT",$updateTaskPostBody);
			}
			else {
				throw new Exception("Required keys of $updateParams are null.");
			}
		}
		else {
			throw new Exception("updateTask takes an array as it's parameter.");
		}
	}
	
	/**
	 * Performs all cURLs that are initated in each function, private function
	 * @param string $endpoint is the URL of the cURL
	 * @param string $curlType is the type of the cURL for the switch, e.g. PUT, POST, GET, etc.
	 * @param array $postBody is the data that is posted to $endpoint in JSON
	 */
	
	private function curl($endpoint,$curlType,$postBody) {
		$curl = curl_init();
		$curlArray = array(
							CURLOPT_RETURNTRANSFER => true, 
							CURLOPT_HEADER => false, 
							//CURLOPT_ENCODING => "gzip",
							CURLOPT_HTTPHEADER => array(
														"Content-type: application/json",
														"x-api-user:".$this->userId,
														"x-api-key:".$this->apiToken),
							CURLOPT_URL => $endpoint);
		switch($curlType) {
			case "POST":
				$curlArray[CURLOPT_POSTFIELDS] = $postBody;
				$curlArray[CURLOPT_POST] = true;
				curl_setopt_array($curl, $curlArray);
				break;
			case "GET":
				curl_setopt_array($curl, $curlArray);
				break;
			case "PUT":
				$curlArray[CURLOPT_CUSTOMREQUEST] = "PUT";				
				$curlArray[CURLOPT_POSTFIELDS] = $postBody;
				curl_setopt_array($curl, $curlArray);
				break;
			case "DELETE":
				break;
			default:
				throw new Exception("Please use a valid method as the cURL type.");
		}
		
		$habitRPGResponse = curl_exec($curl);
		$habitRPGHTTPCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		curl_close($curl);
		
		if ($habitRPGHTTPCode == 200) {
			return array("result"=>true,"habitRPGData"=>json_decode($habitRPGResponse,true));
		}
		else {
		$habitRPGResponse = json_decode($habitRPGResponse,true);
			return array("error"=>$habitRPGResponse['err'],"details"=>array("httpCode"=>$habitRPGHTTPCode,"endpoint"=>$endpoint,"dataSent"=>json_decode($postBody,true)));
		}
	}
}
?>
