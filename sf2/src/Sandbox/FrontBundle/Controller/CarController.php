<?php

namespace Sandbox\FrontBundle\Controller;


use Doctrine\ORM\EntityNotFoundException;
use Sandbox\FrontBundle\Form\Handler\CarHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse as JsR;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Car
 * @package Sandbox\FrontBundle\Controller
 */
class CarController extends Controller
{
    /**
     * @Route("/car/create")
     */
    public function createAction()
    {
        /* @var $carFormHandler CarHandler */
        $carFormHandler = $this->get('car_handler');

        if($carFormHandler->doProcess()) {
            return $this->redirect($this->generateUrl('sandbox_front_car_carlist'));
        }

        dump($carFormHandler->getErrors());

        return $this->render('SandboxFrontBundle:Car:create.html.twig', ['form' => $carFormHandler->createView(), 'errors' => $carFormHandler->getErrors()]);
    }

    /**
     * @Route("/car/list")
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

//       $repository = $this->getDoctrine()->getManager()->getRepository('SandboxBackBundle:Car');
//       $cars = $repository->getAll();

       $cars = $this->get('car_manager')->getAll();
       dump($cars);

       return $this->render('SandboxFrontBundle:Car:list.html.twig', ['cars' => $cars]);
    }

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

//        $repository = $this->getDoctrine()->getManager()->getRepository('SandboxBackBundle:Car');
//        $car = $repository->find($id);

        $car = $this->get('car_manager')->find($id);

        if (!$car) {
            throw new EntityNotFoundException();
        }

        return $this->render('SandboxFrontBundle:Car:show.html.twig', ['car' => $car]);
    }

    /**
     * @Route("/find", options = { "expose" = true }, name = "find_car_ajax")
     */
    public function findAction(Request $request)
    {
        $cars = $this->get('car_manager')->ajaxFindCar($request);
        return new JsR($cars);
    }

    /**
     * @Route("/get/{ids}", options = { "expose" = true }, name = "get_car_ajax")
     */
    public function getAction($ids)
    {
        $expr = new Expr();
        $ids = explode(',', $ids);

        $cars = $this->get('car_manager')->ajaxGetCar($ids);
        return new JsR($cars);
    }

}