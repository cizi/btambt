<?php

namespace App\Controller;

use Nette\Http\FileUpload;

class FileController {

    /** @var int */
    const MAX_FILE_SIZE = 500;  // in kB

    /** @var string */
	private $pathDb;

	/** @var string */
	private $path;

	/**
	 * @param FileUpload $fileUpload
	 * @param array $formats example: ["jpg", "png", ...etc]
     * @param string $baseUrl
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
     * @param FileUpload $fileUpload
     * @param int $allowedFileSize
	 * @return bool
     */
    public function isInAllowedSize(FileUpload $fileUpload, $allowedFileSize = null) {
        $allowedFileSize = (!empty($allowedFileSize) ? $allowedFileSize : self::MAX_FILE_SIZE * 1000);
        if ($fileUpload->getSize() > $allowedFileSize) {
            return false;
        } else {
            return true;
        }
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