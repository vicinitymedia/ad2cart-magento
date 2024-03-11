<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Api\Data;

interface AdTrackSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @inheritDoc
     * @return \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface[]|\Magento\Framework\DataObject[]
     */
    public function getItems(): array;

    /**
     * @inheritDoc
     * @param \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface[]|\Magento\Framework\DataObject[] $items
     */
    public function setItems(array $items = null): \Magento\Framework\Api\SearchResultsInterface;
}
