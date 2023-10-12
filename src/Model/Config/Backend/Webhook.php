<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Model\Config\Backend;

class Webhook extends \Magento\Framework\App\Config\Value
{
    /**
     * @var \VicinityMedia\Ad2Cart\Helper\Data
     */
    private $helper;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \VicinityMedia\Ad2Cart\Helper\Data $helper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \VicinityMedia\Ad2Cart\Helper\Data $helper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        if (!$this->getValue()) {
            $this->setValue($this->helper->generateWebhookCode());
        }

        return parent::beforeSave();
    }
}
