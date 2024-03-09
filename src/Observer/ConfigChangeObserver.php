<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Observer;

class ConfigChangeObserver implements \Magento\Framework\Event\ObserverInterface
{
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
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $projectData = $this->apiService->configureProject();

            if (!empty($projectData['uuid'])) {
                $this->apiHelper->setProjectUuid($projectData['uuid']);
                $this->apiHelper->cleanConfigCache();
            }
        } catch (\Throwable $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }
}
