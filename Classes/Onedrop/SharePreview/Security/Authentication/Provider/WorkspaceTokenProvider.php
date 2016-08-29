<?php
namespace Onedrop\SharePreview\Security\Authentication\Provider;

use TYPO3\Flow\Annotations as Flow;
use Onedrop\SharePreview\Security\Authentication\Token\UrlToken;
use TYPO3\Flow\Security\Account;
use TYPO3\Flow\Security\Authentication\Provider\AbstractProvider;
use TYPO3\Flow\Security\Authentication\TokenInterface;
use TYPO3\Flow\Security\Cryptography\HashService;
use TYPO3\Flow\Security\Exception\UnsupportedAuthenticationTokenException;
use TYPO3\Flow\Security\Policy\PolicyService;

class WorkspaceTokenProvider extends AbstractProvider
{
    /**
     * @Flow\Inject
     * @var HashService
     */
    protected $hashService;

    /**
     * @Flow\Inject
     * @var PolicyService
     */
    protected $policyService;

    /**
     * Returns the classnames of the tokens this provider is responsible for.
     *
     * @return array The classname of the token this provider is responsible for
     */
    public function getTokenClassNames()
    {
        return [UrlToken::class];
    }

    /**
     * @param TokenInterface $authenticationToken
     * @throws UnsupportedAuthenticationTokenException
     */
    public function authenticate(TokenInterface $authenticationToken)
    {
        if (!($authenticationToken instanceof UrlToken)) {
            throw new UnsupportedAuthenticationTokenException('This provider cannot authenticate the given token.', 1472477331);
        }

        $credentials = $authenticationToken->getCredentials();
        if (is_array($credentials) && isset($credentials['token'])) {
            // todo: get the workspace somehow
            $workspace = 'token';

            if ($credentials['token'] === $this->options['workspaceTokens'][$workspace]) {
                $authenticationToken->setAuthenticationStatus(TokenInterface::AUTHENTICATION_SUCCESSFUL);
                $account = new Account();
                $roles = array();
                foreach ($this->options['authenticateRoles'] as $roleIdentifier) {
                    $roles[] = $this->policyService->getRole($roleIdentifier);
                }
                $account->setRoles($roles);
                $authenticationToken->setAccount($account);
            } else {
                $authenticationToken->setAuthenticationStatus(TokenInterface::WRONG_CREDENTIALS);
            }
        } elseif ($authenticationToken->getAuthenticationStatus() !== TokenInterface::AUTHENTICATION_SUCCESSFUL) {
            $authenticationToken->setAuthenticationStatus(TokenInterface::NO_CREDENTIALS_GIVEN);
        }
    }
}