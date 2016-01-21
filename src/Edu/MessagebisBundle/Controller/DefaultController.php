<?php

namespace Edu\MessagebisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\Message\Driver;
use Edu\MessagebisBundle\Entity;
use FOS\Message\Repository;
use Symfony\Component\HttpFoundation\Request;
//require 'vendor/autoload.php';


class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $driver = new Driver\DoctrineORM\DoctrineORMDriver($em);
        $repository = Repository::createFromModels($driver, 'Edu\MessagebisBundle\Entity\Thread', 'Edu\MessagebisBundle\Entity\Message');
        $provider = $repository->getProvider();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $inbox = $provider->getInboxThreads($user);
        $sent = $provider->getSentThreads($user);
        return $this->render('EduMessagebisBundle:Default:index.html.twig', array('inbox' => $inbox, 'sent' => $sent));
    }

    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $driver = new Driver\DoctrineORM\DoctrineORMDriver($em);
        $repository = Repository::createFromModels($driver, 'Edu\MessagebisBundle\Entity\Thread', 'Edu\MessagebisBundle\Entity\Message');
        $thread = $repository->getProvider()->getThread($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $repository->getReader()->markAsRead($thread, $user);
        $form = $this->createFormBuilder()->getForm();
        $form->add('content', 'textarea', array(
            'attr' => array('class' => 'tinymce', 'data-theme' => 'simple')));
        if ($form->handleRequest($request)->isValid()) {
            $contenu = $form->get('content')->getData();
            $message = $repository->getComposer()->createReplyBuilder($thread)
                ->setAuthor($user)
                ->setContent($contenu)
                ->getMessage();
            $repository->getSender()->send($message);
            return $this->redirect($this->generateUrl('edu_messagebis_view', array('id' => $id)));
        }
        return $this->render('EduMessagebisBundle:Default:view.html.twig', array('form'   => $form->createView(), 'thread' => $thread));
    }

    public function addAction(Request $request){
        $form = $this->createFormBuilder()->getForm();
        $form->add('content', 'textarea', array(
            'attr' => array('class' => 'tinymce', 'data-theme' => 'simple')));
        $form->add('objet', 'text');
        $form->add('destinataire', 'text');
        $form->add('save',      'submit');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $driver = new Driver\DoctrineORM\DoctrineORMDriver($em);
            $repository = Repository::createFromModels($driver, 'Edu\MessagebisBundle\Entity\Thread', 'Edu\MessagebisBundle\Entity\Message');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $destinataire = $em->getRepository('EduUserBundle:User')->findOneBy(array('username' => $form->get('destinataire')->getData()));
            if ($destinataire == null || $user == $destinataire)
                throw new NotFoundHttpException("Le destinataire ".$destinataire." n'existe pas ou est invalide.");
            $composer = $repository->getComposer();
            $sender = $repository->getSender();

            $message = $composer->createNewThreadBuilder()
                ->setSubject($form->get('objet')->getData())
                ->setAuthor($user)
                ->setContent($form->get('content')->getData())
                ->addRecipient($destinataire)
                ->getMessage();
            $sender->send($message);
            $request->getSession()->getFlashBag()->add('notice', 'Message envoyé.');
            return $this->redirect($this->generateUrl('edu_messagebis_homepage'));
        }
        return $this->render('EduMessagebisBundle:Default:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
