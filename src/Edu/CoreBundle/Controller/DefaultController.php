<?php

namespace Edu\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model;

class DefaultController extends Controller
{
    public function contactAction(Request $request)
    {
        $request->getSession()->getFlashBag()->add('info', 'La page n\'est pas encore disponible');
        return $this->redirect($this->generateUrl('edu_core_homepage'));
    }
    public function myformationAction()
    {
        return $this->render('EduCoreBundle:Default:myformation.html.twig');
    }
    public function demo1Action()
    {
        return $this->render('EduCoreBundle:Default:demo1.html.twig');
    }
    public function demo2Action()
    {
        return $this->render('EduCoreBundle:Default:demo2.html.twig');
    }
    public function indexAction()
    {
        return $this->render('EduCoreBundle:Default:index.html.twig');
    }
    public function authorAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_AUTEUR')) {
            $request->getSession()->getFlashBag()->add('info', 'Vous êtes déja un auteur');
            return $this->redirect($this->generateUrl('edu_core_homepage'));
        }
        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            //$role = array('ROLE_AUTEUR');
            //$user->setRole($role);
            $user->addRole('ROLE_AUTEUR');
            $userManager = $this->container->get('fos_user.user_manager');
            $userManager->updateUser($user);
            $request->getSession()->getFlashBag()->add('info', 'Reconnectez vous pour devenir auteur');
            return $this->redirect($this->generateUrl('fos_user_security_logout'));
        }
        return $this->render('EduCoreBundle:Default:author.html.twig', array('form'   => $form->createView()));
    }
}
