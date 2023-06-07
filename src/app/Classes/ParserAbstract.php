<?php

namespace App\Classes;

use GuzzleHttp\Client;

abstract class ParserAbstract
{
    protected $baseUrl;
    const AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36';
    private ?Client $client = null;

    public function __construct()
    {
        $this->client = $this->getClient();
    }

    protected function getClient(): Client
    {
        if (!$this->client) {
            return new Client(['base_uri' => $this->baseUrl]);
        }
        return $this->client;
    }

    public function sendRequest($path)
    {
        return $this->client->request(
            'GET',
            $path,
            [
                'connect_timeout' => 10,
                'timeout' => 30,
                'headers' => [
                    'User-Agent' => self::AGENT,
                ],
                // 'proxy' => [
                //         'http'  => $proxyUrl, // Use this proxy with "http"
                //         'https' => $proxyUrl, // Use this proxy with "https",
                //         'no' => ['.mit.edu', 'foo.com']    // Don't use a proxy with these
                // ]
            ]
        );
    }
}