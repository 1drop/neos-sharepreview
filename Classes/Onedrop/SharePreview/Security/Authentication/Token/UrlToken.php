<?php
namespace Onedrop\SharePreview\Security\Authentication\Token;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Security\Authentication\Token\AbstractToken;
use TYPO3\TYPO3CR\Domain\Utility\NodePaths;

class UrlToken extends AbstractToken
{
    /**
     * The password credentials
     * @var array
     * @Flow\Transient
     */
    protected $credentials = ['token' => '', 'workspace' => ''];

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

        $requestPath = $actionRequest->getHttpRequest()->getRelativePath();
        $workspaceName = $this->getWorkspaceNameFromRequestPath($requestPath);
        $getArguments = $actionRequest->getHttpRequest()->getArguments();

        if (!empty($getArguments['token']) && $workspaceName !== 'live') {
            $this->credentials['token'] = $getArguments['token'];
            $this->credentials['workspace'] = $workspaceName;
            $this->setAuthenticationStatus(self::AUTHENTICATION_NEEDED);
        }
    }

    /**
     * This is mostly a duplicate from the FrontendNodeRoutePartHandler, because there's no other way
     * to get the determined workspace than parsing it again.
     *
     * @see \TYPO3\Neos\Routing\FrontendNodeRoutePartHandler::buildContextFromRequestPath
     * @param string $requestPath
     * @return string The workspaceName if it was contained in the given path
     */
    protected function getWorkspaceNameFromRequestPath($requestPath)
    {
        $workspaceName = 'live';
        if (strpos($requestPath, '@') === 0) {
            $requestPath = '/' . $requestPath;
        }
        if ($requestPath !== '' && NodePaths::isContextPath($requestPath)) {
            try {
                $nodePathAndContext = NodePaths::explodeContextPath($requestPath);
                $workspaceName = $nodePathAndContext['workspaceName'];
            } catch (\InvalidArgumentException $exception) {
            }
        }
        return $workspaceName;
    }
}
