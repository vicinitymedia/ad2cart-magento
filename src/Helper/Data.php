<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Helper;

class Data extends \VicinityMedia\Ad2Cart\Helper\AbstractHelper
{
    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    private $cartHelper;

    /**
     * @var \VicinityMedia\Ad2Cart\Logger
     */
    private $logger;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \VicinityMedia\Ad2Cart\Logger $logger
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \VicinityMedia\Ad2Cart\Logger $logger,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    ) {
        $this->cartHelper = $cartHelper;
        $this->logger = $logger;
        $this->productMetadata = $productMetadata;

        parent::__construct($context, $objectManager, $storeManager, $configWriter, $jsonSerializer, $cacheTypeList);
    }

    /**
     * Is active
     *
     * @param \Magento\Store\Api\Data\StoreInterface|int|string|null $store
     *
     * @return bool
     */
    public function isActive(
        \Magento\Store\Api\Data\StoreInterface|int|string $store = null
    ): bool {
        return $this->isStoreConfigFlag(
            'general/active',
            $store
        );
    }

    /**
     * Is debug active
     *
     * @param \Magento\Store\Api\Data\StoreInterface|int|string|null $store
     *
     * @return bool
     */
    public function isDebugActive(
        \Magento\Store\Api\Data\StoreInterface|int|string $store = null
    ): bool {
        return $this->isStoreConfigFlag(
            'general/debug',
            $store
        );
    }

    /**
     * Get frontend cart URL
     *
     * Fetch the cart URL from configuration, or use the default Magento cart URL.
     *
     * @param \Magento\Store\Api\Data\StoreInterface|int|string|null $store
     *
     * @return string
     */
    public function getFrontendCartUrl(
        \Magento\Store\Api\Data\StoreInterface|int|string $store = null
    ): string {
        $cartUrl = (string)$this->getStoreConfig(
            'general/frontend_cart_url',
            $store
        );

        if (!$cartUrl) {
            $cartUrl = $this->cartHelper->getCartUrl();
        }

        return $cartUrl;
    }

    /**
     * Get webhook code
     *
     * @param \Magento\Store\Api\Data\StoreInterface|int|string|null $store
     *
     * @return string
     */
    public function getWebhookCode(
        \Magento\Store\Api\Data\StoreInterface|int|string $store = null
    ): string {
        return (string)$this->getStoreConfig('general/webhook_code', $store);
    }

    /**
     * Generate webhook code
     *
     * @return string
     */
    public function generateWebhookCode(): string
    {
        return sha1((string)time());
    }

    /**
     * Get webhook URL
     *
     * @param \Magento\Store\Api\Data\StoreInterface|int|string|null $store
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getWebhookUrl(
        \Magento\Store\Api\Data\StoreInterface|int|string $store = null
    ): string {
        return sprintf(
            '%s/%s/%s',
            rtrim($this->storeManager->getStore($store)->getBaseUrl(), '/'),
            'ad2cart',
            $this->getWebhookCode($store)
        );
    }

    /**
     * Get platform
     *
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->productMetadata->getName() . ' ' . $this->productMetadata->getEdition();
    }

    /**
     * Get platform version
     *
     * @return string
     */
    public function getPlatformVersion(): string
    {
        return $this->productMetadata->getVersion();
    }

    /**
     * Get website URL
     *
     * @param \Magento\Store\Api\Data\StoreInterface|int|string|null $store
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getWebsiteUrl(
        \Magento\Store\Api\Data\StoreInterface|int|string $store = null
    ): string {
        return rtrim($this->storeManager->getStore($store)->getBaseUrl(), '/');
    }

    /**
     * Log exception
     *
     * @param \Throwable $exception
     *
     * @return void
     */
    public function logException(\Throwable $exception): void
    {
        $this->logger->logException($exception);
    }

    /**
     * Log debug
     *
     * @param mixed $debug
     * @param \Magento\Store\Api\Data\StoreInterface|int|string|null $store
     *
     * @return void
     */
    public function logDebug(
        $debug,
        \Magento\Store\Api\Data\StoreInterface|int|string $store = null
    ): void {
        if ($this->isDebugActive($store)) {
            $this->logger->logDebug($debug);
        }
    }
}
