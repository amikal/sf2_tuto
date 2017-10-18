<?php

namespace Sandbox\FrontBundle\Form\Handler;

use Sandbox\BackBundle\Services\CarManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CarHandler
 * @package Sandbox\FrontBundle\Form\Handler
 */
class CarHandler
{
    /**
     * @var Form
     */
    protected $form;
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var CarManager
     */
    protected $manager;


    /**
     * CarHandler constructor.
     * @param Form $form
     * @param Request $request
     * @param CarManager $manager
     */
    public function __construct(Form $form, Request $request, CarManager $manager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->manager = $manager;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getView()
    {
        return $this->form->createView();
    }

    /**
     * @return bool
     */
    public function process()
    {
        $this->form->handleRequest($this->request);
        if ($this->request->isMethod('post') && $this->form->isValid()) {
            $this->onSuccess();

            return true;
        }

        return false;
    }

    /**
     *
     */
    protected function onSuccess()
    {
        $this->manager->persist($this->form->getData());
    }
}