<?php

return array(
    'bjyauthorize' => array(

        // set the 'guest' role as default (must be defined in a role provider)
        'default_role' => 'guest',

        /* this module uses a meta-role that inherits from any roles that should
         * be applied to the active user. the identity provider tells us which
         * roles the "identity role" should inherit from.
         *
         * for ZfcUser, this will be your default identity provider
         */
        'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserZendDb',

        /* role providers simply provide a list of roles that should be inserted
         * into the Zend\Acl instance. the module comes with two providers, one
         * to specify roles in a config file and one to load roles using a
         * Zend\Db adapter.
         */
        'role_providers' => array(

            /* here, 'guest' and 'user are defined as top-level roles, with
             * 'admin' inheriting from user
             */
            'BjyAuthorize\Provider\Role\Config' => array(
                'guest' => array(),
                'user'  => array('children' => array(
                    'admin' => array(),
                )),
            ),

            // this will load roles from the user_role table in a database
            // format: user_role(role_id(varchar), parent(varchar))
            'BjyAuthorize\Provider\Role\ZendDb' => array(
                'table'             => 'user_role',
                'role_id_field'     => 'role_id',
                'parent_role_field' => 'parent',
            ),
        ),

        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'admin' => array(),
                //'pants' => array(),
            ),
        ),

        /* rules can be specified here with the format:
         * array(roles (array), resource, [privilege (array|string), assertion])
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"
                    //array(array('guest', 'user'), 'pants', 'wear'),
                    array(array('admin'), 'admin'),
                ),

                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                    // ...
                ),
            ),
        ),

        /* Currently, only controller and route guards exist
         */
        'guards' => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */
            'BjyAuthorize\Guard\Controller' => array(
                //array('controller' => 'index', 'action' => 'index', 'roles' => array('guest','user')),
                //array('controller' => 'index', 'action' => 'stuff', 'roles' => array('user')),
                array('controller' => 'zfcuser', 'roles' => array()),
                array('controller' => 'Application\Controller\Index', 'roles' => array('guest', 'user')),
                array('controller' => 'Admin\Controller\Index', 'roles' => array('admin')),
                array('controller' => 'Album\Controller\Album', 'roles' => array('admin')),
                array('controller' => 'Form\Controller\Index', 'roles' => array('admin')),
            ),

            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'zfcuser', 				'roles' => array('guest','user')),
                array('route' => 'zfcuser/logout', 			'roles' => array('user')),
                array('route' => 'zfcuser/login', 			'roles' => array('guest')),
                array('route' => 'zfcuser/register', 		'roles' => array('guest')),
                array('route' => 'zfcuser/changepassword', 	'roles' => array('user')),
                array('route' => 'zfcuser/changeemail', 	'roles' => array('user')),
                // Below is the default index action used by the [ZendSkeletonApplication](https://github.com/zendframework/ZendSkeletonApplication)
                array('route' => 'home', 	'roles' => array('guest', 'user')),
                array('route' => 'about', 	'roles' => array('guest', 'user')),
                array('route' => 'admin', 	'roles' => array('admin')),
                array('route' => 'admin/users', 	'roles' => array('admin')),
                array('route' => 'album', 	'roles' => array('admin')),

                array('route' => 'form', 	'roles' => array('admin')),
                array('route' => 'form/create','roles' => array('admin')),
                array('route' => 'form/input','roles' => array('admin')),
                array('route' => 'form/paragraph','roles' => array('admin')),
            ),
        ),
    ),

);