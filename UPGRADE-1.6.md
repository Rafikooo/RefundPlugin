### UPGRADE FROM 1.5.X TO 1.6.0

1. Support for Sylius 1.12 has been dropped, upgrade your application to [Sylius 1.13](https://github.com/Sylius/Sylius/blob/1.13/UPGRADE-1.13.md).
   or to [Sylius 1.14](https://github.com/Sylius/Sylius/blob/1.14/UPGRADE-1.14.md).

1. Passing an instance of `SM\Factory\FactoryInterface` as a constructor argument to the below classes has been deprecated 
   and will not be possible in RefundPlugin 2.0. Pass an instance of `Sylius\Abstraction\StateMachine\StateMachineInterface`.

   Applies to classes:
   - `Sylius\RefundPlugin\StateResolver\OrderFullyRefundedStateResolver`
   - `Sylius\RefundPlugin\StateResolver\OrderPartiallyRefundedStateResolver`
   - `Sylius\RefundPlugin\StateResolver\RefundPaymentCompletedStateApplier`
