<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Core\Exception;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * AccountStatusException is the base class for authentication exceptions
 * caused by the user account status.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Alexander <iam.asm89@gmail.com>
 */
abstract class AccountStatusException extends AuthenticationException
{
    private $user;

    /**
     * Get the user.
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function __serialize()
    {
        return [
            $this->user,
            parent::__serialize(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function __unserialize($str)
    {
        list($this->user, $parentData) = $str;

        parent::__unserialize($parentData);
    }
}
