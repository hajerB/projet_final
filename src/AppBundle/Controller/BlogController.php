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

class BlogController extends Controller
{
	    /**
     * @Route("/admin/ajout", name="ajout")
     */
    public function ajoutAction(Request $request)
    {
        $blog=new Blog();
        $form = $this->createForm(BlogType::class,$blog);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $blog->setPublishedAt(new \DateTime());
            $blog->setPublished(false);
            $em=$this->get('doctrine.orm.entity_manager');
            $em->persist($blog);
            $em->flush();


            $messgae=sprintf('Contact ajouté avec succes');
            $this->addFlash('success',$messgae); // message de remerciement
            return $this->redirectToRoute('list'); // redirection vers une autre page

        }

        return $this->render('default/ajout.html.twig',['form'=>$form->createView()]);
    }


    /**
//     * @Route("/admin/list/remove/{id}", name="remove")
     */
    public function listDetails(Request $request,$id)
    {
        $em=$this->get('doctrine.orm.entity_manager');
        $repository=$em->getRepository(Blog::class);
        $blogs=$repository->findAll();
        $repository=$em->getRepository(Commentaire::class);
        $commentaire=$repository->find($id);
        $em->remove($commentaire);
        $em->flush();

        return $this->render('default/list.html.twig',['blogs'=>$blogs]);
    }
    /**
     * @Route("/admin/list/details/{id}", name="detail")
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
            return $this->redirectToRoute('detail',['id'=>$id]); // redirection vers une autre page
        }


        return $this->render('default/detail.html.twig',array('blog'=>$blog,'commentaires'=>$commentaires,'form'=>$form->createView()));

    }

    /**
     * @Route("/admin/blog/details/{id}/mark", name="mark")
     */
    public function markAction(Blog $blog)
    {
        if($blog->getPublished()){
            $this->addFlash('error','this contact already ');
        }
        else {
            $blog->setPublished(true);
            $this->addFlash('success','this contact has been marked as..');
            $em=$this->get('doctrine.orm.entity_manager');
            $em->flush();
        }
        return $this->redirectToRoute('list',['id'=>$blog->getId()]);
    }


	
 /**
 * @Route("/admin/list", name="list")
 */
    public function listAction(Request $request)
    {
        $em=$this->get('doctrine.orm.entity_manager');
        $repository=$em->getRepository(Blog::class);
        $blogs=$repository->findAll();
        return $this->render('default/list.html.twig',['blogs'=>$blogs]);
    }


}