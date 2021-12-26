<?php
namespace Dove\Commission\Model\Client;

use Dove\Commission\Model\ClientInterface;

class BusinessClient implements ClientInterface {

    public function getType(): string
    {
        return 'Business';
    }
}