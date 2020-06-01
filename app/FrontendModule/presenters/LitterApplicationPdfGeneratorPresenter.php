<?php

namespace App\FrontendModule\Presenters;

use App\Forms\LitterApplicationDetailForm;
use App\Model\EnumerationRepository;
use App\Model\LitterApplicationRepository;
use Nette\Application\AbortException;
use App\Model\Entity\DogEntity;

class LitterApplicationPdfGeneratorPresenter extends BasePresenter {

	/** @var LitterApplicationRepository */
	private $litterApplicationRepository;

	/** @var  EnumerationRepository */
	private $enumerationRepository;

	/**
	 * LitterApplicationPdfGeneratorPresenter constructor.
	 * @param LitterApplicationRepository $litterApplicationRepository
	 * @param EnumerationRepository $enumerationRepository
	 */
	public function __construct(LitterApplicationRepository $litterApplicationRepository, EnumerationRepository $enumerationRepository) {
		$this->litterApplicationRepository = $litterApplicationRepository;
		$this->enumerationRepository = $enumerationRepository;
	}

	/**
	 * @param int $id
	 * @throws AbortException
	 */
	public function renderDefault($id) {
        $litterApplication = $this->litterApplicationRepository->getLitterApplication($id);
		if ($litterApplication != null) {
            try {
                $latteParams = $litterApplication->getDataDecoded();
				$latteParams['puppiesLines'] = LitterApplicationDetailForm::NUMBER_OF_LINES;
				$latteParams['enumRepository'] = $this->enumerationRepository;
                $latteParams['currentLang'] = $this->langRepository->getCurrentLang($this->session);
                $latteParams['basePath'] = $this->getHttpRequest()->getUrl()->getBaseUrl();
                $latteParams["dateFormat"] = DogEntity::MASKA_DATA_ZOBRAZENI;
                $latteParams["PlemenoNazev"] = $this->enumerationRepository->findEnumItemByOrder($this->langRepository->getCurrentLang($this->session), $latteParams["Plemeno"]);
                $clubName = $this->enumerationRepository->findEnumItemByOrder($this->langRepository->getCurrentLang($this->session), $latteParams["cID"]);
                $latteParams["clubName"] = $clubName;

				$latte = new \Latte\Engine();
				$latte->setTempDirectory(__DIR__ . '/../../../temp/cache');
				$template = $latte->renderToString(__DIR__ . '/../templates/FeItem1velord9/pdf.latte', $latteParams);

				$pdf = new \Joseki\Application\Responses\PdfResponse($template);
				$pdf->documentTitle = LITTER_APPLICATION . "_" . date("Y-m-d_His");
				$this->sendResponse($pdf);
			} catch (AbortException $e) {
				throw $e;
			} catch (\Exception $e) {
			}
		} else {
			$message = sprintf(LITTER_APPLICATION_DOES_NOT_EXIST, $id);
			$this->flashMessage($message, "alert-danger");
			$this->redirect("FeItem1velord9:default");
		}
	}
}