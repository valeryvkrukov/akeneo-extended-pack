# akeneo-extended-pack

## Manual installation 
 - Upload & unzip package to src/ folder
 - Add `new \ExtendedPack\Bundle\ExtendedPackBundle(),` to array of return in method `registerProjectBundles()` in app/AppKernel.php
 - In app/routing.yml add route with resource `@ExtendedPackBundle/Resources/config/routing.yml`
 - Clear cache and rebuild assets:
   - rm -rf var/cache/* web/cache/*
   - php bin/console pim:installer:assets --env=prod (--env=dev)
   - yarn run webpack (yarn run webpack-dev)
   - check for write permissions for catalogs var/cache, var/logs etc