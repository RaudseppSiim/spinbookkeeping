<?php
require PROJECT_ROOT_PATH . "helper/DateHelper.php";
class PaymentController extends BaseController
{
    use DateHelper;
    /** 
    * "/payment/year" Endpoint - Get year payment dates 
    */
    public function yearAction()
    {
        try{
            $validation = $this->validateRequest("GET", $_SERVER["REQUEST_METHOD"]);
            if($validation != true){
                return $validationM;
            }
            $params = $this->getQueryStringParams();

            if(!isset($params['year'])){
                    throw new Exception("Palun sisestage päringusse aasta number");
            }
            
            if($this->isValidYear($params['year'])){
                $returnArray=array();
                for($month = 1; $month<=12; $month++){
                    $monthPayDay = $this->getPaymentDate(PAYMENT_DATE, str_pad($month,2,"0",STR_PAD_LEFT), $params['year']);
                    if(!$monthPayDay){
                        throw new Exception("Ei õnnestunud luua kuu ".$month." palga kuupäeva");
                    }
                    $monthNothificationDay = date('Y-m-d', strtotime($monthPayDay. ' - '.NOTIFICATION_DAY_BEFORE.' days'));
                    $returnArray[] = array(
                        strtolower($this->getAllMonthNamesArray()[$month-1]) => 
                            array(
                                "paymentDate" => $monthPayDay,
                                "notificationDate" => $monthNothificationDay
                            )
                        );
                }
                return array(
                    json_encode(array(
                        "status"=>"success",
                        "data"=>array(
                            "year"=>$params['year'],
                            "dates" => $returnArray
                        ))
                    ), array('Content-Type: application/json', 'HTTP/1.1 200 Ok')
                );
            }
            else{
                throw new Exception("Sisestatud aastanumber ei vasta nõuetele");
            }
        }
        catch(Exception $e){
            return array(
                json_encode(array("status"=>"error","error"=>$e->getMessage())),
                array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request')
            );
        }
    }
}