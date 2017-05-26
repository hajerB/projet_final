<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commentaire;
use AppBundle\Form\Type\ContactType;
use AppBundle\Entity\Blog;
use AppBundle\Form\Type\BlogType;
use AppBundle\Form\Type\CommentaireType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{


    /**
    //     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('default/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    /**
     * @Route("/list/detailsUser/{id}", name="detailUser")
     */
    public function listDetailsAction(Request $request,$id)
    {
        $em=$this->get('doctrine.orm.entity_manager');
        $repository=$em->getRepository(Blog::class);
        $blog=$repository->find($id);
        $em=$this->get('doctrine.orm.entity_manager');
        $repository=$em->getRepository(Commentaire::class);
        $commentaires=$repository->findBy(array('blog' => $blog));
        $commentaire=new Commentaire();
        $form = $this->createForm(CommentaireType::class,$commentaire);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $commentaire->setBlog($blog);
            $em=$this->get('doctrine.orm.entity_manager');
            $em->persist($commentaire);
            $em->flush();
            $message=sprintf('Commentaire ajouté avec succes');
            $this->addFlash('success',$message); // message de remerciement
            return $this->redirectToRoute('detailUser',['id'=>$id]); // redirection vers une autre page
        }


        return $this->render('user/detail.html.twig',array('blog'=>$blog,'commentaires'=>$commentaires,'form'=>$form->createView()));

    }
	    /**
     * @Route("/", name="home")
     */
    public function listAction(Request $request)
    {
        $em=$this->get('doctrine.orm.entity_manager');
        $repository=$em->getRepository(Blog::class);
        $blogs=$repository->findAll();
        return $this->render('user/list.html.twig',['blogs'=>$blogs]);
    }

}
