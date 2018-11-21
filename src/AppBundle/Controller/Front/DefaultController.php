<?php

namespace AppBundle\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Route("/hello/{page}.{_format}", name="app_default_hello",
     *      defaults={"page" = 1, "_format" = "html"},
     *      requirements={"page" = "\d+"}
     * )
     */

    public function helloAction(Request $request, $page, $_format)
    {
       // $page = (int) $request->get("page", 1);

        if('json' == $_format){
            return new JsonResponse(['page' => $page]);
        }
        
        $viewParams = [
          'page' => $page,
          'name' => 'ala',
          'tablica' => [1,2,3,4,5,6,],
          'tablica2' => [],
          'komentarz' => 'ala <strong>ma kota</strong>',
        ];

        return $this->render("front/default/hello.html.twig", $viewParams);
       // return new Response("Ala ma kota {$page} lkdlkdl {$_format}" );
    }

    /**
     * @Route("/old-homepage", name="homepage")
     */
    public function indexAction(Request $request)
    {


        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    //@Security("has_role('ROLE_ADMIN')")
    /**
     * @param Request $request
     * @Route("/admin/firewall-test", name="app_front_default_firewall-test")
     *
     */
    public function firewallTestAction(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        return $this->render("front/default/firewall-test.html.twig");
    }
}
