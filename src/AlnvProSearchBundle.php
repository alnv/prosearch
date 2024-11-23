<?php

namespace Alnv\ProSearchBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AlnvProSearchBundle extends Bundle
{

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}