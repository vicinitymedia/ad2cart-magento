<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Api\Data;

interface AdTrackInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const ID = 'track_id';
    public const AD_ID = 'ad_id';
    public const CAMPAIGN_ID = 'campaign_id';
    public const QUOTE_ID = 'quote_id';
    public const ORDER_ID = 'order_id';
    public const CLAIMED = 'claimed';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * Get ad ID
     *
     * @return int
     */
    public function getAdId(): int;

    /**
     * Set ad ID
     *
     * @param int $id
     *
     * @return AdTrackInterface
     */
    public function setAdId(int $id): AdTrackInterface;

    /**
     * Get campaign ID
     *
     * @return int|null
     */
    public function getCampaignId(): ?int;

    /**
     * Set campaign ID
     *
     * @param int|null $id
     *
     * @return AdTrackInterface
     */
    public function setCampaignId(?int $id): AdTrackInterface;

    /**
     * Get quote ID
     *
     * @return int|null
     */
    public function getQuoteId(): ?int;

    /**
     * Set quote ID
     *
     * @param int|null $id
     *
     * @return AdTrackInterface
     */
    public function setQuoteId(?int $id): AdTrackInterface;

    /**
     * Get order ID
     *
     * @return int|null
     */
    public function getOrderId(): ?int;

    /**
     * Set order ID
     *
     * @param int|null $id
     *
     * @return AdTrackInterface
     */
    public function setOrderId(?int $id): AdTrackInterface;

    /**
     * Is claimed
     *
     * @return bool
     */
    public function isClaimed(): bool;

    /**
     * Set claimed
     *
     * @param bool $bool
     *
     * @return AdTrackInterface
     */
    public function setClaimed(bool $bool): AdTrackInterface;

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Set created at
     *
     * @param string $date
     *
     * @return AdTrackInterface
     */
    public function setCreatedAt(string $date): AdTrackInterface;

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Set updated at
     *
     * @param string $date
     *
     * @return AdTrackInterface
     */
    public function setUpdatedAt(string $date): AdTrackInterface;
}
