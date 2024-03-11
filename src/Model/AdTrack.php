<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class AdTrack extends AbstractExtensibleModel implements AdTrackInterface, IdentityInterface
{
    /**
     * Cache tag
     *
     * The main cache tag for this model.
     *
     * @var string
     */
    public const CACHE_TAG = 'vicinity_media_ad2cart_ad_track';

    /**
     * @inheritdoc
     * @var string|array|bool
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @inheritdoc
     * @var string
     */
    protected $_eventPrefix = self::CACHE_TAG;

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct(): void
    {
        $this->_init(\VicinityMedia\Ad2Cart\Model\ResourceModel\AdTrack::class);
        parent::_construct();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritdoc
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getAdId(): int
    {
        return (int)$this->getData(self::AD_ID);
    }

    /**
     * @inheritdoc
     */
    public function setAdId(int $id): AdTrackInterface
    {
        return $this->setData(self::AD_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getCampaignId(): ?int
    {
        return $this->getData(self::CAMPAIGN_ID) ? (int)$this->getData(self::CAMPAIGN_ID) : null;
    }

    /**
     * @inheritdoc
     */
    public function setCampaignId(?int $id): AdTrackInterface
    {
        return $this->setData(self::CAMPAIGN_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getQuoteId(): ?int
    {
        return $this->getData(self::QUOTE_ID) ? (int)$this->getData(self::QUOTE_ID) : null;
    }

    /**
     * @inheritdoc
     */
    public function setQuoteId(?int $id): AdTrackInterface
    {
        return $this->setData(self::QUOTE_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function getOrderId(): ?int
    {
        return $this->getData(self::ORDER_ID) ? (int)$this->getData(self::ORDER_ID) : null;
    }

    /**
     * @inheritdoc
     */
    public function setOrderId(?int $id): AdTrackInterface
    {
        return $this->setData(self::ORDER_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function isClaimed(): bool
    {
        return (bool)$this->getData(self::CLAIMED);
    }

    /**
     * @inheritdoc
     */
    public function setClaimed(bool $bool): AdTrackInterface
    {
        return $this->setData(self::CLAIMED, $bool);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt(string $date): AdTrackInterface
    {
        return $this->setData(self::CREATED_AT, $date);
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setUpdatedAt(string $date): AdTrackInterface
    {
        return $this->setData(self::UPDATED_AT, $date);
    }
}
