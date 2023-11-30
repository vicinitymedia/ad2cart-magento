<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Observer;

class ControllerActionPredispatch implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $response;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $actionFlag;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \VicinityMedia\Ad2Cart\Helper\Data
     */
    protected $helper;

    /**
     * @var \VicinityMedia\Ad2Cart\Model\Auth
     */
    protected $auth;

    /**
     * @var \VicinityMedia\Ad2Cart\Model\Processor
     */
    protected $processor;

    /**
     * @var \VicinityMedia\Ad2Cart\Model\Cart
     */
    protected $cart;

    /**
     * Construct
     *
     * @param \Magento\Framework\App\Response\Http $response
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \VicinityMedia\Ad2Cart\Helper\Data $helper
     * @param \VicinityMedia\Ad2Cart\Model\Auth $auth
     * @param \VicinityMedia\Ad2Cart\Model\Processor $processor
     * @param \VicinityMedia\Ad2Cart\Model\Cart $cart
     */
    public function __construct(
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \VicinityMedia\Ad2Cart\Helper\Data $helper,
        \VicinityMedia\Ad2Cart\Model\Auth $auth,
        \VicinityMedia\Ad2Cart\Model\Processor $processor,
        \VicinityMedia\Ad2Cart\Model\Cart $cart
    ) {
        $this->response = $response;
        $this->actionFlag = $actionFlag;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
        $this->auth = $auth;
        $this->processor = $processor;
        $this->cart = $cart;
    }

    /**
     * @inheritdoc
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ): void {
        try {
            if (!$this->helper->isActive()) {
                return;
            }

            /** @var \Magento\Framework\App\Request\Http $request */
            $request = $observer->getEvent()->getData('request');

            $identifier = trim($request->getPathInfo(), '/');
            $parts = explode('/', $identifier);

            //@todo Below needs to change. We will not do this in a real PROD environment. This is just for demo
            $params = $request->getParams();
            if (!empty($params['quote_id'])) {
                $this->cart->setQuoteToSession((int)$params['quote_id']);
                $this->response->setRedirect($this->helper->getFrontendCartUrl(), 302);
            }

            if ($parts[0] === 'ad2cart'
                && !empty($parts[1])
                && $request->getMethod() === 'POST'
            ) {
                try {
                    // Test route for Ad2Cart to verify connection
                    if ($parts[1] === 'test') {
                        $payload = $this->getPayload($request);
                        $this->auth->validateSignature($request, $payload);

                        $this->setResponse(['message' => 'OK']);
                        return;
                    }

                    $store = $this->storeManager->getStore();
                    $webhookCode = $this->helper->getWebhookCode($store);

                    // Consume webhook data
                    if ($parts[1] === $webhookCode) {
                        $payload = $this->getPayload($request);
                        $this->auth->validateSignature($request, $payload);

                        if (empty($payload['action']) || empty($payload['data'])) {
                            throw new \Magento\Framework\Exception\InputException(
                                __('Invalid payload data')
                            );
                        }

                        if (!method_exists($this->processor, $payload['action'])) {
                            throw new \Magento\Framework\Exception\InputException(
                                __('Invalid payload action.')
                            );
                        }

                        $method = $payload['action'];
                        $data = $payload['data'];

                        // Process the payload
                        $result = $this->processor->$method($data);

                        $this->setResponse($result);

                        // Redirect to checkout cart page on success
                        //$this->response->setRedirect($this->helper->getFrontendCartUrl(), 301);
                        //$this->actionFlag->set('', \Magento\Framework\App\ActionInterface::FLAG_NO_DISPATCH, true);
                        return;
                    }
                } catch (\Magento\Framework\Exception\InputException $e) {
                    $this->helper->logException($e);

                    $this->setResponse(['message' => $e->getMessage()]);
                } catch (\Magento\Framework\Exception\AuthenticationException $e) {
                    $this->helper->logException($e);

                    $this->setResponse(['message' => 'Authentication failure']);
                }
            }
        } catch (\Throwable $e) {
            $this->helper->logException($e);

            $this->setResponse(['message' => 'An unknown error occurred']);
        }

        return;
    }

    /**
     * Get payload
     *
     * @param \Magento\Framework\App\Request\Http $request
     *
     * @return array
     */
    private function getPayload(
        \Magento\Framework\App\Request\Http $request
    ): array {
        $encodedPayload = $request->getContent();
        return $this->helper->jsonDecode($encodedPayload);
    }

    /**
     * Set response
     *
     * @param array $data
     *
     * @return void
     */
    private function setResponse(array $data): void
    {
        $this->actionFlag->set('', \Magento\Framework\App\ActionInterface::FLAG_NO_DISPATCH, true);

        $this->response->setHeader('Content-type', 'application/json', true);
        $this->response->setBody($this->helper->jsonEncode($data));
    }
}
