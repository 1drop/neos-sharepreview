<?php
namespace Onedrop\SharePreview\Security\Authentication\Token;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Security\Authentication\Token\AbstractToken;

class UrlToken extends AbstractToken
{
    /**
     * The password credentials
     * @var array
     * @Flow\Transient
     */
    protected $credentials = ['token' => ''];

    /**
     * Updates the authentication credentials, the authentication manager needs to authenticate this token.
     * This could be a username/password from a login controller.
     * This method is called while initializing the security context. By returning TRUE you
     * make sure that the authentication manager will (re-)authenticate the tokens with the current credentials.
     * Note: You should not persist the credentials!
     *
     * @param ActionRequest $actionRequest The current request instance
     * @return void
     */
    public function updateCredentials(ActionRequest $actionRequest)
    {
        if ($actionRequest->getHttpRequest()->getMethod() !== 'GET') {
            return;
        }

        $getArguments = $actionRequest->getHttpRequest()->getArguments();
        if (!empty($getArguments['token'])) {
            $this->credentials['token'] = $getArguments['token'];
            $this->setAuthenticationStatus(self::AUTHENTICATION_NEEDED);
        }

    }
}