<?php

declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Test\Unit;

use PHPUnit\Framework\TestCase;

class UpdateOrderStatusTest extends TestCase
{
    /** @var $baseUrl */
    private $baseUrl;

    /** @var $accessToken */
    private $accessToken;

    protected function setUp(): void
    {
        // Set the base URL for your Magento instance and the Access Token
        $this->baseUrl = 'http://mage2.local/rest/V1';

        $tokenurl = $this->baseUrl . '/integration/admin/token';
        $data = [
            'username' => 'admin',
            'password' => 'admin1234'
        ];

        // Set up the headers for the API request
        $headers = [];
        $accessToken = $this->curlRequest(
            $tokenurl,
            $method = 'POST',
            $data = $data,
            $token = null,
            $headers= $headers
        );

        $this->accessToken = $accessToken; // Replace with a valid admin access token
    }

    /**
     * Test Order Status API function
     */
    public function testUpdateOrderStatusByOrderId()
    {
        $url = $this->baseUrl . '/smartworking-customorderprocessing/orderstatusupdate';
        $data = [
            'incrementId' => '000000026',
            'status' => 'processing'
        ];
        $this->accessToken = str_replace('"', '', $this->accessToken);
        $response = $this->curlRequest($url, $method = 'POST', $data = $data, $this->accessToken);
        // @phpcs:disable
        echo $responseData = json_decode($response, true);
        // @phpcs:enable
    }

    /**
     * Helper function to get HTTP response code
     */
    private function getHttpResponseCode($response)
    {
        $headers = explode("\r\n", $response);
        $statusLine = $headers[0];
        preg_match('{HTTP\/\S+\s(\d{3})}', $statusLine, $matches);
        return (int)$matches[1];
    }

    protected function tearDown(): void
    {
        // Any cleanup can be done here after each test
    }

    /**
     * Make a cURL request to Magento 2 REST API.
     *
     * @param string $url        Full URL (e.g., https://your-site.com/rest/V1/products)
     * @param string $method     HTTP Method: GET, POST, PUT, DELETE
     * @param array|null $data   Data to send in request (for POST/PUT)
     * @param string|null $token Bearer token for authentication
     * @param array $headers     Extra headers if needed
     *
     * @return array|string      Decoded response array or raw response on failure
     */
    protected function curlRequest($url, $method = 'GET', $data = null, $token = null, $headers = [])
    {
        $ch = curl_init();

        $defaultHeaders = [
            'Content-Type: application/json',
        ];

        if ($token) {
            $defaultHeaders[] = 'Authorization: Bearer ' . $token;
        }

        $headers = array_merge($defaultHeaders, $headers);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return 'cURL error: ' . $error;
        }

        curl_close($ch);

        return $response;
    }
}
