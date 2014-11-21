<?php

namespace SS6\ShopBundle\Model\Mail;

use Doctrine\ORM\EntityManager;
use SS6\ShopBundle\Model\Domain\Domain;
use SS6\ShopBundle\Model\Mail\MailTemplate;
use SS6\ShopBundle\Model\Mail\MailTemplateRepository;
use SS6\ShopBundle\Model\Mail\AllMailTemplatesData;
use SS6\ShopBundle\Model\Order\Status\OrderStatusMailTemplateService;
use SS6\ShopBundle\Model\Order\Status\OrderStatusRepository;

class MailTemplateFacade {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \SS6\ShopBundle\Model\Mail\MailTemplateRepository
	 */
	private $mailTemplateRepository;

	/**
	 * @var \SS6\ShopBundle\Model\Order\Status\OrderStatusRepository
	 */
	private $orderStatusRepository;

	/**
	 * @var \SS6\ShopBundle\Model\Order\Status\OrderStatusMailTemplateService
	 */
	private $orderStatusMailTemplateService;

	/**
	 * @var \SS6\ShopBundle\Model\Domain\Domain;
	 */
	private $domain;

	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \SS6\ShopBundle\Model\Mail\MailTemplateRepository $mailTemplateRepository
	 * @param \SS6\ShopBundle\Model\Order\Status\OrderStatusRepository $orderStatusRepository
	 * @param \SS6\ShopBundle\Model\Order\Status\OrderStatusMailTemplateService $orderStatusMailTemplateService
	 * @param \SS6\ShopBundle\Model\Domain\Domain;
	 */
	public function __construct(
		EntityManager $em,
		MailTemplateRepository $mailTemplateRepository,
		OrderStatusRepository $orderStatusRepository,
		OrderStatusMailTemplateService $orderStatusMailTemplateService,
		Domain $domain
	) {
		$this->em = $em;
		$this->mailTemplateRepository = $mailTemplateRepository;
		$this->orderStatusRepository = $orderStatusRepository;
		$this->orderStatusMailTemplateService = $orderStatusMailTemplateService;
		$this->domain = $domain;
	}

	/**
	 * @param string $templateName
	 * @param int $domainId
	 * @return \SS6\ShopBundle\Model\Mail\MailTemplate
	 */
	public function get($templateName, $domainId) {
		return $this->mailTemplateRepository->findByNameAndDomainId($templateName, $domainId);
	}

	/**
	 * @param string $templateName
	 * @return \SS6\ShopBundle\Model\Mail\MailTemplate
	 */
	public function find($templateName) {
		return $this->mailTemplateRepository->findByName($templateName);
	}

	/**
	 * @param \SS6\ShopBundle\Model\Mail\MailTemplateData[] $mailTemplatesData
	 * @param int $domainId
	 */
	public function saveMailTemplatesData(array $mailTemplatesData, $domainId) {
		foreach ($mailTemplatesData as $mailTemplateData) {
			$mailTemplate = $this->mailTemplateRepository->getByNameAndDomainId($mailTemplateData->getName(), $domainId);
			$mailTemplate->edit($mailTemplateData);
		}

		$this->em->flush();
	}

	/**
	 * @return \SS6\ShopBundle\Model\Order\Status\OrderStatus[]
	 */
	public function getAllIndexedById() {
		return $this->orderStatusRepository->getAllIndexedById();
	}

	/**
	 * @param int $domainId
	 * @return \SS6\ShopBundle\Model\Mail\AllMailTemplatesData
	 */
	public function getAllMailTemplatesDataByDomainId($domainId) {
		$orderStatuses = $this->orderStatusRepository->findAll();
		$mailTemplates = $this->mailTemplateRepository->getAllByDomainId($domainId);

		$allMailTemplatesData = new AllMailTemplatesData();
		$allMailTemplatesData->setDomainId($domainId);
		$registrationMailTemplatesData = new MailTemplateData();
		$registrationMailTemplate = $this->mailTemplateRepository
			->findByNameAndDomainId(MailTemplate::REGISTRATION_CONFIRM_NAME, $domainId);
		if ($registrationMailTemplate !== null) {
			$registrationMailTemplatesData->setFromEntity($registrationMailTemplate);
		}
		$registrationMailTemplatesData->setName(MailTemplate::REGISTRATION_CONFIRM_NAME);

		$allMailTemplatesData->setRegistrationTemplate($registrationMailTemplatesData);

		$allMailTemplatesData->setOrderStatusTemplates(
			$this->orderStatusMailTemplateService->getOrderStatusMailTemplatesData($orderStatuses, $mailTemplates));

		return $allMailTemplatesData;
	}

	/**
	 * @param string $name
	 */
	public function createMailTemplateForAllDomains($name) {
		foreach ($this->domain->getAll() as $domainConfig) {
			$mailTemplate = new MailTemplate($name, $domainConfig->getId(), new MailTemplateData());
			$this->em->persist($mailTemplate);
		}

		$this->em->flush();
	}

}
