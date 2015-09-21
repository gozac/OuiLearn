<?php

// src/Edu/FormaBundle/Controller/AdvertController.php

namespace Edu\FormaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Edu\FormaBundle\Entity\Advert;
use Edu\FormaBundle\Form\AdvertType;
use Edu\FormaBundle\Form\AdvertEditType;
use Edu\FormaBundle\Entity\AdvertSkill;
//use Edu\FormaBundle\Entity\Category;
use Edu\FormaBundle\Entity\Image;
use Edu\FormaBundle\Entity\Application;
//use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdvertController extends Controller
{
    public function viewAction(Advert $advert, $id, $page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        $em = $this->getDoctrine()->getManager();/*

        // On récupère l'annonce $id
        $advert = $em
            ->getRepository('EduFormaBundle:Advert')
            ->find($id)
        ;

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }*/

        // On avait déjà récupéré la liste des candidatures
        $listApplications = $em
            ->getRepository('EduFormaBundle:Application')
            ->findBy(array('advert' => $advert))
        ;

        // On récupère maintenant la liste des AdvertSkill
        $listAdvertSkills = $em
            ->getRepository('EduFormaBundle:AdvertSkill')
            ->findBy(array('advert' => $advert))
        ;

        return $this->render('EduFormaBundle:Advert:view.html.twig', array(
            'advert'           => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills,
            'page' => $page
        ));
    }
    public function indexAction($page)
    {
        $nbPerPage = 3;
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em
            ->getRepository('EduFormaBundle:Advert')
            ->getAdverts($page, $nbPerPage);

        $nbPages = ceil(count($listAdverts)/$nbPerPage);
        if ($nbPages < $page & $page != 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        $content = $this->get('twig')->render('EduFormaBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
            'nbPages'     => $nbPages,
            'page'        => $page));
        return new Response($content);
    }
    /*/**
     * @Security("has_role('ROLE_AUTEUR')")
     */
    public function addAction(Request $request)
    {
        /*if (!$this->get('security.context')->isGranted('ROLE_AUTEUR')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux auteurs.');
        }*/
        $advert = new Advert;
        $form = $this->get('form.factory')->create(new AdvertType, $advert);
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes
        // (Nous verrons la validation des objets en détail dans le prochain chapitre)
        if ($form->isValid()) {
            // On l'enregistre notre objet $advert dans la base de données, par exemple
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $advert->setAuthor($user->getUsername());
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Formation bien enregistrée.');

            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirect($this->generateUrl('edu_forma_view', array('id' => $advert->getId())));
        }

        // On récupère l'EntityManager
        // La gestion d'un formulaire est particulière, mais l'idée est la suivante :
        // On récupère le service
        $antispam = $this->container->get('edu_forma.antispam');

        // Je pars du principe que $text contient le texte d'un message quelconque
        $text = $advert->getContent();
       // if ($antispam->isSpam($text)) {
       //     throw new \Exception('Votre message a été détecté comme spam !');
       //}
        // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        return $this->render('EduFormaBundle:Advert:add.html.twig', array(
            'form' => $form->createView(),
        ));
        // Si on n'est pas en POST, alors on affiche le formulaire
    }
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('EduFormaBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $form = $this->get('form.factory')->create(new AdvertEditType, $advert);
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes
        // (Nous verrons la validation des objets en détail dans le prochain chapitre)
        if ($form->isValid()) {
            // On l'enregistre notre objet $advert dans la base de données, par exemple
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Formation bien modifiée.');

            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirect($this->generateUrl('edu_forma_view', array('id' => $advert->getId())));
        }
        return $this->render('EduFormaBundle:Advert:edit.html.twig', array(
            'form' => $form->createView(),
            'advert' => $advert
        ));
    }

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('EduFormaBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {

            $em->remove($advert);

        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

        // On déclenche la modification
        $em->flush();
        // Ici, on récupérera l'annonce correspondant à $id

            // Ici, on s'occupera de la création et de la gestion du formulaire
            $request->getSession()->getFlashBag()->add('info', 'Formation bien suprimée.');

            // Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirect($this->generateUrl('edu_forma_home'));
        }
        // Ici, on gérera la suppression de l'annonce en question
        return $this->render('EduFormaBundle:Advert:delete.html.twig', array('advert' => $advert, 'form'   => $form->createView()));
    }

    public function menuAction($limit)
    {
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository('EduFormaBundle:Advert')->findby(array(), array('date' => 'DESC'), $limit, 0);
        return $this->render('EduFormaBundle:Advert:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }
}
