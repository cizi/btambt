<?php

namespace App\FrontendModule\Presenters;

use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\RefereeRepository;
use App\Model\ShowDogRepository;
use App\Model\ShowRefereeRepository;
use App\Model\ShowRepository;

class FeItem1velord7Presenter extends FrontendPresenter {

	/** @var  ShowRepository */
	private $showRepository;

	/** @var  EnumerationRepository */
	private $enumerationRepository;

	/** @var  ShowRefereeRepository */
	private $showRefereeRepository;

	/** @var ShowDogRepository  */
	private $showDogRepository;

	/** @var DogRepository  */
	private $dogRepository;

	/** @var  RefereeRepository */
	private $refereeRepository;

	public function __construct(
		ShowRepository $showRepository,
		EnumerationRepository $enumerationRepository,
		ShowRefereeRepository $showRefereeRepository,
		ShowDogRepository $showDogRepository,
		DogRepository $dogRepository,
		RefereeRepository $refereeRepository
	) {
		$this->showRepository = $showRepository;
		$this->enumerationRepository = $enumerationRepository;
		$this->showRefereeRepository = $showRefereeRepository;
		$this->showDogRepository = $showDogRepository;
		$this->dogRepository = $dogRepository;
		$this->refereeRepository = $refereeRepository;
	}

	public function startup() {
		parent::startup();
		$this->template->enumRepo = $this->enumerationRepository;
		$this->template->lang = $this->langRepository->getCurrentLang($this->session);
		$this->template->showRefereeRepository = $this->showRefereeRepository;
		$this->template->showDogRepository = $this->showDogRepository;
		$this->template->dogRepository = $this->dogRepository;
		$this->template->refereeRepository = $this->refereeRepository;
	}

	public function actionDefault() {
		$this->template->shows = $this->showRepository->findShows();

	}

	/**
	 * @param int $id
	 */
	public function actionDetail($id) {
		$this->template->idShow = $id;
		$this->template->show = $this->showRepository->getShow($id);
		$this->template->referees = $this->showRefereeRepository->findRefereeByShowFrontEnd($id);
		$this->template->dogs = $this->showDogRepository->findDogsByShowForDetail($id);
		$this->template->showDogRepo = $this->showDogRepository;
	}

	/**
	 * @param int $id
	 */
	public function actionReferee($id) {
		$this->template->referee = $this->refereeRepository->getReferee($id);
	}
}