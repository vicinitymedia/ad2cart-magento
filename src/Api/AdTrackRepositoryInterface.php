<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Api;

interface AdTrackRepositoryInterface
{
    /**
     * Save
     *
     * @param \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface $model
     *
     * @return \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function save(\VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface $model): Data\AdTrackInterface;

    /**
     * Get by ID
     *
     * @param int $id
     *
     * @return \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): Data\AdTrackInterface;

    /**
     * Get list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): \Magento\Framework\Api\SearchResultsInterface;

    /**
     * Delete
     *
     * @param \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface $model
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface $model): bool;

    /**
     * Delete by ID
     *
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $id): bool;
}
