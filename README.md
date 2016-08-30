Onedrop.SharePreview
====================

This Neos plugin brings you secure preview links to any workspace without the need to have a backend login.

You're doing changes that should not be published yet, but you need to show them to somebody, just use this plugin
and send him the secure preview link.

Installation
------------

* Install the package to ``Packages/Plugin/Onedrop.SharePreview`` (e.g. via ``composer require onedrop/sharepreview``)
* Add a secure token per workspace in your settings (see "Configure Tokens")
* Apply patch if necessary (see "Patching")

Patching
--------

If you're prior to Neos 3.0 (probably), you will need to patch the Neos package in order for this to work.  
https://github.com/neos/neos-development-collection/pull/682/files is the necessary change that has already
been merged into the master branch of Neos and will most likely be released with Version 3.0.

In the meantime you can use the `TYPO3.Neos.diff` file in this package to patch your Neos package with e.g. 
 [Beard](https://github.com/mneuhaus/Beard) or manually.

Configure Tokens
----------------

If you want to enable preview, add a token for the workspace to your global settings.yaml

```yaml
TYPO3:
  Flow:
    security:
      authentication:
        providers:
          PreviewProvider:
            providerOptions:
              workspaceTokens:
                'user-demo': 'JHlChmbwBbkhwcMbS'
```

Sharing Links
-------------

Just copy the link of the page you're currently viewing when you're logged in to the workspace you're currently 
modifying e.g. `http://neos.dev/en/features@user-demo;language=en_US.html` and append the token to that URL: 
`http://neos.dev/en/features@user-demo;language=en_US.html?token=JHlChmbwBbkhwcMbS`.  
You can send this link to anybody and he can preview the changes in your workspace.
