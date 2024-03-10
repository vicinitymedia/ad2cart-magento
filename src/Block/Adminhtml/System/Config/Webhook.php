<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Block\Adminhtml\System\Config;

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
        \Magento\Backend\Block\Template\Context $context,
        \VicinityMedia\Ad2Cart\Helper\Data $helper,
        array $data = [],
        ?\Magento\Framework\View\Helper\SecureHtmlRenderer $secureRenderer = null
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element): string
    {
        try {
            $store = $this->_storeManager->getStore();

            if (!$this->helper->getWebhookCode($store)) {
                $html = '<span style="color: #eb5202">'
                    . __('The webhook URL will be generated upon save.') . '</span>';
            } else {
                $html = '<span style="color: #79a22e">' . $this->helper->getWebhookUrl($store) . '</span>';
            }
        } catch (\Exception $e) {
            $html = '<span style="color: #e22626">' . __('Error: %1', $e->getMessage()) . '</span>';
        }

        $html .= parent::_getElementHtml($element);

        return $html;
    }
}
