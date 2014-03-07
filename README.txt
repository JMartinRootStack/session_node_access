INTRODUCTION
------------
This module grants per-session permissions for anonymous users to access nodes they created.

Yet another simple node access module, this time catering to anonymous users. It grants temporary (session based) node access permissions to anonymous users after they create that node.

Consider the following use case:
Content created by anonymous users is set to be unpublished until a mod reviews it. With this module the anonymous user gets to view/edit/delete their freshly created content without it to be accessible to anyone else. This access lasts as long as the user's session lasts, which means, as soon as the session expires, or the user changes the browser, they loose access to their content until it gets published.
Obviously this is better than seeing 'Access denied' right after creating a node.
  
INSTALLATION
------------
* Install as usual, see https://drupal.org/documentation/install/modules-themes/modules-7 for further information.
  
CONFIGURATION
-------------
* ATTN: This module will not work if another content access module (or drupal's native permission tab) doesn't grant anonymous users to create nodes of a given tab. The module will check if the anonymous users have this permission and will warn if that's not the case.

* ATTN 2: As most drupal access modules, this module does not deny access to anything, it only grants access. Please make sure to check the settings of other access control modules and the permissions tab if something does is not working as expected.

* In order to have access to the module settings you need to grant it the appropriate permimssions in the permissions overview.
* To configure the module, click on 'configure' next to its entry on the module overview page or visit the link admin/config/anonymous_session_nodeaccess.

* Restrict by content type: here you can alter the modules functionality (granting access to anonymous to the content they created) by restricting it to do so depending on content type.
* Grant these permissions: The permissions to be granted by the module can be set here.
* Take efffect only on published nodes: Set whether the access permissions granting is to be perfomed on published nodes only or on published and unpublished ones.
