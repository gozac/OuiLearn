<?php

// src/Edu/FormaBundle/Controller/AdvertController.php

namespace Edu\FormaBundle\Controller;

use Edu\FormaBundle\Entity\Content;
use Edu\UserBundle\Form\FollowadvertType;
use Edu\FormaBundle\Form\ContentTextType;
use Edu\FormaBundle\Form\ContentType;
use Edu\QcmBundle\Entity\Reponse;
use Edu\QcmBundle\Entity\Questionnaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Edu\FormaBundle\Entity\Advert;
use FOS\Message\Driver;
use Edu\MessagebisBundle\Entity;
use FOS\Message\Repository;
use Edu\FormaBundle\Form\AdvertType;
use Edu\FormaBundle\Form\AdvertEditType;
use Edu\UserBundle\Entity\Followadvert;
//use Edu\FormaBundle\Entity\Category;
//use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdvertController extends Controller
{
    /*public function shuffle_assoc(&$array) {
        $keys = array_keys($array);
        //$new = array();

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }*/
    public function generateForm($questionnaire)
    {
        //Get question array collection
        $questions = $questionnaire;
        $formBuilderQuestionnaire = $this->createFormBuilder();

        $i = 0;
        //Make a loop for each question
        foreach($questions as $question)
        {
            //Create an answer form
            $answer = new Reponse();
            $formBuilder = $this->get('form.factory')->createNamedBuilder($i, 'form', $answer);

            $reponsearray = array();
            if($question->getReponseFausse1() != null)
            $reponsearray[$question->getReponseFausse1()] = 0;
            if($question->getReponseFausse2() != null)
            $reponsearray[$question->getReponseFausse2()] = 0;
            if($question->getReponseFausse3() != null)
            $reponsearray[$question->getReponseFausse3()] = 0;
            $reponsearray[$question->getReponseVrai()] = 1;
            //$this->shuffle_assoc($reponsearray);
            $formBuilder
            ->add('resultat', 'choice', array(
                'choices' => $reponsearray
                , 'choices_as_values' => true,
                'group_by' => function($category, $key, $index) {
                    // randomly assign things into 2 groups
                    return rand(0, 1) == 1 ? 'Group A' : '';
                },
            'label'  => $question->getQuestion(),
                'expanded' => true,
            ));

            $formBuilderQuestionnaire->add($formBuilder);

            $i++;
        }
        //Create the form
        $formBuilderQuestionnaire->add('save',      'submit');
        $form = $formBuilderQuestionnaire->getForm();
        return $form;
    }

    public function viewAction(Request $request, advert $advert, $page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user->getFactory() != $advert->getFactory()) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité.');
        }
        $em = $this->getDoctrine()->getManager();
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $advert->getId() . " n'existe pas.");
        }
        $contentnew = $advert->getContentnew();
        for ($i = 1; $i < $page; $i++){
            $contentnew = $contentnew->getSuite();
        }
        $form = $this->generateForm($contentnew->getQuestionnaire());
        if ($form->handleRequest($request)->isValid()) {
            $score = 0;
            $questionnaire = $form->getData();
            foreach ($questionnaire as $question) {
                $score += $question->getResultat();
            }
            if ($score >= $contentnew->getScoreMinimum()){
                $followadvert = $em->getRepository('EduUserBundle:Followadvert')->findOneBy(array('advert' => $advert, 'user' => $user));
                $followadvert->decreaseNbqcm();
                $em->flush();
                $request->getSession()->getFlashBag()->add('info', 'Vous avez reussi ce questionnaire !');
                return $this->redirect($this->generateUrl('edu_forma_view', array('id' => $advert->getId())));
            }
            else
                return $this->redirect($this->generateUrl('edu_forma_view', array('id' => $advert->getId())));
        }
        $followadvert = $em->getRepository('EduUserBundle:Followadvert')->findOneBy(array('advert' => $advert, 'user' => $user));
            return $this->render('EduFormaBundle:Advert:view.html.twig', array(
                'form'   => $form->createView(),
                'fadvert' => $followadvert,
                'user' => $user,
                'advert' => $advert,
                'page' => $page,
                'contentnew' => $contentnew
            ));
    }

    public function followAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('EduFormaBundle:Advert')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        if ($user->getFactory() != $advert->getFactory()) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité.');
        }
        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $fadvert = new Followadvert();
            $fadvert->setAdvert($advert);
            $fadvert->setUser($user);
            $fadvert->setNbqcm($advert->getNbqcm());

            $em->persist($fadvert);
            $em->flush();
            return $this->redirect($this->generateUrl('edu_forma_view', array('id' => $advert->getId())));
        }
        return $this->render('EduFormaBundle:Advert:suivreformation.html.twig', array('advert' => $advert, 'form'   => $form->createView()));
}
    /*/**
    * @Security("has_role('ROLE_FACTORY_ADMIN')")
    */
    public function publishAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('EduFormaBundle:Advert')->find($id);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $advert->setPublished(true);
            $em->flush();
            return $this->redirect($this->generateUrl('edu_forma_view', array('id' => $advert->getId())));
        }
        return $this->render('EduFormaBundle:Advert:publish.html.twig', array('advert' => $advert, 'form'   => $form->createView()));
    }

    public function indexAction($page, $recherche)
    {
        $list = array();
        $nbPerPage = 3;
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $listAdverts = $em
            ->getRepository('EduFormaBundle:Advert')->findBy(array('factory' => $user->getFactory()));
           /* ->getAdverts($page, $nbPerPage);*/
        foreach ($listAdverts as $advert) {
            if (strchr($advert->getTitle(), $recherche))
                array_push($list, $advert);
        }
        $nbPages = ceil(count($listAdverts)/$nbPerPage);
        if ($nbPages < $page & $page != 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        $content = $this->get('twig')->render('EduFormaBundle:Advert:recherche.html.twig', array(
            'listAdverts' => $list,
            'nbPages'     => $nbPages,
            'page'        => $page,
        'recherche' => $recherche));
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
            $advert->setAuthor($user);
            $advert->setFactory($user->getFactory());
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
        //$text = $advert->getContent();
       // if ($antispam->isSpam($text)) {
       //     throw new \Exception('Votre message a été détecté comme spam !');
       //}
        // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        return $this->render('EduFormaBundle:Advert:add.html.twig', array(
            'form' => $form->createView(),
        ));
        // Si on n'est pas en POST, alors on affiche le formulaire
    }

    public function addpageAction(Advert $advert, Request $request){
        $em = $this->getDoctrine()->getManager();
        $content = new Content;
        if ($this->get('security.token_storage')->getToken()->getUser()->getUsername() != $advert->getAuthor()) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité.');
        }
        $form = $this->get('form.factory')->create(new ContentTextType, $content);
        if ($form->handleRequest($request)->isValid()) {
            $bouclecontent = $advert->getContentnew();
            while($bouclecontent->getSuite()){
                $bouclecontent = $bouclecontent->getSuite();
            }

            $bouclecontent->setSuite($content);
            $advert->increaseContent();
            $em->persist($content);
            $em->flush();
            return $this->redirect($this->generateUrl('edu_forma_view', array('id' => $advert->getId())));
        }
        return $this->render('EduFormaBundle:Advert:addpage.html.twig', array(
            'form' => $form->createView()));
    }
    public function editAction($id, $page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // On récupère l'annonce $id
        $advert = $em->getRepository('EduFormaBundle:Advert')->find($id);
        if ($this->get('security.token_storage')->getToken()->getUser()->getUsername() != $advert->getAuthor()) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité.');
        }
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $contentnew = $advert->getContentnew();
        for ($i = 1; $i < $page; $i++){
            $contentnew = $contentnew->getSuite();
        }
        $form = $this->createFormBuilder()->getForm();
        $form->add('advert', new AdvertEditType, array('data' => $advert));
        $form->add('content', new ContentType, array('data' => $contentnew));
        $form->add('save',      'submit');
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes
        // (Nous verrons la validation des objets en détail dans le prochain chapitre)
        if ($form->isValid()) {
            // On l'enregistre notre objet $advert dans la base de données, par exemple
            $advert = $form->get('advert')->getData();
            $contentnew = $form->get('content')->getData();

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
        if ($this->get('security.token_storage')->getToken()->getUser()->getUsername() != $advert->getAuthor()) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité.');
        }
        $form = $this->createFormBuilder()->getForm();
        if ($form->handleRequest($request)->isValid()) {
            $fadverts = $em->getRepository('EduUserBundle:Followadvert')->findBy(array('advert' => $advert));
            foreach ($fadverts as $fadvert){
                $em->remove($fadvert);
            }
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
        $adverts = array();
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $listAdverts = $em->getRepository('EduFormaBundle:Advert')->findby(array('published' => true, 'factory' => $user->getFactory()), array('date' => 'DESC'), $limit, 0);
        $listfadverts = $em->getRepository('EduUserBundle:Followadvert')->findBy(array('user' => $user));
        foreach ($listAdverts as $advert) {
            $pure = true;
            foreach ($listfadverts as $fadvert) {
                if ($fadvert->getAdvert()->getSlug() == $advert->getSlug())
                    $pure = false;
            }
            if ($pure == true)
                array_push($adverts, $advert);
        }
        return $this->render('EduFormaBundle:Advert:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $adverts
        ));
    }

    public function finishAction(Advert $advert, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $followadvert = $em->getRepository('EduUserBundle:Followadvert')->findOneBy(array('advert' => $advert, 'user' => $user));
        if ($followadvert->getNbqcm() != 0)
            throw new AccessDeniedException('Vous n\'avez pas valider tous les questionnaires.');
        $form = $this->get('form.factory')->create(new FollowadvertType(), $followadvert);
        if ($form->handleRequest($request)->isValid()) {
            $followadvert->setFinished(true);
            if ($followadvert->getLudique() == 1)
                $followadvert->getAdvert()->increaseLike();
            if ($followadvert->getCoaching() == true) {
                $em = $this->getDoctrine()->getManager();
                $driver = new Driver\DoctrineORM\DoctrineORMDriver($em);
                $repository = Repository::createFromModels($driver, 'Edu\MessagebisBundle\Entity\Thread', 'Edu\MessagebisBundle\Entity\Message');
                $contenu = "Bonjour,\nl'aprennant " . $user->getUsername() . " a demandé un coaching après avoir terminé la formation : " . $advert->getTitle();
                $contenu .= " avec la remarque : " . $followadvert->getRemarque();
                $composer = $repository->getComposer();
                $sender = $repository->getSender();
                $message = $composer->createNewThreadBuilder()
                    ->setSubject("Demande de coaching")
                    ->setAuthor($user)
                    ->setContent($contenu)
                    ->addRecipient($advert->getAuthor())
                    ->getMessage();
                $sender->send($message);
            }
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Vous avez terminé votre formation, bravo !');
            return $this->redirect($this->generateUrl('edu_core_homepage'));
        }
        return $this->render('EduFormaBundle:Advert:finish.html.twig', array('advert' => $advert, 'form'   => $form->createView()));
    }

    public function showallAction()
    {
        $listfinish = array();
        $listfollow = array();
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $fadverts = $em->getRepository('EduUserBundle:Followadvert')->findBy(array('user' => $user));
        foreach ($fadverts as $fadvert){
            if ($fadvert->getFinished() == false)
                array_push($listfollow, $fadvert->getAdvert());
            else
                array_push($listfinish, $fadvert->getAdvert());
        }
        $listAuthor = $em->getRepository('EduFormaBundle:advert')->findBy(array('author' => $user));
        return $this->render('EduFormaBundle:Advert:myformation.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listfinish' => $listfinish,
            'listfollow' => $listfollow,
            'listAuthor' => $listAuthor
        ));
    }

    public function categorieAction($page, $recherche)
    {
        $list = array();
        $nbPerPage = 3;
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $listAdverts = $em
            ->getRepository('EduFormaBundle:Advert')->findBy(array('factory' => $user->getFactory()));
        /* ->getAdverts($page, $nbPerPage);*/
        foreach ($listAdverts as $advert) {
            foreach ($advert->getCategories() as $categorie) {
                if ($categorie->getName() == $recherche)
                    array_push($list, $advert);
            }
        }
        $nbPages = ceil(count($listAdverts)/$nbPerPage);
        if ($nbPages < $page & $page != 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        $content = $this->get('twig')->render('EduFormaBundle:Advert:categorie.html.twig', array(
            'listAdverts' => $list,
            'nbPages'     => $nbPages,
            'page'        => $page,
            'recherche' => $recherche));
        return new Response($content);
    }
}
