<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Helper;

class Api extends \VicinityMedia\Ad2Cart\Helper\Data
{
    /**
     * Get url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return (string)$this->getStoreConfig('api/url');
    }

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

    /**
     * Get project uuid
     *
     * @return string
     */
    public function getProjectUuid(): string
    {
        return (string)$this->getStoreConfig('api/project_uuid');
    }

    /**
     * Set project uuid
     *
     * @param string $uuid
     *
     * @return \VicinityMedia\Ad2Cart\Helper\Api
     */
    public function setProjectUuid(
        string $uuid
    ): \VicinityMedia\Ad2Cart\Helper\Api {
        return $this->setStoreConfig('api/project_uuid', $uuid);
    }
}
