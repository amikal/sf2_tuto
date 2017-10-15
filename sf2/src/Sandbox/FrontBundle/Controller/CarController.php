<?php

namespace Sandbox\FrontBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Car
 * @package Sandbox\FrontBundle\Controller
 */
class CarController extends Controller
{
    /**
     * @Route("/car/{id}")
     */
    public function carAction($id)
    {
        return $this->render('SandboxFrontBundle:Car:car.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/cars")
     */
    public function carListAction()
    {
        return $this->render('SandboxFrontBundle:Car:carList.html.twig');
    }
}