### Legacy installation (without SymfonyFlex)

1. Require plugin with composer:

   ```bash
   composer require sylius/refund-plugin
   ```

1. Add plugin class and other required bundles to your `config/bundles.php`:

    ```php
    $bundles = [
        Knp\Bundle\SnappyBundle\KnpSnappyBundle::class => ['all' => true],
        Sylius\RefundPlugin\SyliusRefundPlugin::class => ['all' => true],
    ];
    ```

1. Import configuration:

    ```yaml
    imports:
        - { resource: "@SyliusRefundPlugin/config/config.yaml" }
    ```
1. Import routes:

    ````yaml
   sylius_refund:
       resource: "@SyliusRefundPlugin/config/routes.yaml"
    ````

1. Apply migrations to your database:

    ```bash
    bin/console doctrine:migrations:migrate
    ```

1. Install assets:

   Add the following line to your `assets/admin/entrypoint.js` file:

    ```js
   import '../../vendor/sylius/refund-plugin/assets/entrypoint';
    ```

1. Run `yarn encore dev` or `yarn encore production`

1. Check if you have `wkhtmltopdf` binary. If not, you can download it [here](https://wkhtmltopdf.org/downloads.html).

   In case `wkhtmltopdf` is not located in `/usr/local/bin/wkhtmltopdf`, add a following snippet at the end of your application's `.env`:

    ```yaml
    WKHTMLTOPDF_PATH=/path/to/your/wkhtmltopdf
    ```   

1. Clear cache:

    ```bash
    bin/console cache:clear
    ```
