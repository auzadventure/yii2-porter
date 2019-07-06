<?php
namespace auzadventure\porter;

use Yii;
use yii\base\Model;


class Porter extends Model {

	// Path to the Containers
	public $containerPath;

	// Path to mysql
	public $mysqlPath;

	// Database Name
	public $dbName;


	public function __construct() {
		$this->containerPath = dirname(__FILE__) . "containers";

	}


	public function backup($name) {

	}

	public function restore() {

	}


	public function listContainers() {

	}

	// Create if Does not exist 
	public function writeDir($path) {
		file_exists( ) dirname(path, levels)
	}
}
