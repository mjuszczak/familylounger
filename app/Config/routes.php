<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Extension handling
 */
Router::parseExtensions('json');

/**
 * Agnostic routes
 */
Router::connect('/register', array('controller' => 'Users', 'action' => 'register'));
Router::connect('/logout', array('controller' => 'Users', 'action' => 'logout'));
Router::connect('/mylounge', array('controller' => 'Lounges', 'action' => 'myLounge'));
Router::connect('/myprofile', array('controller' => 'Users', 'action' => 'profile'));
Router::connect('/users/profile/*', array('controller' => 'Users', 'action' => 'profile'));
Router::connect('/users/update', array('controller' => 'Users', 'action' => 'update'));

/**
 * Site routes and lounge routes
 */
if($_SERVER['HTTP_HOST'] == 'www.' . SITE_DOMAIN){

    Router::connect('/forgot-password', array('controller' => 'Users', 'action' => 'forgotPassword'));
    Router::connect('/reset-password/*', array('controller' => 'Users', 'action' => 'resetPassword'));
    Router::connect('/login', array('controller' => 'Users', 'action' => 'login'));
    Router::connect('/users/login', array('controller' => 'Users', 'action' => 'login'));

    Router::connect('/', array('controller' => 'Pages', 'action' => 'home'));
    Router::connect('/pages/*', array('controller' => 'Pages', 'action' => 'display'));

    Router::connect('/find', array('controller' => 'Lounges', 'action' => 'search'));
    Router::connect('/create', array('controller' => 'Lounges', 'action' => 'create'));
}
else {

    Router::connect('/landing', array('controller' => 'Lounges', 'action' => 'landing'));
    Router::connect('/users/login', array('controller' => 'Users', 'action' => 'login'));

    Router::connect('/', array('controller' => 'Posts','action' => 'index'));

    Router::connect('/updates', array('controller' => 'Updates', 'action' => 'index'));
    Router::connect('/updates/:action/*', array('controller' => 'Updates'));

    Router::redirect('/blog', array('controller' => 'Posts', 'action' => 'index'));
    Router::connect('/posts', array('controller' => 'Posts', 'action' => 'index'));
    Router::connect('/posts/comments/add/*', array('controller' => 'Posts', 'action' => 'addComment'));
    Router::connect('/posts/:action/*', array('controller' => 'Posts'));

    Router::connect('/calendar', array('controller' => 'CalendarEntries', 'action' => 'index'));
    Router::connect('/calendar/add', array('controller' => 'CalendarEntries', 'action' => 'add'));
    Router::connect('/calendar/edit/*', array('controller' => 'CalendarEntries', 'action' => 'edit'));
    Router::connect('/calendar/view/*', array('controller' => 'CalendarEntries', 'action' => 'view'));
    Router::connect('/calendar/delete/*', array('controller' => 'CalendarEntries', 'action' => 'delete'));
    Router::connect('/calendar/*', array('controller' => 'CalendarEntries', 'action' => 'index'));

    Router::connect('/people', array('controller' => 'LoungeUsers', 'action' => 'index'));
    Router::connect('/people/edit/*', array('controller' => 'LoungeUsers', 'action' => 'edit'));
    Router::connect('/people/delete/*', array('controller' => 'LoungeUsers', 'action' => 'delete'));

    Router::connect('/request-invite', array('controller' => 'LoungeRequests', 'action' => 'add'));
    Router::connect('/people/requests', array('controller' => 'LoungeRequests', 'action' => 'index'));
    Router::connect('/people/requests/accept/*', array('controller' => 'LoungeRequests', 'action' => 'accept'));
    Router::connect('/people/requests/ignore/*', array('controller' => 'LoungeRequests', 'action' => 'ignore'));

    Router::connect('/accept-invite/*', array('controller' => 'LoungeInvitations', 'action' => 'accept'));
    Router::connect('/people/invite', array('controller' => 'LoungeInvitations', 'action' => 'invite'));
    Router::connect('/people/invites', array('controller' => 'LoungeInvitations', 'action' => 'index'));
    Router::connect('/people/invites/delete/*', array('controller' => 'LoungeInvitations', 'action' => 'delete'));

    Router::connect('/medications', array('controller' => 'Medications', 'action' => 'index'));
    Router::connect('/medications/:action/*', array('controller' => 'Medications'));

    Router::connect('/tasks', array('controller' => 'Tasks', 'action' => 'index'));
    Router::connect('/tasks/:action/*', array('controller' => 'Tasks'));
}


/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
//	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
//	require CAKE . 'Config' . DS . 'routes.php';
