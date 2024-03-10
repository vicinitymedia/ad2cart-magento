<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Observer;

class ConfigChangeObserver implements \Magento\Framework\Event\ObserverInterface
{
    private $defaultError = 'Your API credentials could not be validated.';

    /**
     * @var \VicinityMedia\Ad2Cart\Model\ApiService
     */
    private $apiService;

    /**
     * @var \VicinityMedia\Ad2Cart\Helper\Api
     */
    private $apiHelper;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @param \VicinityMedia\Ad2Cart\Model\ApiService $apiService
     * @param \VicinityMedia\Ad2Cart\Helper\Api $apiHelper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \VicinityMedia\Ad2Cart\Model\ApiService $apiService,
        \VicinityMedia\Ad2Cart\Helper\Api $apiHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->apiService = $apiService;
        $this->apiHelper = $apiHelper;
        $this->messageManager = $messageManager;
    }

    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        if (!$this->apiHelper->isActive()) {
            return;
        }

        try {
            // Configure the project
            $projectData = $this->apiService->configureProject();

            if (empty($projectData['uuid'])) {
                throw new \Magento\Framework\Exception\RuntimeException(
                    __('Unable to configure project.')
                );
            }

            $this->apiHelper->setProjectUuid($projectData['uuid']);
            $this->apiHelper->cleanConfigCache();

            // Validate authentication signature
            $isValid = $this->apiService->validateAuthSignature(['project_uuid' => $projectData['uuid']]);

            if (!$isValid) {
                $this->messageManager->addErrorMessage(__(
                    '%1 Please verify your credentials and try again.', $this->defaultError
                ));
            } else {
                $this->messageManager->addSuccessMessage(
                    __('Your API credentials have been validated successfully.')
                );
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        } catch (\Throwable $e) {
            if ($this->apiHelper->isDebugActive()) {
                $this->messageManager->addErrorMessage(__('%1 %2', $this->defaultError, $e->getMessage()));
            } else {
                $this->messageManager->addErrorMessage(__($this->defaultError));
            }
        }
    }
}
