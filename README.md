Onedrop.SharePreview
====================

Neos plugin that brings you secure preview links to any workspace without the need to have 
a shared workspace or a backend login.

How-To:
-------

* Install the package to ``Packages/Plugin/Onedrop.SharePreview`` (e.g. via ``composer require onedrop/sharepreview``)
* Add a secure token per workspace in your settings

Configure Tokens
----------------

If you want to enable preview, add a token to your global settings.yaml

```yaml
TYPO3:
  Flow:
    security:
      authentication:
        providers:
          PreviewProvider:
            providerOptions:
              workspaceTokens:
                'token': 'JHlChmbwBbkhwcMbS'
```

> **Note:** In the future it will be possible to have a token per workspace and only the allowed once are accessible
