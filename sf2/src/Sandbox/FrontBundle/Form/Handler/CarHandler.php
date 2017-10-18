<?php

namespace Sandbox\FrontBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class CarHandler
{
    protected $form;
    protected $request;

    public function __construct(Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function process()
    {
        $this->form->handleRequest($this->request);
        if ($this->request->isMethod('post') && $this->form->isValid()) {
            return true;
        }

        return false;
    }

    public function onSuccess()
    {

    }
}