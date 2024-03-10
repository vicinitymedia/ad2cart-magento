<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Block\Adminhtml\System\Config;

class ModuleInfo extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @var string
     */
    protected $userGuideConfigPath = 'vicinity_media_ad2cart/user_guide';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\View\Helper\Js $jsHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);

        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element): array|string|null
    {
        $html = $this->_getHeaderHtml($element);

        if ($this->userGuideConfigPath) {
            $html .= $this->getUserGuideContainer();
        }

        $html .= $this->_getFooterHtml($element);

        return preg_replace('(onclick=\"Fieldset.toggleCollapse.*?\")', '', $html);
    }

    /**
     * Get user guide container
     *
     * @return string
     */
    private function getUserGuideContainer(): string
    {
        $value = $this->scopeConfig->getValue(
            $this->userGuideConfigPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($this->userGuideConfigPath && $value) {
            return '<div class="vicinity-media-user-guide"><div class="message success">'
                . __(
                    'Need help with the settings?'
                    . '  Please consult the <a target="_blank" href="%1">user guide</a>.',
                    $value
                )
                . '</div></div>';
        }

        return '';
    }
}
