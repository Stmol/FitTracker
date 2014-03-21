<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 21.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends Controller
{
    /**
     * @Rest\View(statusCode="202")
     *
     * @param  Request                                                            $request
     * @throws \Symfony\Component\Security\Core\Exception\BadCredentialsException
     * @throws \Exception
     * @return string
     */
    public function loginAction(Request $request)
    {
        $username = $request->request->get('username', null);
        $password = $request->request->get('password', null);

        if (!$username or !$password) {
            throw new BadCredentialsException('One or more authentication parameters were not obtained');
        }

        /** @var \FT\UserBundle\Entity\User $user */
        $user = $this->get('ft_user.manager.user')
            ->getOneByCredentials($username, $password);

        if (!$user) {
            throw new BadCredentialsException('Credentials are incorrect');
        }

        /** @var \Doctrine\DBAL\Connection $conn */
        $conn = $this->getDoctrine()->getConnection();
        $apiTokenManager = $this->getEntityManager();

        $conn->beginTransaction();

        try {
            $tokens = $apiTokenManager->getAllByUser($user);

            // Remove old tokens
            if (is_array($tokens) and !empty($tokens)) {
                foreach ($tokens as $token) {
                    $apiTokenManager->delete($token);
                }
            }

            /** @var \FT\ApiBundle\Entity\ApiToken $apiToken */
            $apiToken = $apiTokenManager->create($user);
            $apiTokenManager->save($apiToken);

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();
            $conn->close();
            throw $e;
        }

        return ['token' => $apiToken->getToken()];
    }

    /**
     * @Rest\View(statusCode="204")
     *
     * @Security("has_role('ROLE_API')")
     *
     * @param  Request                                                            $request
     * @throws \Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function logoutAction(Request $request)
    {
        $apiTokenManager = $this->getEntityManager();
        /** @var \FT\ApiBundle\Entity\ApiToken $apiToken */
        $apiToken = $apiTokenManager->getOneByToken($request->query->get('token'));

        if (!$apiToken or $apiToken->getUser() !== $this->getUser()) {
            throw new BadCredentialsException('Credentials are incorrect');
        }

        $apiTokenManager->delete($apiToken);

        return;
    }

    /**
     * @return \FT\ApiBundle\Manager\ApiTokenManager
     */
    private function getEntityManager()
    {
        return $this->get('ft_api.manager.api_token');
    }
}
