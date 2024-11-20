### UPGRADE FROM 1.X TO 2.0

1. The way of customizing resource definition has been changed.

    ```diff
    -   sylius_resource:
    +   sylius_refund:
            resources:
    -           sylius_refund.sample_resource:
    +           sample_resource:
                    ...
    ```  

1. Doctrine migrations have been regenerated, meaning all previous migration files have been removed and their content is now in a single migration file.
   To apply the new migration and get rid of the old entries run migrations as usual:

```bash
    bin/console doctrine:migrations:migrate --no-interaction
```

1. Aliases introduced in RefundPlugin 1.6 have now become the primary service IDs in RefundPlugin 2.0. 
   The old service IDs have been removed, and all references must be updated accordingly:

   | Old ID                                                                            | New ID                                                                              |
   |-----------------------------------------------------------------------------------|-------------------------------------------------------------------------------------|
   | `sylius_refund_plugin.repository.credit_memo_sequence`                            | `sylius_refund.repository.credit_memo_sequence`                                     |
   | `Sylius\RefundPlugin\Action\Admin\DownloadCreditMemoAction`                       | `sylius_refund.controller.admin.download_credit_memo`                               |
   | `Sylius\RefundPlugin\Action\Admin\OrderRefundsListAction`                         | `sylius_refund.controller.admin.order_refunds_list`                                 |
   | `Sylius\RefundPlugin\Action\Admin\RefundUnitsAction`                              | `sylius_refund.controller.admin.refund_units`                                       |
   | `Sylius\RefundPlugin\Action\Admin\SendCreditMemoAction`                           | `sylius_refund.controller.admin.send_credit_memo`                                   |
   | `Sylius\RefundPlugin\Action\CompleteRefundPaymentAction`                          | `sylius_refund.controller.complete_refund_payment`                                  |
   | `Sylius\RefundPlugin\Action\Shop\DownloadCreditMemoAction`                        | `sylius_refund.controller.shop.download_credit_memo`                                |
   | `Sylius\RefundPlugin\Checker\OrderRefundingAvailabilityChecker`                   | `sylius_refund.checker.order_refunding_availability`                                |
   | `Sylius\RefundPlugin\Checker\OrderRefundsListAvailabilityChecker`                 | `sylius_refund.checker.order_refunds_list_availability`                             |
   | `Sylius\RefundPlugin\CommandHandler\GenerateCreditMemoHandler`                    | `sylius_refund.command_handler.generate_credit_memo`                                |
   | `Sylius\RefundPlugin\CommandHandler\RefundUnitsHandler`                           | `sylius_refund.command_handler.refund_units`                                        |
   | `Sylius\RefundPlugin\CommandHandler\SendCreditMemoHandler`                        | `sylius_refund.command_handler.send_credit_memo`                                    |
   | `Sylius\RefundPlugin\Converter\LineItem\OrderItemUnitLineItemsConverter`          | `sylius_refund.converter.line_items.order_item_unit`                                |
   | `Sylius\RefundPlugin\Converter\LineItem\ShipmentLineItemsConverter`               | `sylius_refund.converter.line_items.shipment`                                       |
   | `Sylius\RefundPlugin\Doctrine\ORM\CountOrderItemUnitRefundsBelongingToOrderQuery` | `sylius_refund.doctrine.orm.query.count_order_item_unit_refunds_belonging_to_order` |
   | `Sylius\RefundPlugin\Doctrine\ORM\CountShipmentRefundsBelongingToOrderQuery`      | `sylius_refund.doctrine.orm.query.count_shipment_refunds_belonging_to_order`        |
   | `Sylius\RefundPlugin\Factory\CreditMemoFactory`                                   | `sylius_refund.custom_factory.credit_memo`                                          |
   | `Sylius\RefundPlugin\Factory\CustomerBillingDataFactory`                          | `sylius_refund.custom_factory.customer_billing_data`                                |
   | `Sylius\RefundPlugin\Factory\ShopBillingDataFactory`                              | `sylius_refund.custom_factory.shop_billing_data`                                    |
   | `Sylius\RefundPlugin\Listener\CreditMemoGeneratedEventListener`                   | `sylius_refund.listener.credit_memo_generated`                                      |
   | `Sylius\RefundPlugin\Listener\UnitRefundedEventListener`                          | `sylius_refund.listener.unit_refunded`                                              |
   | `Sylius\RefundPlugin\Menu\AdminMainMenuListener`                                  | `sylius_refund.listener.admin_main_menu`                                            |
   | `Sylius\RefundPlugin\Menu\OrderShowMenuListener`                                  | `sylius_refund.listener.order_show_menu`                                            |
   | `Sylius\RefundPlugin\ProcessManager\CreditMemoProcessManager`                     | `sylius_refund.process_manager.credit_memo`                                         |
   | `Sylius\RefundPlugin\ProcessManager\RefundPaymentProcessManager`                  | `sylius_refund.process_manager.refund_payment`                                      |
   | `Sylius\RefundPlugin\Provider\OrderItemUnitTotalProvider`                         | `sylius_refund.provider.order_item_unit_total`                                      |
   | `Sylius\RefundPlugin\Provider\ShipmentTotalProvider`                              | `sylius_refund.provider.shipment_total`                                             |
   | `Sylius\RefundPlugin\Refunder\OrderItemUnitsRefunder`                             | `sylius_refund.refunder.order_item_units`                                           |
   | `Sylius\RefundPlugin\Refunder\OrderShipmentsRefunder`                             | `sylius_refund.refunder.order_shipments`                                            |
   | `Sylius\RefundPlugin\Twig\OrderRefundsExtension`                                  | `sylius_refund.twig.extension.order_refunds`                                        |
   | `Sylius\RefundPlugin\Validator\OrderItemUnitRefundsBelongingToOrderValidator`     | `sylius_refund.validator.order_item_unit_refunds_belonging_to_order`                |
   | `Sylius\RefundPlugin\Validator\ShipmentRefundsBelongingToOrderValidator`          | `sylius_refund.validator.shipment_refunds_belonging_to_order`                       |

1. The following services had new aliases added in RefundPlugin 1.6. In RefundPlugin 2.0, these aliases have become 
   the primary service IDs, and the old service IDs remain as aliases:

   | Old ID                                                                           | New Id                                                          |
   |----------------------------------------------------------------------------------|-----------------------------------------------------------------|
   | `Sylius\RefundPlugin\Calculator\UnitRefundTotalCalculatorInterface`              | `sylius_refund.calculator.unit_refund_total`                    |
   | `Sylius\RefundPlugin\Checker\CreditMemoCustomerRelationCheckerInterface`         | `sylius_refund.checker.credit_memo_customer_relation`           |
   | `Sylius\RefundPlugin\Checker\OrderFullyRefundedTotalCheckerInterface`            | `sylius_refund.checker.order_fully_refunded_total`              |
   | `Sylius\RefundPlugin\Checker\UnitRefundingAvailabilityCheckerInterface`          | `sylius_refund.checker.unit_refunding_availability`             |
   | `Sylius\RefundPlugin\Converter\LineItem\LineItemsConverterInterface`             | `sylius_refund.converter.line_items`                            |
   | `Sylius\RefundPlugin\Converter\RefundUnitsConverterInterface`                    | `sylius_refund.converter.refund_units`                          |
   | `Sylius\RefundPlugin\Converter\Request\RequestToOrderItemUnitRefundConverter`    | `sylius_refund.converter.request_to_order_item_unit_refund`     |
   | `Sylius\RefundPlugin\Converter\Request\RequestToRefundUnitsConverterInterface`   | `sylius_refund.converter.request_to_refund_units`               |
   | `Sylius\RefundPlugin\Converter\Request\RequestToShipmentRefundConverter`         | `sylius_refund.converter.request_to_shipment_refund`            |
   | `Sylius\RefundPlugin\Creator\RefundCreatorInterface`                             | `sylius_refund.creator.refund`                                  |
   | `Sylius\RefundPlugin\Creator\RequestCommandCreatorInterface`                     | `sylius_refund.creator.request_command`                         |
   | `Sylius\RefundPlugin\Factory\CreditMemoSequenceFactoryInterface`                 | `sylius_refund.factory.credit_memo_sequence`                    |
   | `Sylius\RefundPlugin\Factory\LineItemFactoryInterface`                           | `sylius_refund.factory.line_item`                               |
   | `Sylius\RefundPlugin\Factory\RefundTypeFactoryInterface`                         | `sylius_refund.factory.refund_type`                             |
   | `Sylius\RefundPlugin\Filter\UnitRefundFilterInterface`                           | `sylius_refund.filter.unit_refund`                              |
   | `Sylius\RefundPlugin\Generator\CreditMemoFileNameGeneratorInterface`             | `sylius_refund.generator.credit_memo_file_name`                 |
   | `Sylius\RefundPlugin\Generator\CreditMemoGeneratorInterface`                     | `sylius_refund.generator.credit_memo`                           |
   | `Sylius\RefundPlugin\Generator\CreditMemoIdentifierGeneratorInterface`           | `sylius_refund.generator.credit_memo_identifier`                |
   | `Sylius\RefundPlugin\Generator\CreditMemoNumberGeneratorInterface`               | `sylius_refund.generator.credit_memo_number`                    |
   | `Sylius\RefundPlugin\Generator\CreditMemoPdfFileGeneratorInterface`              | `sylius_refund.generator.credit_memo_pdf_file`                  |
   | `Sylius\RefundPlugin\Generator\PdfOptionsGeneratorInterface`                     | `sylius_refund.generator.pdf_options`                           |
   | `Sylius\RefundPlugin\Generator\TaxItemsGeneratorInterface`                       | `sylius_refund.generator.tax_items`                             |
   | `Sylius\RefundPlugin\Generator\TwigToPdfGeneratorInterface`                      | `sylius_refund.generator.twig_to_pdf`                           |
   | `Sylius\RefundPlugin\Manager\CreditMemoFileManagerInterface`                     | `sylius_refund.manager.credit_memo_file`                        |
   | `Sylius\RefundPlugin\ProcessManager\UnitsRefundedProcessManagerInterface`        | `sylius_refund.process_manager.units_refunded`                  |
   | `Sylius\RefundPlugin\Provider\CreditMemoFileProviderInterface`                   | `sylius_refund.provider.credit_memo_file`                       |
   | `Sylius\RefundPlugin\Provider\CurrentDateTimeImmutableProviderInterface`         | `sylius_refund.provider.current_date_time_immutable`            |
   | `Sylius\RefundPlugin\Provider\OrderRefundedTotalProviderInterface`               | `sylius_refund.provider.order_refunded_total`                   |
   | `Sylius\RefundPlugin\Provider\RefundedShipmentFeeProviderInterface`              | `sylius_refund.provider.refunded_shipment_fee`                  |
   | `Sylius\RefundPlugin\Provider\RefundPaymentMethodsProviderInterface`             | `sylius_refund.provider.refund_payment_methods`                 |
   | `Sylius\RefundPlugin\Provider\RelatedPaymentIdProviderInterface`                 | `sylius_refund.provider.related_payment_id`                     |
   | `Sylius\RefundPlugin\Provider\RemainingTotalProviderInterface`                   | `sylius_refund.provider.remaining_total`                        |
   | `Sylius\RefundPlugin\Provider\TaxRateProviderInterface`                          | `sylius_refund.provider.tax_rate`                               |
   | `Sylius\RefundPlugin\Resolver\CreditMemoFilePathResolverInterface`               | `sylius_refund.resolver.credit_memo_file_path`                  |
   | `Sylius\RefundPlugin\Resolver\CreditMemoFileResolverInterface`                   | `sylius_refund.resolver.credit_memo_file`                       |
   | `Sylius\RefundPlugin\ResponseBuilder\CreditMemoFileResponseBuilderInterface`     | `sylius_refund.response_builder.credit_memo_file`               |
   | `Sylius\RefundPlugin\Sender\CreditMemoEmailSenderInterface`                      | `sylius_refund.email_sender.credit_memo`                        |
   | `Sylius\RefundPlugin\StateResolver\OrderFullyRefundedStateResolverInterface`     | `sylius_refund.state_resolver.order_fully_refunded`             |
   | `Sylius\RefundPlugin\StateResolver\OrderPartiallyRefundedStateResolverInterface` | `sylius_refund.state_resolver.order_partially_refunded`         |
   | `Sylius\RefundPlugin\StateResolver\RefundPaymentCompletedStateApplierInterface`  | `sylius_refund.state_resolver.refund_payment_completed_applier` |
   | `Sylius\RefundPlugin\Validator\RefundAmountValidatorInterface`                   | `sylius_refund.validator.refund_amount`                         |
   | `Sylius\RefundPlugin\Validator\RefundUnitsCommandValidatorInterface`             | `sylius_refund.validator.refund_units_command`                  |

1. The following deprecated aliases have been removed, use the service IDs instead:

   | Old alias ID                                                    | Service Id                                           | 
   |-----------------------------------------------------------------|------------------------------------------------------|
   | `Sylius\RefundPlugin\Converter\OrderItemUnitLineItemsConverter` | `sylius_refund.converter.line_items.order_item_unit` |
   | `Sylius\RefundPlugin\Converter\ShipmentLineItemsConverter`      | `sylius_refund.converter.line_items.shipment`        |
 
1. The following parameters have been renamed:

   | Old parameter                      | New parameter                      |  
   |------------------------------------|------------------------------------|
   | `default_logo_file`                | `sylius_refund.default_logo_file`  |
   | `sylius.refund.template.logo_file` | `sylius_refund.template.logo_file` |

1. The following configuration parameters have been renamed:

    ```diff
    -   sylius_refund_plugin:
    +   sylius_refund:
            pdf_generator:
                ...
    ```

1. The buses `sylius_refund_plugin.command_bus` and `sylius_refund_plugin.event_bus` have been replaced
   accordingly by `sylius.command_bus` and `sylius.event_bus`.

1. The visibility of services has been changed to `private` by default. This change enhances the performance 
   and maintainability of the application and also follows Symfony's best practices for service encapsulation.

   Exceptions:
   - Services required by Symfony to be `public` (e.g., controllers, event listeners) remain public.

1. `_javascript.html.twig` file has been removed, and its code has been moved to `src/Resources/assets/js/refund-button.js`. When upgrading to 2.0, import the `src/Resources/assets/entrypoint.js` file into your application’s main js file.
