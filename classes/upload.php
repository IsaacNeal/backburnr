<?php
class Upload{
	private $filesArray = array();
	private $errors = array();
	public $maxSize;
	public $user;
	
	public function __construct($files,$max_size,$user){
		$this->filesArray = $files;
		$this->maxSize = $max_size;
		$this->user = $user;
	}
	public function getFileArray(){
		return $this->filesArray;
	}
	public function getExt(){
		$tnt = explode('.',$this->filesArray['fileName']);
		return end($tnt);
	}
	public function checkFile($regex){
		list($width,$height) = getimagesize($this->filesArray['file_tmp_name']);
			if(!preg_match($regex,$this->filesArray['fileName'])){
				array_push($this->errors,"We only allow formats .jpg, .png and .gif. Please use a file with one of those formats.");
			}
			else if($width < 10 || $height < 10){
				array_push($this->errors,"Your image is too small. Please upload a larger one.");
			}
			else if($this->filesArray['fileErrors'] == 1){
				array_push($this->errors,"Sorry, an unknown file error occured. Please try again.");
			}
			else if($this->filesArray['fileSize'] > $this->maxSize){
				array_push($this->errors,"The image you upladed is too large. Please use a smaller file.");
			}
			return $this->errors;
	}
	public function moveFile($file,$dir){
		if(count($this->errors) == 0){
			$new_file_name = rand(100000000000,999999999999).'.'.$this->getExt();
			if(file_exists($file)){
				unlink($file);
			}
			$doc_path = realpath(__DIR__ . '/..');
			$moveit = move_uploaded_file($this->filesArray['file_tmp_name'],"$doc_path$dir/$this->user/$new_file_name");
			if($moveit == true){
				return $new_file_name;
			}else{
				return false;
			}
		}else{
			return $this->errors;
		}
	}
}