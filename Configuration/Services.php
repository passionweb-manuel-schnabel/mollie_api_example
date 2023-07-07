<?php

use Mollie\Api\MollieApiClient;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use Passionweb\MollieApi\Service\MollieService;
use Passionweb\MollieApi\Controller\MollieController;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services->load('Passionweb\\MollieApi\\', __DIR__ . '/../Classes/')
        ->exclude([
            __DIR__ . '/../Classes/Domain/Model',
        ]);

    $services->set('ExtConf.mollieApiKey', 'string')
        ->factory([service(ExtensionConfiguration::class), 'get'])
        ->args(
            [
                'mollie_api',
                'mollieApiKey'
            ]
        );

    $services->set('ExtConf.successPid', 'string')
        ->factory([service(ExtensionConfiguration::class), 'get'])
        ->args(
            [
                'mollie_api',
                'successPid'
            ]
        );

    $containerBuilder->register('MollieApiClient', MollieApiClient::class);
    $services->set('MollieApiClientWithKey', 'MollieApiClient')
        ->factory(
            [
                service('MollieApiClient'), 'setApiKey'
            ]
        )
        ->args(
            [
                service('ExtConf.mollieApiKey')
            ]
        );

    $services->set(MollieService::class)
        ->args(
            [
                service('MollieApiClientWithKey')
            ]
        )
        ->public();

    $services->set(MollieController::class)
        ->arg('$successPid', service('ExtConf.successPid'));
};
