php app/console doctrine:mapping:convert yml ./src/Mirror/ApiBundle/Resources/config/doctrine/metadata/orm --from-database --force
php app/console doctrine:mapping:import MirrorApiBundle annotation
php app/console doctrine:generate:entities MirrorApiBundle