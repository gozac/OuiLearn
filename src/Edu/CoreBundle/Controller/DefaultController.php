<?php

namespace Edu\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Edu\FormaBundle\Entity\Advert;
use Edu\UserBundle\Entity\User;
use FOS\UserBundle\Model;

class DefaultController extends Controller
{
    public function contactAction(Request $request)
    {
        //$request->getSession()->getFlashBag()->add('info', 'La page n\'est pas encore disponible');
        return $this->render('EduCoreBundle:Default:contact.html.twig');
    }
    public function myformationAction()
    {
        return $this->render('EduCoreBundle:Default:myformation.html.twig');
    }
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->add('recherche', 'text');
        if ($form->handleRequest($request)->isValid()) {
            $nom = $form->get('recherche')->getData();
            return $this->redirect($this->generateUrl('edu_forma_home', array('recherche' => $nom)));
        }
        $adverts = array();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $listfadverts = $em->getRepository('EduUserBundle:Followadvert')->findBy(array('user' => $user));
        foreach ($listfadverts as $fadvert){
            if ($fadvert->getFinished() == false)
                array_push($adverts, $fadvert->getAdvert());
    }
        return $this->render('EduCoreBundle:Default:index.html.twig', array('form'   => $form->createView(), 'adverts' => $adverts));
    }
    public function authorAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_AUTEUR')) {
            $request->getSession()->getFlashBag()->add('info', 'Vous êtes déja un auteur');
            return $this->redirect($this->generateUrl('edu_core_homepage'));
        }
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $user->setBeauthor(true);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Vous avez envoyer une demande auteur');
            return $this->redirect($this->generateUrl('edu_core_homepage'));
        }
        return $this->render('EduCoreBundle:Default:author.html.twig', array('form'   => $form->createView()));
    }

    public function rechercheAction(Request $request)
    {
        $form1 = $this->createFormBuilder()->getForm();
        $form2 = $this->createFormBuilder()->getForm();
        $form3 = $this->createFormBuilder()->getForm();

        $form1->add('recherche', 'text', array('required' => false));
        $form2->add('categories', 'entity', array(
            'class'    => 'EduFormaBundle:Category',
            'property' => 'name',
            'required' => false
            //,'expanded' => true
        ));
        $form3->add('user', 'text', array('required' => false));
        if ($form1->handleRequest($request)->isValid()) {
            $nom = $form1->get('recherche')->getData();
            return $this->redirect($this->generateUrl('edu_forma_home', array('recherche' => $nom)));
        }
        if ($form2->handleRequest($request)->isValid()) {
            $categorie = $form2->get('categories')->getData();
            $nom = $categorie->getName();
            return $this->redirect($this->generateUrl('edu_forma_categorie', array('recherche' => $nom)));
        }
        if ($form3->handleRequest($request)->isValid()) {
            $nom = $form3->get('user')->getData();
            return $this->redirect($this->generateUrl('edu_core_user', array('recherche' => $nom)));
        }
        return $this->render('EduCoreBundle:Default:recherche.html.twig', array('form1'   => $form1->createView(),'form2'   => $form2->createView(),'form3'   => $form3->createView()));
    }

    public function userAction($page, $recherche)
    {
        $list = array();
        $nbPerPage = 3;
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $listusers = $em
            ->getRepository('EduUserBundle:User')->findBy(array('factory' => $user->getFactory()));
        /* ->getAdverts($page, $nbPerPage);*/
        foreach ($listusers as $users) {
            if (strchr($users->getUsername(), $recherche))
                    array_push($list, $users);
        }
        $nbPages = ceil(count($listusers)/$nbPerPage);
        if ($nbPages < $page & $page != 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        $content = $this->get('twig')->render('EduCoreBundle:Default:user.html.twig', array(
            'listAdverts' => $list,
            'nbPages'     => $nbPages,
            'page'        => $page,
            'recherche' => $recherche));
        return new Response($content);
    }
}
