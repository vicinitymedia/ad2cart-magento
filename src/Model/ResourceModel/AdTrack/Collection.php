<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Model\ResourceModel\AdTrack;

/**
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @inheritdoc
     * @var string
     */
    protected $_idFieldName = \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface::ID;

    /**
     * @inheritdoc
     * @var string
     */
    protected $_eventPrefix = 'vicinity_media_ad2cart_ad_track_collection';

    /**
     * @inheritdoc
     * @var string
     */
    protected $_eventObject = 'ad_track_collection';

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct(): void
    {
        $this->_init(
            \VicinityMedia\Ad2Cart\Model\AdTrack::class,
            \VicinityMedia\Ad2Cart\Model\ResourceModel\AdTrack::class
        );
        parent::_construct();
    }
}
