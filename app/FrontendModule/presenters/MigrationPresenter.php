<?php

namespace App\FrontendModule\Presenters;

use App\Forms\LitterApplicationDetailForm;
use App\Model\DogRepository;
use App\Model\Entity\DogPicEntity;
use App\Model\LitterApplicationRepository;
use App\Model\ShowDogRepository;
use App\Model\ShowRefereeRepository;
use App\Model\UserRepository;
use Nette\Utils\Finder;

class MigrationPresenter extends BasePresenter	 {

	/** @var DogRepository */
	private $dogRepository;

	/** @var UserRepository  */
	private$userRepository;

	/** @var ShowRefereeRepository  */
	private $showRefereeRepository;

	/** @var ShowDogRepository */
	private $showDogRepository;

	/** @var LitterApplicationRepository */
	private $litterApplicationRepository;

	/**
	 * MigrationPresenter constructor.
	 * @param DogRepository $dogRepository
	 * @param UserRepository $userRepository
	 * @param ShowRefereeRepository $showRefereeRepository
	 * @param ShowDogRepository $showDogRepository
	 * @param LitterApplicationRepository $litterApplicationRepository
	 */
	public function __construct(
		DogRepository $dogRepository,
		UserRepository $userRepository,
		ShowRefereeRepository $showRefereeRepository,
		ShowDogRepository $showDogRepository,
		LitterApplicationRepository $litterApplicationRepository
	) {
		$this->dogRepository = $dogRepository;
		$this->userRepository = $userRepository;
		$this->showRefereeRepository = $showRefereeRepository;
		$this->showDogRepository = $showDogRepository;
		$this->litterApplicationRepository = $litterApplicationRepository;
	}

	/**
	 * Migrace obrázku
	 * volání www/migration/pic-migration
	 * @throws \Nette\Application\AbortException
	 */
	public function actionPicMigration() {
		$pocet = 0;
		/**
		 * @var  $key
		 * @var \SplFileInfo $file
		 */
		foreach (Finder::findFiles('*.jpg')->in('./!migrace/genPhoto') as $key => $file) {
			try {
				// $key; // $key je řetězec s názvem souboru včetně cesty
				$dogPicEntity = new DogPicEntity();
				if (strpos($file->getFilename(), 'Main') !== false) {
					$dogPicEntity->setVychozi(true);
				} else {
					$dogPicEntity->setVychozi(false);
				}

				$baseUrl = $this->getHttpRequest()->getUrl()->getBaseUrl();
				$pathDb = $baseUrl . 'upload/' . date("Ymd-His") . "-" . $file->getFilename();    // cesta do DB
				$path = UPLOAD_PATH . '/' . date("Ymd-His") . "-" . $file->getFilename();    // sem fyzicky nahrávám
				copy($file->getRealPath(), $path);

				$dogPicEntity->setCesta($pathDb);
				preg_match_all('!\d+!', $file->getFilename(), $matches);
				$dogPicEntity->setPID((int)implode('', $matches[0]));
				$this->dogRepository->saveDogPic($dogPicEntity);
				$pocet++;
			} catch (\Exception $e) {
				echo "Soubor {$key} nelze nahraát z důvodu: " . $e->getMessage() . "<br />";
			}
		}
		echo "Zpracováno obrázků: " . $pocet;
		$this->terminate();
	}

	/**
	 * Migrace uživatelů
	 */
	public function actionUserMigration() {
		$migrationResult = $this->userRepository->migrateUserFromOldStructure();
		file_put_contents('user_migration_log.txt', print_r($migrationResult, true));
		dump($migrationResult);
		$this->terminate();
	}

	/**
	 * Migrace rozhodčích ve výstvách
	 */
	public function actionShowRefereeMigration() {
		try {
			$this->showRefereeRepository->migrateRefereeFromOldStructure();
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
		echo "<br />hotovo";
		$this->terminate();
	}

	public function actionShowDogMigration() {
		try {
			$this->showDogRepository->migrateDogsFromOldStructure();
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
		echo "<br />hotovo";
		$this->terminate();
	}

	/**
	 * Projde přihlášky a doplní potřebné údaje a přečísluje číselníky
	 */
	public function actionRecalcLitterApps() {
		try {
			$apps = $this->litterApplicationRepository->findLitterApplications();
			foreach ($apps as $app) {
				$params = $app->getDataDecoded();
				// PLEMENO
				if (isset($params['plemeno'])) {
					$params['plemeno'] = $this->getNewBreed($params['plemeno']);
					$params['Plemeno'] = $params['plemeno'];
				}

				if (isset($params['matkaBarva'])) {
					$params['matkaBarva'] = $this->getFurColor($params['matkaBarva']);
				}
				if (isset($params['matkaSrst'])) {
					$params['matkaSrst'] = $this->getFurType($params['matkaSrst']);
				}

				if (isset($params['otecBarva'])) {
					$params['otecBarva'] = $this->getFurColor($params['otecBarva']);
				}
				if (isset($params['otecSrst'])) {
					$params['otecSrst'] = $this->getFurType($params['otecSrst']);
				}

				// vyplněná data
				for($i=1; $i<=LitterApplicationDetailForm::NUMBER_OF_LINES; $i++) {
					if (isset($params['srst'.$i])) {
						$params['srst'.$i] = $this->getFurType($params['srst'.$i]);
					}
					if (isset($params['pohlavi'.$i])) {
						$params['pohlavi'.$i] = $this->getSex($params['pohlavi'.$i]);
					}
					if (isset($params['barva'.$i])) {
						$params['barva'.$i] = $this->getFurColor($params['barva'.$i]);
					}
				}
				$data = base64_encode(gzdeflate(serialize($params)));
				$app->setData($data);
				$this->litterApplicationRepository->save($app);
			}
		} catch (\Exception $e) {
			dump($e);
			echo $e->getMessage();
		}
		echo "<br />hotovo";
		$this->terminate();

	}

	/**
	 * Vrátí novoi hodnotu číselníku pro Plemeno
	 * @param $oldBreed
	 * @return int|null
	 */
	private function getNewBreed($oldBreed) {
		switch($oldBreed) {
			case 1:
			return 17;
			break;

			case 2:
				return 18;
				break;

			case 3:
				return 19;
				break;

			default:
				return null;
				break;
		}
	}

	/**
	 * Vrátí novou hodnotu číselníku pro Barvu
	 * @param $oldColor
	 * @return int|null
	 */
	private function getFurColor($oldColor) {
		switch ($oldColor) {
			case 1:
				return 23;
				break;

			case 2:
				return 22;
				break;

			case 3:
				return 21;
				break;

			case 4:
				return 24;
				break;

			case 15:
				return null;
				break;

			default:
				return null;
				break;
		}
	}

	/**
	 * Vrátí novou hodnutu číselníku pro Srts
	 * @param $oldFur
	 * @return int|null
	 */
	private function getFurType($oldFur) {
		switch($oldFur) {
			case 1:
				return 45;
				break;

			case 2:
				return 44;
				break;

			default:
				return null;
				break;
		}
	}

	/**
	 * Vrátí novou hodnutu číselníku pro Pohlaví
	 * @param $oldSex
	 * @return int|null
	 */
	private function getSex($oldSex) {
		switch ($oldSex) {
			case 1:
				return 29;
				break;

			case 2:
				return 30;
				break;

			default:
				return null;
				break;
		}
	}
}