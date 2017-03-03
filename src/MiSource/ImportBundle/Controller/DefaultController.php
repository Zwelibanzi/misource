<?php

namespace MiSource\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $this->get('logger.stamp')->write('loadfadfdfgin');
        return $this->render('ImportBundle:Default:index.html.twig');
    }
}
