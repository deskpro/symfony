<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Http\Session;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface;

/**
 * The default session strategy implementation.
 *
 * Supports the following strategies:
 * NONE: the session is not changed
 * MIGRATE: the session id is updated, attributes are kept
 * INVALIDATE: the session id is updated, attributes are lost
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class SessionAuthenticationStrategy implements SessionAuthenticationStrategyInterface
{
    const NONE = 'none';
    const MIGRATE = 'migrate';
    const INVALIDATE = 'invalidate';

    private $strategy;
    private $csrfTokenStorage = null;

    public function __construct($strategy, ClearableTokenStorageInterface $csrfTokenStorage = null)
    {
        $this->strategy = $strategy;

        if (self::MIGRATE === $strategy) {
            $this->csrfTokenStorage = $csrfTokenStorage;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthentication(Request $request, TokenInterface $token)
    {
        switch ($this->strategy) {
            case self::NONE:
                return;

            case self::MIGRATE:
                // Destroying the old session is broken in php 5.4.0 - 5.4.10
                // See https://bugs.php.net/63379
                $destroy = \PHP_VERSION_ID < 50400 || \PHP_VERSION_ID >= 50411;
                $request->getSession()->migrate($destroy);

                if ($this->csrfTokenStorage) {
                    $this->csrfTokenStorage->clear();
                }

                return;

            case self::INVALIDATE:
                $request->getSession()->invalidate();

                return;

            default:
                throw new \RuntimeException(sprintf('Invalid session authentication strategy "%s"', $this->strategy));
        }
    }
}
