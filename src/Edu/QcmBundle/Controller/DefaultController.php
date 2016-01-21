<?php

namespace Edu\QcmBundle\Controller;

use Edu\FormaBundle\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Edu\FormaBundle\Entity\Advert;
use Edu\FormaBundle\Form\ContentQcmType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function addpageAction(Advert $advert, Request $request){
        $em = $this->getDoctrine()->getManager();
        $content = new Content;
        if ($this->get('security.token_storage')->getToken()->getUser()->getUsername() != $advert->getAuthor()) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité.');
        }
        $form = $this->get('form.factory')->create(new ContentQcmType, $content);
        if ($form->handleRequest($request)->isValid()) {
            $bouclecontent = $advert->getContentnew();
            while($bouclecontent->getSuite()){
                $bouclecontent = $bouclecontent->getSuite();
            }
            foreach ($content->getQuestionnaire() as $question){
                $question->setContent($content);
            }
            $bouclecontent->setSuite($content);
            $advert->increaseContent();
            $advert->increaseqcm();
            $em->persist($content);
            $em->flush();
            return $this->redirect($this->generateUrl('edu_forma_view', array('id' => $advert->getId())));
        }
        return $this->render('EduQcmBundle:Default:formqcm.html.twig', array(
            'form' => $form->createView()));
    }

    public function indexAction($name)
    {
        return $this->render('EduQcmBundle:Default:index.html.twig', array('name' => $name));
    }
}
