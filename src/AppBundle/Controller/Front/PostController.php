<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\CommentType;
use AppBundle\Service\Mailer;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{

    /**
     * @param Request $request
     * @Route("/", name="app_front_post_list")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
//1 sposob
        $posts = $em->getRepository(Post::class)->findAll();
//2 sposob
        $posts = $em->getRepository(Post::class)
                    ->findByStatus(1);

        $posts = $em->getRepository(Post::class)
            ->findBy(
                ['status' =>10],
                ['createdAt' => 'DESC']
            );

        $posts = $em->getRepository(Post::class)->find(1);
//3 sposob DQL
        $posts = $em->createQuery("
        SELECT post
        FROM AppBundle:Post post
        WHERE post.status = :status
        ORDER BY post.createdAt DESC
        ")
        ->setParameter("status", 20)
            ->setMaxResults(2) //limit
        ->getResult();

        //4 sposob           ;
        $posts = $em->getRepository(Post::class)
            ->createQueryBuilder("post")
            ->where("post.status = :status")
            ->orderBy("post.createdAt","DESC")
            ->setParameter("status",20)
            ->getQuery()
            ->getResult()
            ;
        //5 wlasna metoda w repository
        $posts = $em->getRepository(Post::class)
            ->wezWszystkie();

        $pagination = $this->get('knp_paginator')
            ->paginate(
              $posts,
              $request->get('page',1)
            );

        return $this->render("front/post/list.html.twig", ['pagination' => $pagination,
            ]);
    }

    /**
     * @param Request $request
     * @Route("/post/{slug}", name="app_front_post_show")
     */

    public function showAction(Request $request, $slug, ObjectManager $em, Post $post, Mailer $mailer)
    {
       // $em = $this->getDoctrine()->getManager();
//        $post = $em->getRepository(Post::class)
//                   ->findOneBySlug($slug);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setIp($request->getClientIp());
            $comment->setPost($post);

            $em->persist($comment);
            $em->flush();

            $mailer->sendConfirmationEmail($comment->getEmail());

            return $this->redirectToRoute("app_front_post_show", [
               'slug' => $post->getSlug(),
            ]);
        }



        return $this->render('front/post/show.html.twig', [
            'post' => $post,
            'form' =>$form->createView()
        ]);
    }
}
