<?php

/**
 * Description of DefaultController
 *
 * @author ppd
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        echo $this->render('Default/index.html.twig', ['welcome' => 'hello word']);
    }
}
