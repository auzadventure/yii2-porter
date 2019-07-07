<?php
namespace auzadventure\porter;

use Yii;
use yii\base\Model;
use yii\helpers\{Html, Url, FileHelper};


class Porter extends Model {

	// Path to the Containers
	public $containerPath;

	public $relativePath;

	public $mysqlPath;

	public $mysqlDumpPath;

	// Database Name
	public $dbName;

	public $dataDir;

	public function __construct() {
		
		#Set MyDumpPath - Based on Your System
		$this->mysqlDumpPath = "C:\\xampp\\mysql\\bin\\mysqldump --user=root";
		
		#Set MysqlPath
		$this->mysqlPath     = "C:\\xampp\\mysql\\bin\\mysql --user=root";

		#Set Name of your DB
		$this->dbName = 'projects_osaka_2';

		#Set the path relative of where your data folder is 
		$this->dataDir = "./data";


		/*##############  Do Not Edit Anything Below *************/ 

		$realPath = realpath(Url::to('@app'))."\\";
		
		$filePath = dirname(__FILE__);
		$this->containerPath = $filePath . "\containers";

		//echo $this->containerPath;
		
		$this->relativePath = str_replace($realPath,"",$this->containerPath);
 		//echo $this->relativePath;
 		

		// Runs the Posts 

 		if(Yii::$app->request->post('action') == 'backup') {
 			$container = Yii::$app->request->post('container');
 			
 			$this->backup($container);
 			$this->jsRedirect();
 		}

 		if(Yii::$app->request->post('action') == 'restore') {
 			$container = Yii::$app->request->post('container');
 			
 			$this->restore($container);
 			$this->jsRedirect();
 		}
	}


	/* Backs up the data folder and sql to a container in the containers folder 
	 * For Vendor 
	 */
	public function backup($name) {

		$this->writeDir($name);

		$sqlFile = $name.'.sql';

		$command = "{$this->mysqlDumpPath} {$this->dbName} > {$this->containerPath}\\{$name}\\{$sqlFile}";
		//echo $command;
		exec($command);
		
		$newSqlFile = $this->relativePath."\\{$name}\\{$sqlFile}";
		if(file_exists($newSqlFile)) {
			echo "-- Database has been backed up into {$name}\\{$sqlFile}";
		}

		$newDataFolder = $this->relativePath."\\{$name}\\data";

		if(file_exists($newDataFolder)) $this->deleteDir($newDataFolder);

		if(FileHelper::copyDirectory($this->dataDir,$newDataFolder)) {
			echo "-- Data Folder has been backed up into {$name}\\{data}";	
		}
	}


	public function restore($name) {


		$dataFolder = "$this->containerPath\\{{$name}}";

		//Import the SQL File
		$sqlFile = $name.'.sql';

		$command = "{$this->mysqlPath} {$this->dbName} < {$this->containerPath}\\{$name}\\{$sqlFile}";
		$command = "cmd /c \"{$command}\"";
		
		echo $command;
		echo "<br>". exec($command);

		//Replace the DataFolder
		$newDataFolder = $this->relativePath."\\{$name}\\data";
		
		if(FileHelper::copyDirectory($newDataFolder,$this->dataDir)) {
			echo "<br><br> -- Data Folder has been restored from $newDataFolder to $this->dataDir";
			Yii::$app->session->setFlash('success',"{$name}has been restored");			
		}

	}

	//List All Available Containers
	public function listContainers() {
		// Container Folder 

		
		$dir = '.';
		$files = scandir($this->relativePath, 0);

		echo "<h4>Containers </h4>";
		echo "<table class='table table-bordered' style='width:300px'>";
		for($i = 2; $i < count($files); $i++) {
			$link = Html::a($files[$i],'#',['data'=>[
												'method'=>'post',
												'confirm'=> 'Restore This Container? Current data will be deleted!',
												'params'=>['action'=>'restore',
														   'container'=>$files[$i]
															]
												]
											]
							);			
			echo "<tr>
					<td> $link </td>
					<td></td>
				  </tr>";
		}
		echo "</table>";

	}


	// Create if Does not exist 
	public function writeDir($containerName) {

		$containerPath = $this->relativePath . "\\{$containerName}";
		if(!file_exists($containerPath)) mkdir($containerPath);
	}

	public function deleteDir($dirPath) {
	    if (! is_dir($dirPath)) {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {

	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);

	        }  
	    }
	    rmdir($dirPath);
	}


	public function backupForm() {

		?>
		<div class="row">
			<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">Backup Current SQL & Data in 'Container'</div>
				<div class="panel-body">
					<?= Html::beginForm('','post') ?>
					<?= Html::textInput('container','') ?>
					<?= Html::hiddenInput('action','backup') ?>
					<?= Html::submitButton('Backup',['class'=>'btn btn-primary', 'confirm'=> 'Are you Sure']) ?>
					<?= Html::endForm() ?>
				</div>
			</div>	
			</div>
		</div>
		<?php 
	}

	public function jsRedirect() {
		$thisPage = Url::to();
		$js = "window.location = \"{$thisPage}\"";
		Yii::$app->view->registerJS($js);
	}

}
