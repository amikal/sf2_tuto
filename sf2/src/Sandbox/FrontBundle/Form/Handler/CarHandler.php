<?php

namespace Sandbox\FrontBundle\Form\Handler;

use Sandbox\BackBundle\Services\CarManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var int
     */
    public $errors;


    /**
     * CarHandler constructor.
     * @param Form $form
     * @param Request $request
     * @param CarManager $manager
     * @param ValidatorInterface $validator
     */
    public function __construct(Form $form, Request $request, CarManager $manager, ValidatorInterface $validator)
    {
        $this->form = $form;
        $this->request = $request;
        $this->manager = $manager;
        $this->validator = $validator;
        $this->errors = 0;
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
    public function createView()
    {
        return $this->form->createView();
    }

    /**
     * @return bool
     */
    public function doProcess()
    {
        $this->form->handleRequest($this->request);



        if ($this->request->isMethod('post')) {

            if (count($this->validate()) > 0) {
                return false;
            }

            $this->onSuccess();

            return true;
        }

        return false;
    }

    /**
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected function validate()
    {
        return $this->errors = $this->validator->validate($this->form->getData());
    }

    /**
     * @return int
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     */
    protected function onSuccess()
    {
        $this->manager->persist($this->form->getData());
    }
}