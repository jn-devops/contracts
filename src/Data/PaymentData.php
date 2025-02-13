<?php

namespace Homeful\Contracts\Data;

use Spatie\LaravelData\Data;

class PaymentData extends Data
{
    public function __construct(
        public string $code,
        public PaymentPayloadData $data,
        public string $message,
    ){}
}

class PaymentPayloadData extends Data
{
    public function __construct(
        public PaymentOrderInformationdData $orderInformation,
    ){}
}

class PaymentOrderInformationdData extends Data
{
    public function __construct(
      public int $qrTag,
      public int $amount,
      public string $attach,
      public int $tipFee,
      public string $orderId,
      public string $currency,
      public int $surcharge,
      public string $goodsDetail,
      public int $orderAmount,
      public string $paymentType,
      public string $paymentBrand,
      public string $referencedId,
      public string $responseDate,
      public string $transactionResult
    ){}
}
