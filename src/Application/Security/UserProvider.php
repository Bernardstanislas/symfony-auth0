<?php

namespace App\Application\Security;

use App\Domain\User\Entity\AnonymousUser;
use App\Domain\User\Entity\User;
use Auth0\JWTAuthBundle\Security\Auth0Service;
use Auth0\JWTAuthBundle\Security\Core\JWTUserProviderInterface;
use RuntimeException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider implements JWTUserProviderInterface
{
    protected $auth0Service;

    public function __construct(Auth0Service $auth0Service) {
        $this->auth0Service = $auth0Service;
    }

    public function loadUserByJWT($jwt) {
        // you can fetch the user profile from the auth0 api
        // or from your database
        // $data = $this->auth0Service->getUserProfileByA0UID($jwt->token,$jwt->sub);
        // in this case, we will just use what we got from
        // the token because we dont need any info from the profile
        $data = [ 'sub' => $jwt->sub ];
        $roles = [];
        $roles[] = 'ROLE_OAUTH_AUTHENTICATED';

        if (isset($jwt->scope)) {
            $scopes = explode(' ', $jwt->scope);
            if (array_search('read:articles', $scopes) !== false) {
                $roles[] = 'ROLE_OAUTH_READER';
            }
        }
        return new User($data, $roles);
    }

    public function loadUserByUsername($username)
    {
        throw new RuntimeException('method not implemented');
    }

    public function getAnonymousUser() {
        return new AnonymousUser();
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    get_class($user)
                )
            );
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'App\Domain\User\Entity\User';
    }
}
