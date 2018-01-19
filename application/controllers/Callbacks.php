<?php 
class Callbacks extends MY_Controller
	{
		public function __construct()
			{
				parent::__construct();
			}
		public static function processB2BRequestCallback()
			{
		        $callbackJSONData 						=	file_get_contents('php://input');
		        $callbackData 							=	json_decode($callbackJSONData)->Result;
		        $resultCode 							=	$callbackData->ResultCode;
		        $resultDesc 							=	$callbackData->ResultDesc;
		        $originatorConversationID 				=	$callbackData->OriginatorConversationID;
		        $conversationID 						=	$callbackData->ConversationID;
		        $transactionID 							=	$callbackData->TransactionID;
		        $transactionReceipt						=	$callbackData->ResultParameters->ResultParameter[0]->Value;
		        $transactionAmount						=	$callbackData->ResultParameters->ResultParameter[1]->Value;
		        $b2CWorkingAccountAvailableFunds		=	$callbackData->ResultParameters->ResultParameter[2]->Value;
		        $b2CUtilityAccountAvailableFunds		=	$callbackData->ResultParameters->ResultParameter[3]->Value;
		        $transactionCompletedDateTime			=	$callbackData->ResultParameters->ResultParameter[4]->Value;
		        $receiverPartyPublicName				=	$callbackData->ResultParameters->ResultParameter[5]->Value;
		        $B2CChargesPaidAccountAvailableFunds	=	$callbackData->ResultParameters->ResultParameter[6]->Value;
		        $B2CRecipientIsRegisteredCustomer		=	$callbackData->ResultParameters->ResultParameter[7]->Value;


		        $result=array(
		          				"resultCode"							=>	$resultCode,
					          	"resultDesc"							=>	$resultDesc,
					            "originatorConversationID"				=>	$originatorConversationID,
					            "conversationID"						=>	$conversationID,
					            "transactionID"							=>	$transactionID,
					            "transactionReceipt"					=>	$transactionReceipt,
					            "transactionAmount"						=>	$transactionAmount,
					            "b2CWorkingAccountAvailableFunds"		=>	$b2CWorkingAccountAvailableFunds,
					            "b2CUtilityAccountAvailableFunds"		=>	$b2CUtilityAccountAvailableFunds,
					            "transactionCompletedDateTime"			=>	$transactionCompletedDateTime,
					            "receiverPartyPublicName"				=>	$receiverPartyPublicName,
					            "B2CChargesPaidAccountAvailableFunds"	=>	$B2CChargesPaidAccountAvailableFunds,
					            "B2CRecipientIsRegisteredCustomer"		=>	$B2CRecipientIsRegisteredCustomer
		        			);

		        return json_encode($result);
    		}
    	public static function processB2CRequestCallback()
    		{
			    $callbackJSONData=file_get_contents('php://input');
			    $callbackData=json_decode($callbackJSONData);
			    $resultCode=$callbackData->Result->ResultCode;
			    $resultDesc=$callbackData->Result->ResultDesc;
			    $originatorConversationID=$callbackData->Result->OriginatorConversationID;
			    $conversationID=$callbackData->Result->ConversationID;
			    $transactionID=$callbackData->Result->TransactionID;
			    $initiatorAccountCurrentBalance=$callbackData->Result->ResultParameters->ResultParameter[0]->Value;
			    $debitAccountCurrentBalance=$callbackData->Result->ResultParameters->ResultParameter[1]->Value;
			    $amount=$callbackData->Result->ResultParameters->ResultParameter[2]->Value;
			    $debitPartyAffectedAccountBalance=$callbackData->Result->ResultParameters->ResultParameter[3]->Value;
			    $transCompletedTime=$callbackData->Result->ResultParameters->ResultParameter[4]->Value;
			    $debitPartyCharges=$callbackData->Result->ResultParameters->ResultParameter[5]->Value;
			    $receiverPartyPublicName=$callbackData->Result->ResultParameters->ResultParameter[6]->Value;
			    $currency=$callbackData->Result->ResultParameters->ResultParameter[7]->Value;

			    $result=[
			        "resultCode"=>$resultCode,
			        "resultDesc"=>$resultDesc,
			        "originatorConversationID"=>$originatorConversationID,
			        "conversationID"=>$conversationID,
			        "transactionID"=>$transactionID,
			        "initiatorAccountCurrentBalance"=>$initiatorAccountCurrentBalance,
			        "debitAccountCurrentBalance"=>$debitAccountCurrentBalance,
			        "amount"=>$amount,
			        "debitPartyAffectedAccountBalance"=>$debitPartyAffectedAccountBalance,
			        "transCompletedTime"=>$transCompletedTime,
			        "debitPartyCharges"=>$debitPartyCharges,
			        "receiverPartyPublicName"=>$receiverPartyPublicName,
			        "currency"=>$currency
			    ];

                self::Logs("B2C.log",$result);
        		return json_encode($result);

    		}
    	public static function processC2BRequestValidation()
    		{
		        $callbackJSONData=file_get_contents('php://input');
		        $callbackData=json_decode($callbackJSONData);
		        $transactionType=$callbackData->TransactionType;
		        $transID=$callbackData->TransID;
		        $transTime=$callbackData->TransTime;
		        $transAmount=$callbackData->TransAmount;
		        $businessShortCode=$callbackData->BusinessShortCode;
		        $billRefNumber=$callbackData->BillRefNumber;
		        $invoiceNumber=$callbackData->InvoiceNumber;
		        $orgAccountBalance=$callbackData->OrgAccountBalance;
		        $thirdPartyTransID=$callbackData->ThirdPartyTransID;
		        $MSISDN=$callbackData->MSISDN;
		        $firstName=$callbackData->FirstName;
		        $middleName=$callbackData->MiddleName;
		        $lastName=$callbackData->LastName;

		        $result=[
		            $transTime=>$transTime,
		            $transAmount=>$transAmount,
		            $businessShortCode=>$businessShortCode,
		            $billRefNumber=>$billRefNumber,
		            $invoiceNumber=>$invoiceNumber,
		            $orgAccountBalance=>$orgAccountBalance,
		            $thirdPartyTransID=>$thirdPartyTransID,
		            $MSISDN=>$MSISDN,
		            $firstName=>$firstName,
		            $lastName=>$lastName,
		            $middleName=>$middleName,
		            $transID=>$transID,
		            $transactionType=>$transactionType

		        ];

		        return json_encode($result);
    		}    
    	public static function processC2BRequestConfirmation()
    		{
		        $callbackJSONData=file_get_contents('php://input');
		        $callbackData=json_decode($callbackJSONData);
		        $transactionType=$callbackData->TransactionType;
		        $transID=$callbackData->TransID;
		        $transTime=$callbackData->TransTime;
		        $transAmount=$callbackData->TransAmount;
		        $businessShortCode=$callbackData->BusinessShortCode;
		        $billRefNumber=$callbackData->BillRefNumber;
		        $invoiceNumber=$callbackData->InvoiceNumber;
		        $orgAccountBalance=$callbackData->OrgAccountBalance;
		        $thirdPartyTransID=$callbackData->ThirdPartyTransID;
		        $MSISDN=$callbackData->MSISDN;
		        $firstName=$callbackData->FirstName;
		        $middleName=$callbackData->MiddleName;
		        $lastName=$callbackData->LastName;

		        $result=[
		            $transTime=>$transTime,
		            $transAmount=>$transAmount,
		            $businessShortCode=>$businessShortCode,
		            $billRefNumber=>$billRefNumber,
		            $invoiceNumber=>$invoiceNumber,
		            $orgAccountBalance=>$orgAccountBalance,
		            $thirdPartyTransID=>$thirdPartyTransID,
		            $MSISDN=>$MSISDN,
		            $firstName=>$firstName,
		            $lastName=>$lastName,
		            $middleName=>$middleName,
		            $transID=>$transID,
		            $transactionType=>$transactionType

		        ];

		        return json_encode($result);
   			}
    	public static function processAccountBalanceRequestCallback()
    		{
		        $callbackJSONData=file_get_contents('php://input');
		        $callbackData=json_decode($callbackJSONData);
		        $resultType=$callbackData->Result->ResultType;
		        $resultCode=$callbackData->Result->ResultCode;
		        $resultDesc=$callbackData->Result->ResultDesc;
		        $originatorConversationID=$callbackData->Result->OriginatorConversationID;
		        $conversationID=$callbackData->Result->ConversationID;
		        $transactionID=$callbackData->Result->TransactionID;
		        $accountBalance=$callbackData->Result->ResultParameters->ResultParameter[0]->Value;
		        $BOCompletedTime=$callbackData->Result->ResultParameters->ResultParameter[1]->Value;

		        $result=[
		          "resultDesc"=>$resultDesc,
		          "resultCode"=>$resultCode,
		          "originatorConversationID"=>$originatorConversationID,
		          "conversationID"=>$conversationID,
		          "transactionID"=>$transactionID,
		          "accountBalance"=>$accountBalance,
		          "BOCompletedTime"=>$BOCompletedTime,
		          "resultType"=>$resultType
		        ];

		        return json_encode($result);


		    }
    	public static function processReversalRequestCallBack()
	    	{
		        $callbackJSONData=file_get_contents('php://input');
		        $callbackData=json_decode($callbackJSONData);
		        $resultType=$callbackData->Result->ResultType;
		        $resultCode=$callbackData->Result->ResultCode;
		        $resultDesc=$callbackData->Result->ResultDesc;
		        $originatorConversationID=$callbackData->Result->OriginatorConversationID;
		        $conversationID=$callbackData->Result->ConversationID;
		        $transactionID=$callbackData->Result->TransactionID;

		        $result=[
		          "resultType"=>$resultType,
		          "resultCode"=>$resultCode,
		          "resultDesc"=>$resultDesc,
		          "conversationID"=>$conversationID,
		          "transactionID"=>$transactionID,
		          "originatorConversationID"=>$originatorConversationID
		        ];

		        return json_encode($result);

	    	}
    	public static function processSTKPushRequestCallback()
	    	{
		        $callbackJSONData=file_get_contents('php://input');
		        $callbackData=json_decode($callbackJSONData);
		        $resultCode=$callbackData->stkCallback->ResultCode;
		        $resultDesc=$callbackData->stkCallback->ResultDesc;
		        $merchantRequestID=$callbackData->stkCallback->MerchantRequestID;
		        $checkoutRequestID=$callbackData->stkCallback->CheckoutRequestID;
		        $amount=$callbackData->stkCallback->CallbackMetadata->Item[0]->Value;
		        $mpesaReceiptNumber=$callbackData->stkCallback->CallbackMetadata->Item[1]->Value;
		        $balance=$callbackData->stkCallback->CallbackMetadata->Item[2]->Value;
		        $b2CUtilityAccountAvailableFunds=$callbackData->stkCallback->CallbackMetadata->Item[3]->Value;
		        $transactionDate=$callbackData->stkCallback->CallbackMetadata->Item[4]->Value;
		        $phoneNumber=$callbackData->stkCallback->CallbackMetadata->Item[5]->Value;

		        $result=[
		            "resultDesc"=>$resultDesc,
		            "resultCode"=>$resultCode,
		            "merchantRequestID"=>$merchantRequestID,
		            "checkoutRequestID"=>$checkoutRequestID,
		            "amount"=>$amount,
		            "mpesaReceiptNumber"=>$mpesaReceiptNumber,
		            "balance"=>$balance,
		            "b2CUtilityAccountAvailableFunds"=>$b2CUtilityAccountAvailableFunds,
		            "transactionDate"=>$transactionDate,
		            "phoneNumber"=>$phoneNumber
		        ];

		        return json_encode($result);
		    }
    	public static function processSTKPushQueryRequestCallback()
	    	{
		        $callbackJSONData 		=	file_get_contents('php://input');
		        $callbackData 			=	json_decode($callbackJSONData);
		        $responseCode 			=	$callbackData->ResponseCode;
		        $responseDescription 	=	$callbackData->ResponseDescription;
		        $merchantRequestID 		=	$callbackData->MerchantRequestID;
		        $checkoutRequestID 		=	$callbackData->CheckoutRequestID;
		        $resultCode 			=	$callbackData->ResultCode;
		        $resultDesc 			=	$callbackData->ResultDesc;

		        $result=[
		            		"resultCode" 			=>	$resultCode,
		            		"responseDescription" 	=>	$responseDescription,
		            		"responseCode" 			=>	$responseCode,
		            		"merchantRequestID" 	=>	$merchantRequestID,
		            		"checkoutRequestID" 	=> 	$checkoutRequestID,
	            			"resultDesc" 			=>	$resultDesc
		        		];

		        return json_encode($result);
		    }
    	public static function processTransactionStatusRequestCallback()
	    	{
		        $callbackJSONData=file_get_contents('php://input');
		        $callbackData=json_decode($callbackJSONData);
		        $resultCode=$callbackData->Result->ResultCode;
		        $resultDesc=$callbackData->Result->ResultDesc;
		        $originatorConversationID=$callbackData->Result->OriginatorConversationID;
		        $conversationID=$callbackData->Result->ConversationID;
		        $transactionID=$callbackData->Result->TransactionID;
		        $ReceiptNo=$callbackData->Result->ResultParameters->ResultParameter[0]->Value;
		        $ConversationID=$callbackData->Result->ResultParameters->ResultParameter[1]->Value;
		        $FinalisedTime=$callbackData->Result->ResultParameters->ResultParameter[2]->Value;
		        $Amount=$callbackData->Result->ResultParameters->ResultParameter[3]->Value;
		        $TransactionStatus=$callbackData->Result->ResultParameters->ResultParameter[4]->Value;
		        $ReasonType=$callbackData->Result->ResultParameters->ResultParameter[5]->Value;
		        $TransactionReason=$callbackData->Result->ResultParameters->ResultParameter[6]->Value;
		        $DebitPartyCharges=$callbackData->Result->ResultParameters->ResultParameter[7]->Value;
		        $DebitAccountType=$callbackData->Result->ResultParameters->ResultParameter[8]->Value;
		        $InitiatedTime=$callbackData->Result->ResultParameters->ResultParameter[9]->Value;
		        $OriginatorConversationID=$callbackData->Result->ResultParameters->ResultParameter[10]->Value;
		        $CreditPartyName=$callbackData->Result->ResultParameters->ResultParameter[11]->Value;
		        $DebitPartyName=$callbackData->Result->ResultParameters->ResultParameter[12]->Value;

		        $result=[
		            "resultCode"=>$resultCode,
		            "resultDesc"=>$resultDesc,
		            "originatorConversationID"=>$originatorConversationID,
		            "conversationID"=>$conversationID,
		            "transactionID"=>$transactionID,
		            "ReceiptNo"=>$ReceiptNo,
		            "ConversationID"=>$ConversationID,
		            "FinalisedTime"=>$FinalisedTime,
		            "Amount"=>$Amount,
		            "TransactionStatus"=>$TransactionStatus,
		            "ReasonType"=>$ReasonType,
		            "TransactionReason"=>$TransactionReason,
		            "DebitPartyCharges"=>$DebitPartyCharges,
		            "DebitAccountType"=>$DebitAccountType,
		            "InitiatedTime"=>$InitiatedTime,
		            "OriginatorConversationID"=>$OriginatorConversationID,
		            "CreditPartyName"=>$CreditPartyName,
		            "DebitPartyName"=>$DebitPartyName
		        ];

		        return json_encode($result);
		    }
        public function Logs($filename,$content)
            {
                $data= "\n".date("h:i:sa d,m,Y")."\t".$content;
                write_file(APPPATH.'logs/'.$filename, $data, 'a+');
            }
	}