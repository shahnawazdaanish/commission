<?php

namespace Dove\Commission\Model\Client;

use Dove\Commission\Model\ClientInterface;

class PrivateClient implements ClientInterface
{

    public function getType(): string
    {
        return 'Private';
    }
}