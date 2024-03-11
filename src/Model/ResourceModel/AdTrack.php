<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Model\ResourceModel;

class AdTrack extends \Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb
{
    /**
     * Table name
     *
     * The main table name for this resource.
     *
     * @var string
     */
    public const TABLE_NAME = 'vicinity_media_ad2cart_ad_track';

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface::ID);
    }

    /**
     * Set is new object
     *
     * Required if you want to set a selected ID rather than use the PK auto increment.
     *
     * @return void
     */
    public function setIsNewObject(): void
    {
        $this->_isPkAutoIncrement = false;
        $this->_useIsObjectNew = true;
    }

    /**
     * Reset is new object
     *
     * @return void
     */
    public function resetIsNewObject(): void
    {
        $this->_isPkAutoIncrement = true;
        $this->_useIsObjectNew = false;
    }
}
