<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 20.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Class ApiTokenAuthenticator
 * @package FT\ApiBundle\Security
 * @author Yury Smidovich <dev@stmol.me>
 */
class ApiTokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var ApiTokenUserProvider
     */
    protected $userProvider;

    /**
     * @param ApiTokenUserProvider $userProvider
     */
    function __construct(ApiTokenUserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $tokenString = $token->getCredentials();

        if (null === $tokenString) {
            return $token;
        }

        $username = $this->userProvider->getUsernameByToken($tokenString);

        if (!$username) {
            throw new AuthenticationException(sprintf('Token "%s" does not exist', $tokenString));
        }

        return new PreAuthenticatedToken(
            $this->userProvider->loadUserByUsername($username),
            $tokenString,
            $providerKey,
            ['ROLE_API']
        );
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     * @throws \Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function createToken(Request $request, $providerKey)
    {
        return new PreAuthenticatedToken(
            'anon.',
            $request->query->get('token'),
            $providerKey
        );
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO (Stmol) Xml?
        return new JsonResponse([
            'code'    => 403,
            'message' => $exception->getMessage(),
        ], 403);
    }
}
