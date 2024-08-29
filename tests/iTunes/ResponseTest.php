<?php

use ReceiptValidator\iTunes\Response;

/**
 * @group library
 */
class iTunesResponseTest extends \PHPUnit\Framework\TestCase
{

    /**
     *
     */
    public function testInvalidOptionsToConstructor()
    {
        $this->expectException("ReceiptValidator\\RuntimeException");

        new Response('invalid');
    }

    /**
     *
     */
    public function testInvalidReceipt()
    {
        $response = new Response(array('status' => Response::RESULT_DATA_MALFORMED, 'receipt' => array()));

        $this->assertInstanceOf('ReceiptValidator\SubscriptionInterface', $response);
        $this->assertFalse($response->isValid(), 'receipt must be invalid');
        $this->assertEquals(Response::RESULT_DATA_MALFORMED, $response->getResultCode(),
            'receipt result code must match');

        $response = new Response(array('status' => Response::RESULT_OK));

        $this->assertFalse($response->isValid(), 'receipt must be invalid');
    }

    /**
     *
     */
    public function testReceiptSentToWrongEndpoint()
    {
        $response = new Response(array('status' => Response::RESULT_SANDBOX_RECEIPT_SENT_TO_PRODUCTION));

        $this->assertFalse($response->isValid(), 'receipt must be invalid');
        $this->assertEquals(Response::RESULT_SANDBOX_RECEIPT_SENT_TO_PRODUCTION, $response->getResultCode(),
            'receipt result code must match');
    }

    /**
     *
     */
    public function testValidReceipt()
    {
        $response = new Response(array('status' => Response::RESULT_OK, 'receipt' => array('testValue')));

        $this->assertTrue($response->isValid(), 'receipt must be valid');
        $this->assertEquals(Response::RESULT_OK, $response->getResultCode(), 'receipt result code must match');
    }

    /**
     *
     */
    public function testSubscriptionReceiptWithLatestReceiptInfo()
    {
        $jsonResponseString = file_get_contents(__DIR__ . '/fixtures/SubscriptionResponseProduction.json');
        $jsonResponseArray = json_decode($jsonResponseString, true);

        $response = new Response($jsonResponseArray);

        $this->assertInternalType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $response->getLatestReceiptInfo());
        $this->assertEquals($jsonResponseArray['latest_receipt_info'], $response->getLatestReceiptInfo(),
            'latest receipt info must match');

        $this->assertInternalType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $response->getLatestReceipt());
        $this->assertEquals($jsonResponseArray['latest_receipt'], $response->getLatestReceipt(),
            'latest receipt must match');

        $this->assertInternalType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $response->getBundleId());
        $this->assertEquals($jsonResponseArray['receipt']['bundle_id'], $response->getBundleId(),
            'receipt bundle id must match');
        $this->assertEquals($jsonResponseArray['receipt']['app_item_id'], $response->getAppItemId(),
            'receipt app item id must match');
        $this->assertEquals('30000789813124',
            $response->getTransactionId(), 'receipt transaction id must match');
        $this->assertEquals('30000757253823',
            $response->getOriginalTransactionId(), 'receipt original transaction id must match');
        $this->assertEquals('Subscription_MedicineFlat_1Month', $response->getProductId(),
            'receipt product id must match');
        $this->assertEquals('1595860505000',
            $response->getExpiresDate(), 'receipt expires date must match');
        $this->assertEquals($jsonResponseArray, $response->getRawResponse(), 'original receipt');
    }

    public function testPurchaseReceiptWithLatestReceiptInfo()
    {
        $jsonResponseString = file_get_contents(__DIR__ . '/fixtures/PurchaseResponseProduction.json');
        $jsonResponseArray = json_decode($jsonResponseString, true);

        $response = new Response($jsonResponseArray);

        $this->assertInternalType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $jsonResponseArray['receipt']['in_app']);

        $this->assertInternalType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $response->getLatestReceipt());
        $this->assertEquals($jsonResponseArray['latest_receipt'], $response->getLatestReceipt(),
            'latest receipt must match');

        $this->assertInternalType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $response->getBundleId());
        $this->assertEquals($jsonResponseArray['receipt']['bundle_id'], $response->getBundleId(),
            'receipt bundle id must match');
        $this->assertEquals($jsonResponseArray['receipt']['app_item_id'], $response->getAppItemId(),
            'receipt app item id must match');
        $this->assertEquals('30000789813124',
            $response->getTransactionId(), 'receipt transaction id must match');
        $this->assertEquals('30000757253823',
            $response->getOriginalTransactionId(), 'receipt original transaction id must match');
        $this->assertEquals('Subscription_MedicineFlat_1Month', $response->getProductId(),
            'receipt product id must match');
        $this->assertEquals('1595860505000',
            $response->getExpiresDate(), 'receipt expires date must match');
        $this->assertEquals($jsonResponseArray, $response->getRawResponse(), 'original receipt');
    }

}
