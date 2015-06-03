<?php

namespace C3\BudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('C3BudgetBundle:Default:index.html.twig', array('name' => $name));
    }
}
