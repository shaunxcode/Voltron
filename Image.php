<?php

class Voltron_Image 
{
	private $fileName;
	private $image; 
	
	public static $creationFuncs = array(
		'file' => array(
			IMAGETYPE_GIF => 'imagecreatefromgif', 
			IMAGETYPE_JPEG => 'imagecreatefromjpeg', 
			IMAGETYPE_PNG => 'imagecreatefrompng',
			IMAGETYPE_WBMP => 'imagecreatefrombmp',
			IMAGETYPE_XBM => 'imagecreatefromxbm'),
		'resource' => array(
			IMAGETYPE_GIF => 'imagegif',
			IMAGETYPE_JPEG => 'imagejpeg',
			IMAGETYPE_PNG => 'imagepng',
			IMAGETYPE_WBMP => 'imagewbmp',
			IMAGETYPE_XBM => 'imagexbm'));
		
	public function __construct($fileName)
	{
		$this->fileName = $fileName;

		if(isset(self::$creationFuncs['file'][$this->getImageType()])) {
			$this->image = apply(self::$creationFuncs['file'][$this->getImageType()], $this->fileName);
		} else {
			throw new Exception("Image is not supported");
		}
	}
	
	public function getImageType()
	{
		return exif_imagetype($this->fileName);
	}
	
	public function getWidth()
	{
		return imagesx($this->image);
	}
	
	public function getHeight()
	{
		return imagesy($this->image);
	}

	public function scale($width, $height)
	{
		$w = $this->getWidth();
		$h = $this->getHeight();
		if(($h / $w) <= ($width / $height)) {
			$new_w = $width;
			$ratio = $h * $width;
			$new_h = $ratio / $w;
		} else {
			$new_h = $height;
			$ratio = $w * $height;
			$new_w = $ratio / $h;
		}
		
		$newImg = imagecreatetruecolor($new_w, $new_h);
		imagecopyresized($newImg, $this->image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
		$this->image = $newImg;
		return $this;
	}
		
	public function getFileName()
	{
		return $this->fileName;
	}
	
	public function getJustFileName()
	{
		return array_pop(explode('/', $this->fileName));
	}
	
	public function copy($source, $destination)
	{
		return Voltron_File::copy($this->fileName, $destination) ? new Voltron_Image($destination) : false;
	}
	
	public function saveAs($fileName, $type = false) 
	{
		return apply(self::$creationFuncs['resource'][$type ? $type : $this->getImageType()], $this->image, $fileName) ? new Voltron_Image($fileName) : false;
	}
}