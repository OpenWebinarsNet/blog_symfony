<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $request->setLocale('es');
        return $this->render(
            'default/index.html.twig',
            array(
                'base_dir' => 'Symfony'
            )
        );
    }

    /**
     * @Route("/template/bootstrap", name="template")
     */
    public function templateAction(Request $request)
    {
        return $this->render(
            'layout.bootstrap.html.twig',
            array(
            )
        );
    }
}
