### UPGRADE FROM 1.X TO 2.0

1. The way of customizing resource definition has been changed.

   Before:

    ```yaml
        sylius_resource:
            resources:
                sylius_refund.sample_resource:
                    ...
    ```  

   After:

    ```yaml
        sylius_refund:
            resources:
                sample_resource:
                    ...
    ```

1. Doctrine migrations have been regenerated, meaning all previous migration files have been removed and their content is now in a single migration file.
   To apply the new migration and get rid of the old entries run migrations as usual:

```bash
    bin/console doctrine:migrations:migrate --no-interaction
```
