<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use AppBundle\Services\SendMail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Transition;

class PostController extends Controller
{
    /**
     * @Route("/post/list", name="list")
     */
    public function listAction(Request $request)
    {
        $postRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Post');

        $posts = $postRepository->findAll();

        $name = 'Lista';
        $translator = $this->get('translator');
        $title = $translator->trans(
            'Hello %name%',
            array('%name%' => $name)
        );

        $request->setLocale('en_US');
        $locale = $request->getLocale();

        $title = 'Post List';
        $number = 1;
        return $this->render('post/list.html.twig', array(
            'locale' => $locale,
            'count' => $number,
            'title' => $title,
            'posts' => $posts
        ));
    }

    /**
     * @Route("/post/search/{title}", name="seach")
     */
    public function searchAction(Request $request,$title)
    {
        $postRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Post');

        $posts = $postRepository->findAllByTitle($title);

        print_r($posts);

    }

    /**
     * @Route("/post/new", name="post_new")
     */
    public function newAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $post = new Post();

        $form = $this->createForm(PostType::class, $post, array());

        $form->handleRequest($request);

        $formData = $form->getData();
        $date = $form['dueDate']->getData();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Se ha creado un nuevo objeto.'
            );

            return $this->redirect($this->generateUrl('list'));
        }

        return $this->render('post/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/post/{id}", name="post_view")
     */
    public function viewAction(Request $request,$id)
    {
        $postRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Post');

        $post = $postRepository->find($id);

        return new Response('Post whit slug '.$post->getSlug());
    }

    /**
     * @Route("/post/edit/{id}", name="post_edit")
     */
    public function editAction(Request $request,$id)
    {
        $postRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Post');

        $post = $postRepository->find($id);
        
        $form = $this->createForm(PostType::class, $post, array());

        $form->handleRequest($request);

        $formData = $form->getData();
        $date = $form['dueDate']->getData();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            //Envío de email
            /** @var SendMail $mailer */
            $mailer = $this->get('my_mailer');
            $mailer->sendEmailAction();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Se han guardado los cambios.'
            );

            return $this->redirect($this->generateUrl('list'));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }
}
