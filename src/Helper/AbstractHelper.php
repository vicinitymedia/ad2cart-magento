<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Helper;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AbstractHelper extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const MODULE_CONFIG_PATH = 'vicinity_media_ad2cart';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    private $configWriter;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $jsonSerializer;

    /**
     * @var \Magento\Backend\App\Config
     */
    protected $backendConfig;

    /**
     * @var array
     */
    protected array $isArea = [];

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
    ) {
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->configWriter = $configWriter;
        $this->jsonSerializer = $jsonSerializer;

        parent::__construct($context);
    }

    /**
     * Get store config
     *
     * @param string $field
     * @param \Magento\Store\Api\Data\StoreInterface|int|string|null $scopeCode
     * @param string $scopeType
     *
     * @return mixed
     */
    public function getStoreConfig(
        string $field = '',
        \Magento\Store\Api\Data\StoreInterface|int|string $scopeCode = null,
        string $scopeType = \Magento\Store\Model\ScopeInterface::SCOPE_STORE
    ) {
        $field = static::MODULE_CONFIG_PATH . '/' .  $field;

        if ($scopeCode === null && !$this->isArea()) {
            if (!$this->backendConfig) {
                $this->backendConfig = $this->objectManager
                    ->get(\Magento\Backend\App\ConfigInterface::class);
            }

            return $this->backendConfig->getValue($field);
        }

        return $this->scopeConfig->getValue($field, $scopeType, $scopeCode);
    }

    /**
     * Get store config
     *
     * @param string $field
     * @param string $value
     * @param string $scope
     * @param int $scopeId
     *
     * @return \VicinityMedia\Ad2Cart\Helper\AbstractHelper
     */
    public function setStoreConfig(
        string $field = '',
        string $value = '',
        string $scope = \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        int $scopeId = 0
    ): static {
        $field = static::MODULE_CONFIG_PATH . '/' .  $field;

        $this->configWriter->save($field, $value, $scope, $scopeId);

        return $this;
    }

    /**
     * Get store config flag
     *
     * @param string $field
     * @param \Magento\Store\Api\Data\StoreInterface|int|string|null $scopeCode
     * @param string $scopeType
     *
     * @return bool
     */
    public function isStoreConfigFlag(
        string $field = '',
        \Magento\Store\Api\Data\StoreInterface|int|string $scopeCode = null,
        string $scopeType = \Magento\Store\Model\ScopeInterface::SCOPE_STORE
    ): bool {
        $field = static::MODULE_CONFIG_PATH . '/' .  $field;

        if ($scopeCode === null && !$this->isArea()) {
            if (!$this->backendConfig) {
                $this->backendConfig = $this->objectManager
                    ->get(\Magento\Backend\App\ConfigInterface::class);
            }

            return $this->backendConfig->isSetFlag($field);
        }

        return $this->scopeConfig->isSetFlag($field, $scopeType, $scopeCode);
    }

    /**
     * JSON encode
     *
     * @param mixed $value
     *
     * @return string
     */
    public function jsonEncode(mixed $value): string
    {
        try {
            $encodeValue = $this->jsonSerializer->serialize($value);
        } catch (\Exception) {
            $encodeValue = '{}';
        }

        return $encodeValue;
    }

    /**
     * JSON decode
     *
     * @param string $value
     *
     * @return array|mixed
     */
    public function jsonDecode(string $value)
    {
        try {
            $decodeValue = $this->jsonSerializer->unserialize($value);
        } catch (\Exception) {
            $decodeValue = [];
        }

        return $decodeValue;
    }

    /**
     * Is area
     *
     * @param string $area
     *
     * @return bool
     */
    public function isArea(string $area = \Magento\Framework\App\Area::AREA_FRONTEND): bool
    {
        if (!isset($this->isArea[$area])) {
            /** @var \Magento\Framework\App\State $state */
            $state = $this->objectManager->get(\Magento\Framework\App\State::class);

            try {
                $this->isArea[$area] = ($state->getAreaCode() === $area);
            } catch (\Exception) {
                $this->isArea[$area] = false;
            }
        }

        return $this->isArea[$area];
    }

    /**
     * Is admin store
     *
     * @return bool
     */
    public function isAdminStore(): bool
    {
        return $this->isArea(\Magento\Framework\App\Area::AREA_ADMINHTML);
    }

    /**
     * Get store
     *
     * @param int|null $storeId
     *
     * @return \Magento\Store\Api\Data\StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStore(int $storeId = null): \Magento\Store\Api\Data\StoreInterface
    {
        return $this->storeManager->getStore($storeId);
    }
}
