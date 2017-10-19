<?php

namespace Sandbox\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class CarController extends Controller
{
    /**
     * Collection get action
     * @var Request $request
     *
     */
    public function getCarsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $cars = $em->getRepository('SandboxBackBundle:Car')->findAll();

        // Création d'une vue FOSRestBundle
        $view = View::create($cars);
        $view->setFormat('json');

        return $view;
    }

    /**
     * Collection post action
     * @var Request $request
     */
    public function postCarAction()
    {
        $carFormHandler = $this->get('car_handler');

        dump($carFormHandler);
        //die();

        if($carFormHandler->process()) {
            return $this->redirect($this->generateUrl('sandbox_front_car_carlist'));
        }

        dump($carFormHandler->getErrors());

        return $this->render('SandboxFrontBundle:Car:create.html.twig', ['form' => $carFormHandler->createView(), 'errors' => $carFormHandler->getErrors()]);

//        return array(
//            'form' => $carFormHandler->createView(),
//        );
    }
}
