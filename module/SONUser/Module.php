<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SONUser;

use Zend\Mvc\MvcEvent;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

use SONUser\Auth\Adapter as AuthAdapter;

use Zend\ModuleManager\ModuleManager;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

  public function init(ModuleManager $moduleManager) {
        #buscando os eventos compartilhados
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        #associando um evento (validaAuth) ao dispath
        $sharedEvents->attach(
                "Zend\Mvc\Controller\AbstractActionController",
                MvcEvent::EVENT_DISPATCH,
                array($this, 'validaAuth'),
                100); #100 é a prioridade
  }

    public function validaAuth($e)
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage());

        $controller = $e->getTarget();
        $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

//         if(!$auth->getIdentity())
// $user = $auth->getIdentity();
// print "<pre>";
// print_r($auth->getIdentity());


        if(!$auth->getIdentity() and ($matchedRoute == "sonuser-admin" ||
                                      $matchedRoute == "sonuser-admin/paginator"))
            return $controller->redirect()->toRoute("sonuser-auth");
    }

    public function getServiceConfig()
    {

        return array(
          'factories' => array(
              'SONUser\Mail\Transport' => function($sm) {
                $config = $sm->get('Config');
                $transport = new SmtpTransport();
                $transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
                return $transport;
              },
              'SONUser\Service\User' => function($sm) {
                  $sm->get('SONUser\Mail\Transport');

                  return new Service\User($sm->get('Doctrine\ORM\EntityManager'),
                                          $sm->get('SONUser\Mail\Transport'),
                                          $sm->get('View'));
              },
              'SONUser\Auth\Adapter' => function($sm)
              {
                  return new AuthAdapter($sm->get('Doctrine\ORM\EntityManager'));
              }

          )
        );

    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'UserIdentity' => new View\Helper\UserIdentity()
            )
        );
    }
}
