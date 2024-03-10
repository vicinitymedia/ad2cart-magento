<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Block\Adminhtml\System\Config;

class ProjectUuid extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \VicinityMedia\Ad2Cart\Helper\Api
     */
    private $apiHelper;

    /**
     * Construct
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \VicinityMedia\Ad2Cart\Helper\Api $apiHelper
     * @param array $data
     * @param \Magento\Framework\View\Helper\SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \VicinityMedia\Ad2Cart\Helper\Api $apiHelper,
        array $data = [],
        ?\Magento\Framework\View\Helper\SecureHtmlRenderer $secureRenderer = null
    ) {
        $this->apiHelper = $apiHelper;
        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element): string
    {
        try {
            if (!$this->apiHelper->getProjectUuid()) {
                $html = '<span style="color: #eb5202">'
                    . __('Your Site ID will be fetched upon save.') . '</span>';
            } else {
                $html = '<span style="color: #79a22e">' . $this->apiHelper->getProjectUuid() . '</span>';
            }
        } catch (\Exception $e) {
            $html = '<span style="color: #e22626">' . __('Error: %1', $e->getMessage()) . '</span>';
        }

        $html .= parent::_getElementHtml($element);

        return $html;
    }
}
