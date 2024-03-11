<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Model;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class AdTrackRepository implements \VicinityMedia\Ad2Cart\Api\AdTrackRepositoryInterface
{
    /**
     * @var \VicinityMedia\Ad2Cart\Model\ResourceModel\AdTrack
     */
    private $modelResource;

    /**
     * @var \VicinityMedia\Ad2Cart\Model\AdTrackFactory
     */
    private $modelFactory;

    /**
     * @var \VicinityMedia\Ad2Cart\Model\ResourceModel\AdTrack\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\Api\Search\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \VicinityMedia\Ad2Cart\Api\Data\AdTrackSearchResultInterfaceFactory
     */
    private $searchResultInterfaceFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\EntityManager\HydratorInterface
     */
    private $hydrator;

    /**
     * Construct
     *
     * @param \VicinityMedia\Ad2Cart\Model\ResourceModel\AdTrack $modelResource
     * @param \VicinityMedia\Ad2Cart\Model\AdTrackFactory $modelFactory
     * @param \VicinityMedia\Ad2Cart\Model\ResourceModel\AdTrack\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \VicinityMedia\Ad2Cart\Api\Data\AdTrackSearchResultInterfaceFactory $searchResultInterfaceFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\EntityManager\HydratorInterface|null $hydrator
     */
    public function __construct(
        \VicinityMedia\Ad2Cart\Model\ResourceModel\AdTrack $modelResource,
        \VicinityMedia\Ad2Cart\Model\AdTrackFactory $modelFactory,
        \VicinityMedia\Ad2Cart\Model\ResourceModel\AdTrack\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
        \VicinityMedia\Ad2Cart\Api\Data\AdTrackSearchResultInterfaceFactory $searchResultInterfaceFactory,
        \Psr\Log\LoggerInterface $logger,
        ?\Magento\Framework\EntityManager\HydratorInterface $hydrator = null
    ) {
        $this->modelResource = $modelResource;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultInterfaceFactory = $searchResultInterfaceFactory;
        $this->logger = $logger;
        $this->hydrator = $hydrator ?? \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\EntityManager\HydratorInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function save(
        \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface $model
    ): \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface {
        $hydrated = null;

        if ($model->getId()
            && !$model->getOrigData()
            && !$model->isObjectNew()
        ) {
            $hydrated = $this->hydrator->hydrate(
                $this->getById((int)$model->getId()),
                $this->hydrator->extract($model)
            );
        }

        try {
            if ($hydrated instanceof \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface) {
                $model = $hydrated;
                unset($hydrated);
            }

            $this->modelResource->save($model);
        } catch (\Throwable $exception) {
            $this->logger->critical($exception);

            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('There was an error saving the model: %1', $exception->getMessage()),
                $exception
            );
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): \VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface
    {
        $model = $this->modelFactory->create();

        $this->modelResource->load($model, $id);

        if (!$model->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The ad does not exist.', $id)
            );
        }

        foreach ($model->getData() as $key => $value) {
            if ($value) {
                $model->setDataUsingMethod($key, $value);
            }
        }

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): \Magento\Framework\Api\SearchResultsInterface {
        $collection = $this->collectionFactory->create();

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $searchResult = $this->searchResultInterfaceFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function delete(\VicinityMedia\Ad2Cart\Api\Data\AdTrackInterface $model): bool
    {
        try {
            $this->modelResource->delete($model);
        } catch (\Exception $exception) {
            $this->logger->critical($exception);

            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __('There was an error deleting the model: %1', $exception->getMessage()),
                $exception
            );
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * Set is new object
     *
     * Required if you want to set a selected ID rather than use the PK auto increment.
     *
     * @return \VicinityMedia\Ad2Cart\Model\AdTrackRepository
     */
    public function setIsNewObject(): static
    {
        $this->modelResource->setIsNewObject();

        return $this;
    }

    /**
     * Reset is new object
     *
     * @return \VicinityMedia\Ad2Cart\Model\AdTrackRepository
     */
    public function resetIsNewObject(): static
    {
        $this->modelResource->resetIsNewObject();

        return $this;
    }
}
