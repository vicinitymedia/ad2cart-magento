<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart;

class Logger extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Construct
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->_logger = $logger;
        $this->updateHandlers();
    }

    /**
     * Log exception
     *
     * @param \Throwable $exception
     *
     * @return void
     */
    public function logException(\Throwable $exception): void
    {
        $this->_logger->critical('Error message', ['exception' => $exception]);
    }

    /**
     * Log debug
     *
     * @param mixed $debug
     *
     * @return void
     */
    public function logDebug($debug): void
    {
        $this->_logger->debug('Debug', ['info' => $debug]);
    }

    /**
     * Update handlers
     *
     * Removes default Magento debug handler to prevent duplicate logging.
     *
     * @return void
     */
    private function updateHandlers(): void
    {
        if ($this->_logger instanceof \Magento\Framework\Logger\Monolog) {
            $handlers = $this->_logger->getHandlers();

            foreach ($handlers as $key => $handler) {
                if ($handler instanceof \Magento\Developer\Model\Logger\Handler\Debug) {
                    unset($handlers[$key]);
                }
            }

            $this->_logger->setHandlers($handlers);
        }
    }
}
