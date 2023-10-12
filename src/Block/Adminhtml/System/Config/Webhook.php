<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class Webhook extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \VicinityMedia\Ad2Cart\Helper\Data
     */
    private $helper;

    /**
     * Construct
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \VicinityMedia\Ad2Cart\Helper\Data $helper
     * @param array $data
     * @param \Magento\Framework\View\Helper\SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        Context $context,
        \VicinityMedia\Ad2Cart\Helper\Data $helper,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * @inheritdoc
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        try {
            $store = $this->_storeManager->getStore();

            if (!$this->helper->getWebhookCode($store)) {
                $html = __('The webhook URL will be generated upon save.');
            } else {
                $html = $this->helper->getWebhookUrl($store);
            }
        } catch (\Exception $e) {
            $html = __('Error: %1', $e->getMessage());
        }

        $html .= parent::_getElementHtml($element);

        return $html;
    }
}
