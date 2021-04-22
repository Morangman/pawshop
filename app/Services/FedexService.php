<?php

declare(strict_types = 1);

namespace App\Services;

use App\Order;
use FedEx\ShipService;
use FedEx\TrackService\Request;
use FedEx\ShipService\ComplexType;
use FedEx\ShipService\SimpleType;
use FedEx\TrackService\ComplexType\TrackRequest;
use FedEx\TrackService\ComplexType\TrackSelectionDetail;
use FedEx\TrackService\SimpleType\TrackIdentifierType;
use FedEx\TrackService\SimpleType\TrackRequestProcessingOptionType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

/**
 * Class FedexService
 *
 * @package App\Services
 */
class FedexService
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $accountNumber;

    /**
     * @var string
     */
    protected $meterNumber;

    /**
     * FedexService constructor.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $config = Config::get('services.fedex');

        $this->key = $config['key'];
        $this->password = Arr::get($config, 'password');
        $this->accountNumber = Arr::get($config, 'account_number');
        $this->meterNumber = Arr::get($config, 'meter_number');
    }

    /**
     * @param \App\Order $order
     *
     * @return array|string
     */
    public function ship(Order $order)
    {
        $userCredential = new ComplexType\WebAuthenticationCredential();
        $userCredential
            ->setKey($this->key)
            ->setPassword($this->password);

        $webAuthenticationDetail = new ComplexType\WebAuthenticationDetail();
        $webAuthenticationDetail->setUserCredential($userCredential);

        $clientDetail = new ComplexType\ClientDetail();
        $clientDetail
            ->setAccountNumber($this->accountNumber)
            ->setMeterNumber($this->meterNumber);

        $version = new ComplexType\VersionId();
        $version
            ->setMajor(26)
            ->setIntermediate(0)
            ->setMinor(0)
            ->setServiceId('ship');

        $shipperAddress = new ComplexType\Address();
        $address1 = $order->getAttribute('address')['address1'];
        $address2 = isset($order->getAttribute('address')['address2']) ? $order->getAttribute('address')['address2'] : null;
        $shipperAddress
            ->setStreetLines([$address1, $address2 ? $address2 : $address1])
            ->setCity($order->getAttribute('address')['city'])
            ->setStateOrProvinceCode(str_replace('string:', '', $order->getAttribute('address')['state']))
            ->setPostalCode($order->getAttribute('address')['postal_code'])
            ->setCountryCode('US');

        $shipperContact = new ComplexType\Contact();
        $shipperContact
            ->setEMailAddress($order->getAttribute('user_email'))
            ->setPersonName($order->getAttribute('address')['name'])
            ->setPhoneNumber(($order->getAttribute('address')['phone']));

        $shipper = new ComplexType\Party();
        $shipper
            ->setAccountNumber($this->accountNumber)
            ->setAddress($shipperAddress)
            ->setContact($shipperContact);

        $recipientAddress = new ComplexType\Address();
        $recipientAddress
            ->setStreetLines(['1730 E Warner Rd, Suite 7'])
            ->setCity('Tempe')
            ->setStateOrProvinceCode('AZ')
            ->setPostalCode('85284')
            ->setCountryCode('US');

        $recipientContact = new ComplexType\Contact();
        $recipientContact
            ->setPersonName('Contact Name')
            ->setPhoneNumber('14803168679');

        $recipient = new ComplexType\Party();
        $recipient
            ->setAddress($recipientAddress)
            ->setContact($recipientContact);

        $labelSpecification = new ComplexType\LabelSpecification();
        $labelSpecification
            ->setLabelStockType(new SimpleType\LabelStockType(SimpleType\LabelStockType::_PAPER_7X4POINT75))
            ->setImageType(new SimpleType\ShippingDocumentImageType(SimpleType\ShippingDocumentImageType::_PDF))
            ->setLabelFormatType(new SimpleType\LabelFormatType(SimpleType\LabelFormatType::_COMMON2D));

        $packageLineItem1 = new ComplexType\RequestedPackageLineItem();
        $packageLineItem1
            ->setSequenceNumber(1)
            ->setItemDescription('Product description')
            ->setDimensions(new ComplexType\Dimensions(array(
                'Width' => 1,
                'Height' => 1,
                'Length' => 1,
                'Units' => SimpleType\LinearUnits::_IN
            )))
            ->setWeight(new ComplexType\Weight(array(
                'Value' => 1,
                'Units' => SimpleType\WeightUnits::_LB
            )));

        $shippingChargesPayor = new ComplexType\Payor();
        $shippingChargesPayor->setResponsibleParty($shipper);

        $shippingChargesPayment = new ComplexType\Payment();
        $shippingChargesPayment
            ->setPaymentType(SimpleType\PaymentType::_SENDER)
            ->setPayor($shippingChargesPayor);

        $requestedShipment = new ComplexType\RequestedShipment();
        $requestedShipment->setShipTimestamp(date('c'));
        $requestedShipment->setDropoffType(new SimpleType\DropoffType(SimpleType\DropoffType::_REGULAR_PICKUP));
        $requestedShipment->setServiceType(new SimpleType\ServiceType(SimpleType\ServiceType::_FEDEX_GROUND));
        $requestedShipment->setPackagingType(new SimpleType\PackagingType(SimpleType\PackagingType::_YOUR_PACKAGING));
        $requestedShipment->setShipper($shipper);
        $requestedShipment->setRecipient($recipient);
        $requestedShipment->setLabelSpecification($labelSpecification);
        $requestedShipment->setRateRequestTypes(array(new SimpleType\RateRequestType(SimpleType\RateRequestType::_PREFERRED)));
        $requestedShipment->setPackageCount(1);
        $requestedShipment->setRequestedPackageLineItems([
            $packageLineItem1
        ]);
        $requestedShipment->setShippingChargesPayment($shippingChargesPayment);

        $processShipmentRequest = new ComplexType\ProcessShipmentRequest();
        $processShipmentRequest->setWebAuthenticationDetail($webAuthenticationDetail);
        $processShipmentRequest->setClientDetail($clientDetail);
        $processShipmentRequest->setVersion($version);
        $processShipmentRequest->setRequestedShipment($requestedShipment);

        $shipService = new ShipService\Request();
        if (env('FEDEX_KEY_IS_PROD', 0)) {
            $shipService->getSoapClient()->__setLocation(ShipService\Request::PRODUCTION_URL); //use production URL
        }

        $result = $shipService->getProcessShipmentReply($processShipmentRequest);

        $arrayResult = $result->toArray();

        // Save .pdf label
        //Storage::put('public/pdf/info.json', json_encode($result->toArray(), JSON_PRETTY_PRINT));

        if ($arrayResult['HighestSeverity'] === 'SUCCESS' || $arrayResult['HighestSeverity'] === 'NOTE') {
            //var_dump($result->CompletedShipmentDetail->CompletedPackageDetails[0]->Label->Parts[0]->Image);

            $order->update(['tracking_number' => (int)$arrayResult['CompletedShipmentDetail']['MasterTrackingId']['TrackingNumber'] ]);

            return $result->CompletedShipmentDetail->CompletedPackageDetails[0]->Label->Parts[0]->Image;
        } else {
            return [];
        }
    }

    /**
     * @param int $id
     *
     * @return array|string
     */
    public function track(int $id)
    {
        $trackRequest = new TrackRequest();

        // User Credential
        $trackRequest->WebAuthenticationDetail->UserCredential->Key = $this->key;
        $trackRequest->WebAuthenticationDetail->UserCredential->Password = $this->password;
        
        // Client Detail
        $trackRequest->ClientDetail->AccountNumber = $this->accountNumber;
        $trackRequest->ClientDetail->MeterNumber = $this->meterNumber;
        
        // Version
        $trackRequest->Version->ServiceId = 'trck';
        $trackRequest->Version->Major = 19;
        $trackRequest->Version->Intermediate = 0;
        $trackRequest->Version->Minor = 0;
        
        // Track 1 shipments TrackSelectionDetail
        $trackRequest->SelectionDetails = [new TrackSelectionDetail()];
        
        // For get all events TrackRequestProcessingOptionType
        //$trackRequest->ProcessingOptions = [TrackRequestProcessingOptionType::_INCLUDE_DETAILED_SCANS];
        
        // Track shipment 1
        $trackRequest->SelectionDetails[0]->PackageIdentifier->Value = $id;
        $trackRequest->SelectionDetails[0]->PackageIdentifier->Type = TrackIdentifierType::_TRACKING_NUMBER_OR_DOORTAG;

        $request = new Request();
        $trackReply = $request->getTrackReply($trackRequest);
        
        return $trackReply;
    }
}
