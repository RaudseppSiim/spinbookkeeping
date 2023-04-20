<?php declare(strict_types=1);
require("./vendor/autoload.php");
require("index.test.php");
use PHPUnit\Framework\TestCase;

final class PaymentControllerTest extends TestCase
{
    public function testSubmittedYearIsValid(): void
    {
        $year = 2000;
        $paymentController = new PaymentController();
        $validation = $paymentController->isValidYear($year);

        $this->assertSame(true, $validation);
    }

    public function testDidNotSubmitYear(): void
    {
        $paymentController = new PaymentController();
        $result = $paymentController->yearAction();

        $this->assertSame('{"status":"error","error":"Palun sisestage p\u00e4ringusse aasta number"}',  $result[0]);
    }

    public function testSundayIsNotPayday(): void
    {
        $paymentController = new PaymentController();
        $result = $paymentController->getPaymentDate(16,4, 2023);

        $this->assertSame(date('Y-m-d', strtotime("14-04-2023")),  $result);
    }

    public function testChristmasOnSundayHasFridayAsPayday(): void
    {
        $paymentController = new PaymentController();
        $result = $paymentController->getPaymentDate(24,12, 2023);

        $this->assertSame(date('Y-m-d', strtotime("22-12-2023")),  $result);
    }
}
