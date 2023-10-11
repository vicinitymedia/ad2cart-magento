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
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Magento\Checkout\Helper\Cart $cartHelper
    ) {
        $this->cartHelper = $cartHelper;

        parent::__construct($context, $objectManager, $storeManager, $configWriter, $jsonSerializer);
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
}
