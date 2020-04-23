<?php

namespace App\Controller;

use Nette\Http\FileUpload;

class FileController {

	/** @var string */
	private $pathDb;

	/** @var string */
	private $path;

	/**
	 * @param FileUpload $fileUpload
	 * @param array $formats example: ["jpg", "png", ...etc]
	 * @return bool
	 */
	public function upload(FileUpload $fileUpload, array $formats, $baseUrl) {
		$suffix = pathinfo($fileUpload->name, PATHINFO_EXTENSION);
		if (!in_array(strtolower($suffix), $formats)) {
			return false;
		}
		$this->pathDb = $baseUrl . 'upload/' . date("Ymd-His") . "-" . $fileUpload->name;
		$this->path = UPLOAD_PATH . '/' . date("Ymd-His") . "-" . $fileUpload->name;
		$fileUpload->move($this->path);

		return true;
	}

	/**
	 * @return string
	 */
	public function getPathDb() {
		return $this->pathDb;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}
}