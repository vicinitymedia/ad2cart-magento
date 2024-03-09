<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Model;

class ApiService
{
    /**
     * @var \VicinityMedia\Ad2Cart\Helper\Api
     */
    private $apiHelper;

    /**
     * Construct
     *
     * @param \VicinityMedia\Ad2Cart\Helper\Api $apiHelper
     */
    public function __construct(
        \VicinityMedia\Ad2Cart\Helper\Api $apiHelper
    ) {
        $this->apiHelper = $apiHelper;
    }

    /**
     * Configure project
     *
     * @return array
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    public function configureProject(): array
    {
        $store = $this->apiHelper->getStore();
        $apiUrl = $this->apiHelper->getUrl();
        $apiKey = $this->apiHelper->getKey();

        $projectData = [
            'platform'          => $this->apiHelper->getPlatform(),
            'platform_version'  => $this->apiHelper->getPlatformVersion(),
            'webhook_url'       => $this->apiHelper->getWebhookUrl($store),
            'website_url'       => $this->apiHelper->getWebsiteUrl($store),
            'frontend_url'      => $this->apiHelper->getFrontendCartUrl($store)
        ];

        if (!$apiUrl) {
            throw new \Magento\Framework\Exception\InputException(
                __('Unable to read API URL')
            );
        }

        if (!$apiKey) {
            throw new \Magento\Framework\Exception\InputException(
                __('Unable to read API Key')
            );
        }

        $client = new \GuzzleHttp\Client([
            'base_uri' => $apiUrl,
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => $apiKey
            ]
        ]);

        $mutation = '
            mutation Mutation($project: UpdateProjectInput!) {
                configureProject(project: $project) {
                    uuid
                }
            }';

        $variables = [
            'project' => $projectData
        ];

        $response = $client->post('', [
            'json' => [
                'query' => $mutation,
                'variables' => $variables,
            ]
        ]);

        $body = $response->getBody();
        $contents = $body->getContents();
        $data = json_decode($contents, true);

        // Handle errors, assume first error is the most important
        if (!empty($data['errors'])) {
            $message = $data['errors'][0]['message'] ?? 'An unknown error occurred';
            throw new \Magento\Framework\Exception\RuntimeException(__($message));
        }

        return $data['data']['configureProject'] ?? [];
    }
}
