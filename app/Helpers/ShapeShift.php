<?php

namespace App\Helpers;

use GuzzleHttp\Client;
// use GuzzleHttp\RedirectMiddleware;
use Psr\Http\Message\ResponseInterface;

class ShapeShift
{

    const ENDPOINT_BASE = 'https://shapeshift.io';

    const ENDPOINT_GET_RATE   = 'rate/';
    const ENDPOINT_GET_LIMIT  = 'limit/';
    const ENDPOINT_GET_INFO   = 'marketinfo/';
    const ENDPOINT_GET_RECENT = 'recenttx/';
    const ENDPOINT_GET_STATUS = 'txStat/';
    const ENDPOINT_GET_TIME   = 'timeremaining/';
    const ENDPOINT_GET_COINS  = 'getcoins/';

    const ENDPOINT_POST_SHIFT   = 'shift';
    const ENDPOINT_POST_FIXED   = 'sendamount';
    const ENDPOINT_POST_RECEIPT = 'mail';

    private $guzzle;

    private $_from;
    private $_to;

    private $_sender;
    private $_recipient;

    /**
     * @var ResponseInterface
     */
    // private $response;

    public function __construct()
    {
        $this->guzzle = new Client([
            'base_uri' => self::ENDPOINT_BASE,
        ]);
    }

    public function from(string $from)
    {
        $this->_from = strtolower($from);

        return $this;
    }

    public function to(string $to)
    {
        $this->_to = strtolower($to);

        return $this;
    }

    public function getPair()
    {
        return $this->_from && $this->_to ? strtolower("{$this->_from}_{$this->_to}") : '';
    }

    public function sender(string $sender)
    {
        $this->_sender = $sender;

        return $this;
    }

    public function recipient(string $recipient)
    {
        $this->_recipient = $recipient;

        return $this;
    }

    public function get(string $endpoint)
    {
        $response = $this->guzzle->get($endpoint);

        return json_decode($response->getBody()->getContents());
    }

    public function post(string $endpoint, array $data = [])
    {
        $response = $this->guzzle->post($endpoint, [
            'form_params' => $data,
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function getRate()
    {
        return $this->get(self::ENDPOINT_GET_RATE . $this->getPair());
    }

    public function getLimit()
    {
        if ($pair = $this->getPair() === '') {
            throw new \Exception('This endpoint requires a currency pair.');
        }

        return $this->get(self::ENDPOINT_GET_LIMIT . $this->getPair());
    }

    public function getInfo()
    {
        return collect($this->get(self::ENDPOINT_GET_INFO . $this->getPair()));
    }

    public function getTransactions(int $limit = 5)
    {
        $limit = min(max($limit, 1), 50);

        return $this->get(self::ENDPOINT_GET_RECENT . $limit);
    }

    public function getStatus(string $address)
    {
        return $this->get(self::ENDPOINT_GET_STATUS . $address);
    }

    public function getTime(string $address)
    {
        return $this->get(self::ENDPOINT_GET_TIME . $address);
    }

    public function getSupportedCoins()
    {
        return $this->get(self::ENDPOINT_GET_COINS);
    }

    public function shift(string $withdrawl_address, string $return_address)
    {
        if ($pair = $this->getPair() === '') {
            throw new \Exception('This endpoint requires a currency pair.');
        }

        return $this->post(self::ENDPOINT_POST_SHIFT, [
            'withdrawl'     => $withdrawl_address,
            'pair'          => $pair,
            'returnAddress' => $return_address,
        ]);
    }

}