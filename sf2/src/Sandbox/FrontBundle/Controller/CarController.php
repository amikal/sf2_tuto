<?php

namespace Sandbox\FrontBundle\Controller;


use Doctrine\ORM\EntityNotFoundException;
use Sandbox\BackBundle\Entity\Car;
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
        //dump($session);

//        $session->getFlashBag()->add('errors_info', 'no_error_here');
//        $session->getFlashBag()->add('errors_info', 'no_error_here2');
//        $session->getFlashBag()->add('errors_info', 'no_error_here3');

        $repository = $this->getDoctrine()->getManager()->getRepository('SandboxBackBundle:Car');
        $car = $repository->find($id);

        if (!$car) {
            throw new EntityNotFoundException();
        }

        return $this->render('SandboxFrontBundle:Car:car.html.twig', ['car' => $car]);
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

//       $em = $this->getDoctrine()->getManager();
//       $car = new Car();
//       $car->setName('Twingo');
//       $car->setMarque('Renault');
//       $car->setType('citadine');
//       $car->setCreated(new \DateTime('now'));
//
//       $em->persist($car);
//       $em->flush();

       $repository = $this->getDoctrine()->getManager()->getRepository('SandboxBackBundle:Car');
       $cars = $repository->getAll();
       dump($cars);

       return $this->render('SandboxFrontBundle:Car:carList.html.twig');
    }
}