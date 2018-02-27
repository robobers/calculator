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

  //initilize logger in constructor
  public function __construct(LoggerInterface $logger)
  {
      $this->logger = $logger;
  }


  //default route
  /**
  * @Route("/")
  **/
  public function index(){

    return new Response('Index');
  }

  //calculator index
  /**
  * @Route("/calculate")
  **/
  public function action(){
    return $this->render('default/action.html.twig');
  }

  //calculator post, calculates given value
  //logs the input and also the output also for errors at ~/var/log/dev.log
  /**
  * @Route("/eval/{value}", name="eval", requirements={"value"=".+"})
  **/
  public function eval($value){

     $result = "";
     try {

       $this->writeLog($value,"debug");
       $result = $this->mathParser($value);
       $this->writeLog($result,"info");

     }
     catch(\Exception  $ex){
        $log =$ex->getMessage();
        $result = "Error";
        $this->writeLog($log,"error");

     }
     return $this->render('default/postacion.html.twig', [
       'result' => $result,
    ]);

  }

 //logs the value with logtype
  private function writeLog($val, $type){
       switch ($type) {
         case 'debug':
           $this->logger->info("Given Value: $val");
           break;
         case 'info':
           $this->logger->info("Result: $val");
           break;
         case 'error':
           $this->logger->error("Error: $val");
           break;
         default:
            $this->logger->critical("Critical something went Wrong: $val");
           break;
       }

  }

  //parse and evaluates expression
  private function mathParser($value){

    $parser = new StdMathParser();
    $AST = $parser->parse($value);
    $evaluator = new Evaluator();
    $result = $AST->accept($evaluator);
    return $result;

  }


}
