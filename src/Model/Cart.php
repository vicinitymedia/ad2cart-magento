<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Model;

class Cart
{
    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    private $quoteFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * Construct
     *
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->quoteFactory = $quoteFactory;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Set quote to session
     *
     * @todo this should only be done from a different table result. A discussion is needed here.
     *
     * @param int $quoteId
     *
     * @return void
     */
    public function setQuoteToSession(int $quoteId): void
    {
        $this->checkoutSession->setQuoteId($quoteId);
        //@todo load from quote repository rather for checking purposes
        //$quote = $this->quoteFactory->create()->load($quoteId);

        /*if ($quote->getId()) {
            $this->checkoutSession->setQuoteId($quoteId);
        }*/
    }
}
