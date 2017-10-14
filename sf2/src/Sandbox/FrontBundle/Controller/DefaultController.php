<?php

namespace Sandbox\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/{slug}")
     */
    public function indexAction($slug)
    {
        return $this->render('SandboxFrontBundle:Default:index.html.twig', ['slug' => $slug]);
    }
}
