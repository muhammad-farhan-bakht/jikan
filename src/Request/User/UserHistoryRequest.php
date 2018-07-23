<?php

namespace Jikan\Request\User;

use Jikan\Request\RequestInterface;

/**
 * Class UserHistoryRequest
 *
 * @package Jikan\Request
 */
class UserHistoryRequest implements RequestInterface
{

    /**
     * @var string
     */
    private $username;

    /**
     * @var string|null
     */
    private $type;

    /**
     * UserHistoryRequest constructor.
     *
     * @param string $username
     */
    public function __construct(string $username, $type = null)
    {
        $this->username = $username;


        if (!is_null($type)) {
            if (!\in_array($type, ['anime', 'manga'])) {
                throw new \InvalidArgumentException(sprintf('Type %s is not valid', $type));
            }
        }

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/history/%s/%s', $this->username, $this->type);
    }
}
