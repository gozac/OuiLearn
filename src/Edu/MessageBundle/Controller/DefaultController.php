<?php

namespace Edu\MessageBundle\Controller;

use Edu\MessageBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Edu\MessageBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $trimessages = array();
        $listauteur = array();
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $messages = $em->getRepository('EduMessageBundle:Message')->findby(array('receiver' => $user), array('date' => 'ASC'));
        foreach ($messages as $message)
        {
            if (in_array($message->getSender()->getUsername(), $listauteur) == false)
            {
                array_push($trimessages, $message);
                array_push($listauteur, $message->getSender()->getUsername());
            }
        }
        return $this->render('EduMessageBundle:Default:index.html.twig', array('messages' => $trimessages));
    }
    public function viewAction($sender, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $senderuser = $em->getRepository('EduUserBundle:User')->findOneBy(array('username' => $sender));
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($senderuser == null || $user == $senderuser)
            throw new NotFoundHttpException("Le destinataire ".$sender." n'existe pas ou est invalide.");
        $form = $this->createFormBuilder()->getForm();
        $form->add('content', 'textarea', array(
            'attr' => array('class' => 'tinymce', 'data-theme' => 'simple')));
        if ($form->handleRequest($request)->isValid()) {
            $reply = new Message;
            $contenu = $form->get('content')->getData();
            $reply->setContent($contenu);
            $reply->setReceiver($senderuser);
            $reply->setSender($user);
            $em->persist($reply);
            $em->flush();
            return $this->redirect($this->generateUrl('edu_message_view', array('sender' => $sender)));
        }
        $messages = $em->getRepository('EduMessageBundle:Message')->findby(array('receiver' => array($user,$senderuser), 'sender' => array($user,$senderuser)), array('date' => 'ASC'));
        return $this->render('EduMessageBundle:Default:view.html.twig', array('form'   => $form->createView(), 'messages' => $messages, 'sender' => $sender));
    }
    public function addAction(Request $request){
        $message = new Message;
        $form = $this->createFormBuilder()->getForm();
        $form->add('content', 'textarea', array(
            'attr' => array('class' => 'tinymce', 'data-theme' => 'simple')));
        $form->add('destinataire', 'text');
        $form->add('save',      'submit');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $message->setSender($user);
            $message->setContent($form->get('content')->getData());
            $destinataire = $em->getRepository('EduUserBundle:User')->findOneBy(array('username' => $form->get('destinataire')->getData()));
            if ($destinataire == null || $user == $destinataire)
                    throw new NotFoundHttpException("Le destinataire ".$destinataire." n'existe pas ou est invalide.");
            $message->setReceiver($destinataire);
            $em->persist($message);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Message envoyé.');
            return $this->redirect($this->generateUrl('edu_message_homepage'));
        }
        return $this->render('EduMessageBundle:Default:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
