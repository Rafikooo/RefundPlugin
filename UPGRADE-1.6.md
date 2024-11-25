### UPGRADE FROM 1.5.X TO 1.6.0

1. Support for Sylius 1.12 has been dropped, upgrade your application to [Sylius 1.13](https://github.com/Sylius/Sylius/blob/1.13/UPGRADE-1.13.md).
   or to [Sylius 1.14](https://github.com/Sylius/Sylius/blob/1.14/UPGRADE-1.14.md).

1. Passing an instance of `SM\Factory\FactoryInterface` as a constructor argument to the below classes has been deprecated 
   and will not be possible in RefundPlugin 2.0. Pass an instance of `Sylius\Abstraction\StateMachine\StateMachineInterface`.

   Applies to classes:
   - `Sylius\RefundPlugin\StateResolver\OrderFullyRefundedStateResolver`
   - `Sylius\RefundPlugin\StateResolver\OrderPartiallyRefundedStateResolver`
   - `Sylius\RefundPlugin\StateResolver\RefundPaymentCompletedStateApplier`

1. Aliases for the following services have been introduced to standardize service IDs and will replace the incorrect IDs 
   in RefundPlugin 2.0. The old service IDs are now deprecated and will be removed in RefundPlugin 2.0. 
   Please update your service definitions accordingly to ensure compatibility with next major version of RefundPlugin.

   | Old ID                                                                            | New ID                                                                              |
   |-----------------------------------------------------------------------------------|-------------------------------------------------------------------------------------|
   | `Sylius\RefundPlugin\Refunder\OrderItemUnitsRefunder`                             | `sylius_refund.refunder.order_item_units`                                           |
   | `Sylius\RefundPlugin\Refunder\OrderShipmentsRefunder`                             | `sylius_refund.refunder.order_shipments`                                            |
   | `Sylius\RefundPlugin\Twig\OrderRefundsExtension`                                  | `sylius_refund.twig.extension.order_refunds`                                        |
   | `sylius_refund_plugin.repository.credit_memo_sequence`                            | `sylius_refund.repository.credit_memo_sequence`                                     |
   | `Sylius\RefundPlugin\Action\Admin\DownloadCreditMemoAction`                       | `sylius_refund.controller.admin.download_credit_memo`                               |
   | `Sylius\RefundPlugin\Action\Shop\DownloadCreditMemoAction`                        | `sylius_refund.controller.shop.download_credit_memo`                                |
   | `Sylius\RefundPlugin\Action\Admin\OrderRefundsListAction`                         | `sylius_refund.controller.admin.order_refunds_list`                                 |
   | `Sylius\RefundPlugin\Action\Admin\RefundUnitsAction`                              | `sylius_refund.controller.admin.refund_units`                                       |
   | `Sylius\RefundPlugin\Action\CompleteRefundPaymentAction`                          | `sylius_refund.controller.complete_refund_payment`                                  |
   | `Sylius\RefundPlugin\Action\Admin\SendCreditMemoAction`                           | `sylius_refund.controller.admin.send_credit_memo`                                   |
   | `Sylius\RefundPlugin\Checker\OrderRefundingAvailabilityChecker`                   | `sylius_refund.checker.order_refunding_availability`                                |
   | `Sylius\RefundPlugin\Checker\OrderRefundsListAvailabilityChecker`                 | `sylius_refund.checker.order_refunds_list_availability`                             |
   | `Sylius\RefundPlugin\Doctrine\ORM\CountOrderItemUnitRefundsBelongingToOrderQuery` | `sylius_refund.doctrine.orm.query.count_order_item_unit_refunds_belonging_to_order` |
   | `Sylius\RefundPlugin\Doctrine\ORM\CountShipmentRefundsBelongingToOrderQuery`      | `sylius_refund.doctrine.orm.query.count_shipment_refunds_belonging_to_order`        |
   | `Sylius\RefundPlugin\Menu\AdminMainMenuListener`                                  | `sylius_refund.listener.admin_main_menu`                                            |
   | `Sylius\RefundPlugin\Menu\OrderShowMenuListener`                                  | `sylius_refund.listener.order_show_menu`                                            |
   | `Sylius\RefundPlugin\Listener\CreditMemoGeneratedEventListener`                   | `sylius_refund.listener.credit_memo_generated`                                      |
   | `Sylius\RefundPlugin\Listener\UnitRefundedEventListener`                          | `sylius_refund.listener.unit_refunded`                                              |
   | `Sylius\RefundPlugin\ProcessManager\RefundPaymentProcessManager`                  | `sylius_refund.process_manager.refund_payment`                                      |
   | `Sylius\RefundPlugin\ProcessManager\CreditMemoProcessManager`                     | `sylius_refund.process_manager.credit_memo`                                         |
   | `Sylius\RefundPlugin\Factory\CreditMemoFactory`                                   | `sylius_refund.custom_factory.credit_memo`                                          |
   | `Sylius\RefundPlugin\Factory\ShopBillingDataFactory`                              | `sylius_refund.custom_factory.shop_billing_data`                                    |
   | `Sylius\RefundPlugin\Factory\CustomerBillingDataFactory`                          | `sylius_refund.custom_factory.customer_billing_data`                                |
   | `Sylius\RefundPlugin\Validator\OrderItemUnitRefundsBelongingToOrderValidator`     | `sylius_refund.validator.order_item_unit_refunds_belonging_to_order`                |
   | `Sylius\RefundPlugin\Validator\ShipmentRefundsBelongingToOrderValidator`          | `sylius_refund.validator.shipment_refunds_belonging_to_order`                       |
   | `Sylius\RefundPlugin\Provider\OrderItemUnitTotalProvider`                         | `sylius_refund.provider.order_item_unit_total`                                      |
   | `Sylius\RefundPlugin\Provider\ShipmentTotalProvider`                              | `sylius_refund.provider.shipment_total`                                             |
   | `Sylius\RefundPlugin\CommandHandler\RefundUnitsHandler`                           | `sylius_refund.command_handler.refund_units`                                        |
   | `Sylius\RefundPlugin\CommandHandler\GenerateCreditMemoHandler`                    | `sylius_refund.command_handler.generate_credit_memo`                                |
   | `Sylius\RefundPlugin\CommandHandler\SendCreditMemoHandler`                        | `sylius_refund.command_handler.send_credit_memo`                                    |
   | `Sylius\RefundPlugin\Converter\LineItem\OrderItemUnitLineItemsConverter`          | `sylius_refund.converter.line_items.order_item_unit`                                |
   | `Sylius\RefundPlugin\Converter\LineItem\ShipmentLineItemsConverter`               | `sylius_refund.converter.line_items.shipment`                                       |

1. For the following services, new aliases have been added, these aliases will become the primary services IDs 
   in RefundPlugin 2.0, while the current service IDs will be converted into aliases:

   | Current ID                                                                       | New Alias                                                       |
   |----------------------------------------------------------------------------------|-----------------------------------------------------------------|
   | `Sylius\RefundPlugin\Calculator\UnitRefundTotalCalculatorInterface`              | `sylius_refund.calculator.unit_refund_total`                    |
   | `Sylius\RefundPlugin\Sender\CreditMemoEmailSenderInterface`                      | `sylius_refund.email_sender.credit_memo`                        |
   | `Sylius\RefundPlugin\ResponseBuilder\CreditMemoFileResponseBuilderInterface`     | `sylius_refund.response_builder.credit_memo_file`               |
   | `Sylius\RefundPlugin\Manager\CreditMemoFileManagerInterface`                     | `sylius_refund.manager.credit_memo_file`                        |
   | `Sylius\RefundPlugin\Checker\CreditMemoCustomerRelationCheckerInterface`         | `sylius_refund.checker.credit_memo_customer_relation`           |
   | `Sylius\RefundPlugin\Checker\OrderFullyRefundedTotalCheckerInterface`            | `sylius_refund.checker.order_fully_refunded_total`              |
   | `Sylius\RefundPlugin\Checker\UnitRefundingAvailabilityCheckerInterface`          | `sylius_refund.checker.unit_refunding_availability`             |
   | `Sylius\RefundPlugin\Resolver\CreditMemoFileResolverInterface`                   | `sylius_refund.resolver.credit_memo_file`                       |
   | `Sylius\RefundPlugin\Resolver\CreditMemoFilePathResolverInterface`               | `sylius_refund.resolver.credit_memo_file_path`                  |
   | `Sylius\RefundPlugin\Filter\UnitRefundFilterInterface`                           | `sylius_refund.filter.unit_refund`                              |
   | `Sylius\RefundPlugin\ProcessManager\UnitsRefundedProcessManagerInterface`        | `sylius_refund.process_manager.units_refunded`                  |
   | `Sylius\RefundPlugin\Factory\CreditMemoSequenceFactoryInterface`                 | `sylius_refund.factory.credit_memo_sequence`                    |
   | `Sylius\RefundPlugin\Factory\RefundTypeFactoryInterface`                         | `sylius_refund.factory.refund_type`                             |
   | `Sylius\RefundPlugin\Factory\LineItemFactoryInterface`                           | `sylius_refund.factory.line_item`                               |
   | `Sylius\RefundPlugin\Validator\RefundUnitsCommandValidatorInterface`             | `sylius_refund.validator.refund_units_command`                  |
   | `Sylius\RefundPlugin\Validator\RefundAmountValidatorInterface`                   | `sylius_refund.validator.refund_amount`                         |
   | `Sylius\RefundPlugin\StateResolver\OrderFullyRefundedStateResolverInterface`     | `sylius_refund.state_resolver.order_fully_refunded`             |
   | `Sylius\RefundPlugin\StateResolver\OrderPartiallyRefundedStateResolverInterface` | `sylius_refund.state_resolver.order_partially_refunded`         |
   | `Sylius\RefundPlugin\StateResolver\RefundPaymentCompletedStateApplierInterface`  | `sylius_refund.state_resolver.refund_payment_completed_applier` |
   | `Sylius\RefundPlugin\Generator\CreditMemoNumberGeneratorInterface`               | `sylius_refund.generator.credit_memo_number`                    |
   | `Sylius\RefundPlugin\Generator\CreditMemoGeneratorInterface`                     | `sylius_refund.generator.credit_memo`                           |
   | `Sylius\RefundPlugin\Generator\CreditMemoFileNameGeneratorInterface`             | `sylius_refund.generator.credit_memo_file_name`                 |
   | `Sylius\RefundPlugin\Generator\CreditMemoPdfFileGeneratorInterface`              | `sylius_refund.generator.credit_memo_pdf_file`                  |
   | `Sylius\RefundPlugin\Generator\TaxItemsGeneratorInterface`                       | `sylius_refund.generator.tax_items`                             |
   | `Sylius\RefundPlugin\Generator\CreditMemoIdentifierGeneratorInterface`           | `sylius_refund.generator.credit_memo_identifier`                |
   | `Sylius\RefundPlugin\Generator\PdfOptionsGeneratorInterface`                     | `sylius_refund.generator.pdf_options`                           |
   | `Sylius\RefundPlugin\Generator\TwigToPdfGeneratorInterface`                      | `sylius_refund.generator.twig_to_pdf`                           |
   | `Sylius\RefundPlugin\Provider\CreditMemoFileProviderInterface`                   | `sylius_refund.provider.credit_memo_file`                       |
   | `Sylius\RefundPlugin\Provider\RefundedShipmentFeeProviderInterface`              | `sylius_refund.provider.refunded_shipment_fee`                  |
   | `Sylius\RefundPlugin\Provider\OrderRefundedTotalProviderInterface`               | `sylius_refund.provider.order_refunded_total`                   |
   | `Sylius\RefundPlugin\Provider\CurrentDateTimeImmutableProviderInterface`         | `sylius_refund.provider.current_date_time_immutable`            |
   | `Sylius\RefundPlugin\Provider\RemainingTotalProviderInterface`                   | `sylius_refund.provider.remaining_total`                        |
   | `Sylius\RefundPlugin\Provider\RelatedPaymentIdProviderInterface`                 | `sylius_refund.provider.related_payment_id`                     |
   | `Sylius\RefundPlugin\Provider\RefundPaymentMethodsProviderInterface`             | `sylius_refund.provider.refund_payment_methods`                 |
   | `Sylius\RefundPlugin\Provider\TaxRateProviderInterface`                          | `sylius_refund.provider.tax_rate`                               |
   | `Sylius\RefundPlugin\Creator\RefundCreatorInterface`                             | `sylius_refund.creator.refund`                                  |
   | `Sylius\RefundPlugin\Creator\RequestCommandCreatorInterface`                     | `sylius_refund.creator.request_command`                         |
   | `Sylius\RefundPlugin\Converter\LineItem\LineItemsConverterInterface`             | `sylius_refund.converter.line_items`                            |
   | `Sylius\RefundPlugin\Converter\Request\RequestToRefundUnitsConverterInterface`   | `sylius_refund.converter.request_to_refund_units`               |
   | `Sylius\RefundPlugin\Converter\Request\RequestToShipmentRefundConverter`         | `sylius_refund.converter.request_to_shipment_refund`            |
   | `Sylius\RefundPlugin\Converter\Request\RequestToOrderItemUnitRefundConverter`    | `sylius_refund.converter.request_to_order_item_unit_refund`     |
   | `Sylius\RefundPlugin\Converter\RefundUnitsConverterInterface`                    | `sylius_refund.converter.refund_units`                          |
