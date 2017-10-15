<?php

namespace Sandbox\FrontBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
        /* @var $session Session */
        $session = $this->get('session');
        $session->set('userName', 'John');
        dump($session);

        return $this->render('SandboxFrontBundle:Car:car.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/cars")
     */
    public function carListAction()
    {
//       $url = $this->generateUrl('sandbox_front_car_car', ['id' => 4]);
//       return $this->redirect($url);
//
//       return $this->forward('SandboxFrontBundle:Car:Car', ['id' => 3]);

       return $this->render('SandboxFrontBundle:Car:carList.html.twig');
    }
}