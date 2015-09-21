<?php

namespace Edu\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EduUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
