<?php

namespace Edu\FactoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Edu\FormaBundle\Entity\Advert;
use Edu\FormaBundle\Entity\Category;

class FactoryController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->add('valider', 'choice', array('choices'  => array(1 => 'Promouvoir', 0 => 'Refuser')));
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($form->handleRequest($request)->isValid()) {
            $user_author = $em->getRepository('EduUserBundle:User')->findOneBy(array('beauthor' => true, 'factory' => $user->getFactory()));
            $nom = $form->get('valider')->getData();
            if ($nom == 1) {
                $user_author->addRole('ROLE_AUTEUR');
                $userManager = $this->container->get('fos_user.user_manager');
                $userManager->updateUser($user);
            }
            $user_author->setBeauthor(false);
            $em->flush();
        }
        $user_author = $em->getRepository('EduUserBundle:User')->findOneBy(array('beauthor' => true, 'factory' => $user->getFactory()));
        $advert = $em->getRepository('EduFormaBundle:Advert')->findOneBy(array('published' => false, 'factory' => $user->getFactory()));
        return $this->render('EduFactoryBundle:Default:home.html.twig', array('advert' => $advert, 'author' => $user_author, 'form'   => $form->createView()));
    }

    public function formationAction(){
        return $this->render('EduFactoryBundle:Default:formation.html.twig');
    }

    public function categorieAction(Request $request){
        $form = $this->createFormBuilder()->getForm();
        $form->add('categorie', 'text');
        $em = $this->getDoctrine()->getManager();
        if ($form->handleRequest($request)->isValid()) {
            $categorie = new Category;
            $categorie->setName($form->get('categorie')->getData());
            $em->persist($categorie);
            $em->flush();
            return $this->redirect($this->generateUrl('edu_factory_homepage'));
        }
        return $this->render('EduFactoryBundle:Default:categorie.html.twig', array('form'   => $form->createView()));
    }

    public function formationsampleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $follows = $em->getRepository('EduFormaBundle:Advert')->findBy(array('factory' => $user->getFactory()), array('nbfollow' => 'desc'));
        $likes = $em->getRepository('EduFormaBundle:Advert')->findBy(array('factory' => $user->getFactory()), array('nblike' => 'desc')); //thinkaddtotalmethod
        $coachs = $em->getRepository('EduFormaBundle:Advert')->findBy(array('factory' => $user->getFactory()), array('nbcoach' => 'desc'));;
        return $this->render('EduFactoryBundle:Default:formationsample.html.twig', array('follows' => $follows, 'likes' => $likes, 'coachs' => $coachs));
    }
    public function membreAction(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $users = $em->getRepository('EduUserBundle:User')->findBy(array('factory' => $user->getFactory()));
        return $this->render('EduFactoryBundle:Default:membre.html.twig', array('users' => $users));
    }
    public function auteurAction(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $follows = $em->getRepository('EduUserBundle:User')->findBy(array('factory' => $user->getFactory()), array('nbfollow' => 'desc'));
        $likes = $em->getRepository('EduUserBundle:User')->findBy(array('factory' => $user->getFactory()), array('nblike' => 'desc')); //thinkaddtotalmethod
        $coachs = $em->getRepository('EduUserBundle:User')->findBy(array('factory' => $user->getFactory()), array('nbcoach' => 'desc'));;
        return $this->render('EduFactoryBundle:Default:author.html.twig', array('follows' => $follows, 'likes' => $likes, 'coachs' => $coachs));
    }
}