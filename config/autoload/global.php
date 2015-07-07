<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'mail' => array(
        'transport' => array(
            'options' => array(
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'connection_class' => 'plain',
                'connection_config' => array(
                    'username' => 'adzf2.project@gmail.com',
                    'password' => 'adzf2021221*',
                    'ssl' => 'tls'
                )
            )
        )
    ),
    'service_manager' => array(
        'aliases' => array(
            'Zend\Authentication\AuthenticationService' => 'SONUser\Entity\User',
        ),
        'invokables' => array(
            'SONUser\Entity\User' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
);
