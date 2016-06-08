<?php

namespace Powker4Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Powker4Controller extends Controller
{
    public function indexAction()
    {
        return $this->render('Powker4Bundle:Powker4:index.html.twig');
    }
}
