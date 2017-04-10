<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/serviceuser", name="serviceuser")
     */
    public function indexAction(Request $request)
    {
        $templateName = 'serviceuser';
        $argsArray = [
            'name' => 'matt'
        ];
        return $this->render($templateName . '.html.twig', $argsArray);
    }

    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

}


