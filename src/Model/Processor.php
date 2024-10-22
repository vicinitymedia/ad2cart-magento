<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Model;

class Processor
{
    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \VicinityMedia\Ad2Cart\Helper\Data
     */
    protected $helper;

    /**
     * Construct
     *
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \VicinityMedia\Ad2Cart\Helper\Data $helper
     */
    public function __construct(
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \VicinityMedia\Ad2Cart\Helper\Data $helper
    ) {
        $this->quoteFactory = $quoteFactory;
        $this->quoteRepository = $quoteRepository;
        $this->productRepository = $productRepository;
        $this->helper = $helper;
    }

    /**
     * Add to cart
     *
     * @param array $data
     *
     * @return array
     */
    public function addToCart(array $data = []): array
    {
        $offers = [];

        if (!empty($data['offers'])) {
            $offers = $data['offers'];
        }

        //@todo Need to fetch
        $storeId = 0;

        $quote = $this->quoteFactory->create();
        $quote->setStoreId($storeId);

        $productsAdded = 0;

        foreach ($offers as $offer) {
            $sku = $offer['sku'] ?? '';

            try {
                if (isset($offer['sku'], $offer['qty'])) {
                    $product = $this->productRepository->get((string)$offer['sku']);

                    $quote->addProduct($product, (int)$offer['qty']);
                    $productsAdded++;
                }
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $this->helper->logDebug(__('The product with SKU "%1" does not exist.', $sku));
                // For now we are not handling errors so that we can continue adding products to the cart
                // This might change in the future
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->helper->logDebug($e->getMessage());
                // For now we are not handling errors so that we can continue adding products to the cart
                // This might change in the future
            }
        }

        if ($productsAdded) {
            $this->quoteRepository->save($quote);
        }

        $cartUrl = $this->helper->getFrontendCartUrl();
        $uri = \Laminas\Uri\UriFactory::factory($cartUrl);
        $query = $uri->getQuery();

        $symbol = $query ? '&' : '?';

        $cartUrl .= $symbol . 'quote_id=' . (int)$quote->getId();

        return $this->getResultArray([
            'redirect_url' => $cartUrl,
            'quote_id' => (int)$quote->getId()
        ]);
    }

    /**
     * Get result array
     *
     * @param array $result
     *
     * @return array[]
     */
    protected function getResultArray(array $result = []): array
    {
        return [
            'result' => $result
        ];
    }
}
