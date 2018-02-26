<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use MathParser\StdMathParser;
use MathParser\Interpreting\Evaluator;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Psr\Log\LoggerInterface;




class DefaultController extends AbstractController
{

  private $logger;

  public function __construct(LoggerInterface $logger)
  {
      $this->logger = $logger;
  }



  /**
  * @Route("/")
  **/
  public function index(){

    return new Response('Index');
  }

  /**
  * @Route("/calculate")
  **/
  public function action(){
    return $this->render('default/action.html.twig');
  }


  /**
       * @Route("/share/{token}", name="share", requirements={"token"=".+"})
       */
      public function share($token)
      {
         $this->logger->info("token: $token");

         return new Response("res : $token");

      }



  /**
  * @Route("/eval/{value}", name="eval", requirements={"value"=".+"})
  **/
  public function eval($value){

     $result = "";
     try {
       $this->logger->info("Given Value: $value");
       $parser = new StdMathParser();
       $AST = $parser->parse($value);
       $evaluator = new Evaluator();
       $result = $AST->accept($evaluator);
       $this->logger->info("Result Value: $result");

     }
     catch(\Exception  $ex){
        $log =$ex->getMessage();
        $result = "Error";
        $this->logger->error("Result Value:  $log");

     }
     return $this->render('default/postacion.html.twig', [
       'result' => $result,
    ]);

       //return new Response("Result : $result");
  }


}
