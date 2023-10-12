<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Helper;

class Api extends \VicinityMedia\Ad2Cart\Helper\Data
{
    /**
     * Get key
     *
     * @return string
     */
    public function getKey(): string
    {
        return (string)$this->getStoreConfig('api/key');
    }

    /**
     * Get secret
     *
     * @return string
     */
    public function getSecret(): string
    {
        return (string)$this->getStoreConfig('api/secret');
    }
}
