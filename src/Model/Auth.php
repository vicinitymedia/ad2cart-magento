<?php
declare(strict_types=1);

namespace VicinityMedia\Ad2Cart\Model;

class Auth
{
    private const SIGNATURE_VALIDITY = 60;
    private const HASH_ALGO = 'sha256';

    /**
     * @var \VicinityMedia\Ad2Cart\Helper\Data
     */
    private $helper;

    /**
     * @var \VicinityMedia\Ad2Cart\Helper\Api
     */
    private $apiHelper;

    /**
     * Construct
     *
     * @param \VicinityMedia\Ad2Cart\Helper\Data $helper
     * @param \VicinityMedia\Ad2Cart\Helper\Api $apiHelper
     */
    public function __construct(
        \VicinityMedia\Ad2Cart\Helper\Data $helper,
        \VicinityMedia\Ad2Cart\Helper\Api $apiHelper
    ) {
        $this->helper = $helper;
        $this->apiHelper = $apiHelper;
    }

    /**
     * Validate signature
     *
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $payload
     *
     * @throws \Magento\Framework\Exception\AuthenticationException
     */
    public function validateSignature(
        \Magento\Framework\App\Request\Http $request,
        array $payload
    ): void {
        $requestKey = (string)$request->getHeader('X-Ad2Cart-Key');
        $requestSignature = (string)$request->getHeader('X-Ad2Cart-Signature');
        $requestTimestamp = $request->getHeader('X-Ad2Cart-Timestamp');

        $this->helper->logDebug(['headers' => [
            'key'       => $requestKey,
            'signature' => $requestSignature,
            'timestamp' => $requestTimestamp
        ]]);

        // NB: These values should never be logged!
        $key = $this->apiHelper->getKey();
        $secret = $this->apiHelper->getSecret();

        // Validate key
        if (!$requestKey
            || $requestKey !== $key
        ) {
            throw new \Magento\Framework\Exception\AuthenticationException(
                __('Authentication failed: invalid key.')
            );
        }

        // Validate timestamp
        if (!$requestTimestamp
            || time() - $requestTimestamp > self::SIGNATURE_VALIDITY
        ) {
            throw new \Magento\Framework\Exception\AuthenticationException(
                __('Authentication failed: invalid timestamp.')
            );
        }

        $this->helper->logDebug(['payload' => $payload]);

        // Create digest for signing string
        $digest = base64_encode(hash_hmac(self::HASH_ALGO, $this->helper->jsonEncode($payload), $secret));

        $stringToSign = implode('\n', [
            'key:' . $key,
            'timestamp:' . $requestTimestamp,
            'digest:' . $digest
        ]);

        // Compute signature
        $computedSignature = hash_hmac(self::HASH_ALGO, $stringToSign, $secret);

        $this->helper->logDebug([
            'signatures' => [
                'computed' => $computedSignature,
                'request'  => $requestSignature
            ]
        ]);

        // Validate signature
        $valid = hash_equals($computedSignature, $requestSignature);

        if (!$valid) {
            throw new \Magento\Framework\Exception\AuthenticationException(
                __('Authentication failed: invalid signature.')
            );
        }

        $this->helper->logDebug('Signature validation successful.');
    }
}