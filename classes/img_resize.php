<?php
class ResizeIMG{
	public $image;
	public $img_type;
	public $user;
	
	public function __construct($user){
		$this->user = $user;
	}
	public function loadFile($dir,$filename){
		$doc_path = realpath(__DIR__ . '/..');
		$full_path = "$doc_path$dir/".$this->user."/$filename";
		$infos = getimagesize($full_path);
		$this->img_type = $infos[2];
		if($this->img_type == IMAGETYPE_PNG){
			$this->image = imagecreatefrompng($full_path);
		}else if($this->img_type == IMAGETYPE_GIF){
			$this->image = imagecreatefromgif($full_path);
		}else{
			$this->image = imagecreatefromjpeg($full_path);
		}
	}
	public function saveFile($dir,$filename,$compression){
		$doc_path = realpath(__DIR__ . '/..');
		if($this->img_type == IMAGETYPE_PNG){
			imagepng($this->image,"$doc_path$dir/".$this->user."/$filename");
		}else if($this->img_type == IMAGETYPE_GIF){
			imagegif($this->image,"$doc_path$dir/".$this->user."/$filename");
		}else{
			imagejpeg($this->image,"$doc_path$dir/".$this->user."/$filename",$compression);
		}
	}
	public function getWidth(){return imagesx($this->image);}
	public function getHeight(){return imagesy($this->image);}
	
	public function output(){
		if($this->img_type == IMAGETYPE_PNG){
			return imagepng($this->image);
		}else if($this->img_type == IMAGETYPE_GIF){
			return imagegif($this->image);
		}else{
			return imagejpeg($this->image);
		}
	}
	public function resizeH($width){
		$ratio = $width / $this->getHeight();
		$height = $this->getHeight() * $ratio;
		$this->resize($width,$height);
	}
	public function resizeW($width){
		$ratio = $width / $this->getWidth();
		$height = $this->getHeight() * $ratio;
		$this->resize($width,$height);
	}
	
	public function resize($width,$height){
		$new_image = imagecreatetruecolor($width,$height);
		if($this->img_type == IMAGETYPE_GIF || $this->img_type == IMAGETYPE_PNG){
			$curTransparent = imagecolortransparent($this->image);
			if($curTransparent != -1){
				$transColor = imagecolorsforindex($this->image,$curTransparent);
				$curTransparent = imagecolorallocate($new_image,$transColor['red'],$transColor['green'],$transColor['blue']);
				imagefill($new_image, 0, 0, $curTransparent);
				imagecolortransparent($new_image, $curTransparent);
			}else if($this->img_type == IMAGETYPE_PNG){
				imagealphablending($new_image, false);
				$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
				imagefill($new_image, 0, 0, $color);
				imagesavealpha($new_image, true);
			}
			
		}
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}
}