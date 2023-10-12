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
     * @var \VicinityMedia\Ad2Cart\Helper\Data
     */
    protected $helper;

    /**
     * @var \VicinityMedia\Ad2Cart\Model\Auth
     */
    protected $auth;

    /**
     * Construct
     *
     * @param \Magento\Framework\App\Response\Http $response
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \VicinityMedia\Ad2Cart\Helper\Data $helper
     * @param \VicinityMedia\Ad2Cart\Model\Auth $auth
     */
    public function __construct(
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \VicinityMedia\Ad2Cart\Helper\Data $helper,
        \VicinityMedia\Ad2Cart\Model\Auth $auth
    ) {
        $this->response = $response;
        $this->actionFlag = $actionFlag;
        $this->helper = $helper;
        $this->auth = $auth;
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

                    //@todo Validate request signature
                    //@todo Let Magento 404 on invalid signature

                    //@todo Process request (consume data)

                    // Redirect to checkout cart page on success
                    $this->response->setRedirect($this->helper->getFrontendCartUrl(), 301);
                    $this->actionFlag->set('', \Magento\Framework\App\ActionInterface::FLAG_NO_DISPATCH, true);
                    return;
                } catch (\Magento\Framework\Exception\AuthenticationException $ae) {
                    $this->helper->logException($ae);

                    $this->setResponse(['message' => 'Authentication failure']);
                }
            }
        } catch (\Throwable $e) {
            $this->helper->logException($e);
        }
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
