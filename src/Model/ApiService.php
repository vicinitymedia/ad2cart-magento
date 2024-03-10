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
     * @var \VicinityMedia\Ad2Cart\Model\Auth
     */
    private $auth;

    /**
     * Construct
     *
     * @param \VicinityMedia\Ad2Cart\Helper\Api $apiHelper,
     * @param \VicinityMedia\Ad2Cart\Model\Auth $auth
     */
    public function __construct(
        \VicinityMedia\Ad2Cart\Helper\Api $apiHelper,
        \VicinityMedia\Ad2Cart\Model\Auth $auth
    ) {
        $this->apiHelper = $apiHelper;
        $this->auth = $auth;
    }

    /**
     * Validate auth signature
     *
     * @param array $payload
     *
     * @return bool
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function validateAuthSignature(array $payload): bool
    {
        $defaultError = 'Cannot validate authentication signature.';
        $apiUrl = $this->apiHelper->getUrl();
        $apiSecret = $this->apiHelper->getSecret();
        $apiKey = $this->apiHelper->getKey();

        if (!$apiUrl) {
            throw new \Magento\Framework\Exception\InputException(
                __('%1 Unable to read API URL.', $defaultError)
            );
        }

        if (!$apiKey) {
            throw new \Magento\Framework\Exception\InputException(
                __('%1 Unable to read API Key.', $defaultError)
            );
        }

        if (!$apiSecret) {
            throw new \Magento\Framework\Exception\InputException(
                __('%1 Unable to read API Secret.', $defaultError)
            );
        }

        $timestamp = (string)time();
        $signature = $this->auth->prepareSignature($payload, $apiKey, $apiSecret, $timestamp);

        $client = new \GuzzleHttp\Client([
            'base_uri' => $apiUrl,
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => $apiKey
            ]
        ]);

        $mutation = 'mutation Mutation($auth: ValidateAuthSignatureInput!) {
            validateAuthSignature(auths: $auth) {
                isValid
            }
        }';

        $variables = [
            'auth' => [
                'signature' => $signature,
                'timestamp' => $timestamp
            ]
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
            $error = $data['errors'][0]['message'] ?? 'An unknown error occurred.';
            $message = $defaultError . ' ' . $error;

            throw new \Magento\Framework\Exception\RuntimeException(__($message));
        }

        return (bool)$data['data']['validateAuthSignature']['isValid'] ?? false;
    }

    /**
     * Configure project
     *
     * @return array
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\RuntimeException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function configureProject(): array
    {
        $store = $this->apiHelper->getStore();
        $apiUrl = $this->apiHelper->getUrl();
        $apiKey = $this->apiHelper->getKey();

        if (!$apiUrl) {
            throw new \Magento\Framework\Exception\InputException(
                __('Cannot configure your project. Unable to read API URL.')
            );
        }

        if (!$apiKey) {
            throw new \Magento\Framework\Exception\InputException(
                __('Cannot configure your project. Unable to read API Key.')
            );
        }

        $projectData = [
            'platform'          => $this->apiHelper->getPlatform(),
            'platform_version'  => $this->apiHelper->getPlatformVersion(),
            'webhook_url'       => $this->apiHelper->getWebhookUrl($store),
            'website_url'       => $this->apiHelper->getWebsiteUrl($store),
            'frontend_url'      => $this->apiHelper->getFrontendCartUrl($store)
        ];

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
