<?php

defined('TYPO3') || die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'MollieApi',
            'PaymentForm',
            [
                \Passionweb\MollieApi\Controller\MollieController::class => 'index,payment'
            ],
            // non-cacheable actions
            [
                \Passionweb\MollieApi\Controller\MollieController::class => 'index,payment'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'MollieApi',
            'PaymentReturn',
            [
                \Passionweb\MollieApi\Controller\MollieController::class => 'paymentreturn'
            ],
            // non-cacheable actions
            [
                \Passionweb\MollieApi\Controller\MollieController::class => 'paymentreturn'
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        paymentform {
                            iconIdentifier = mollie-payment
                            title = LLL:EXT:mollie_api/Resources/Private/Language/locallang_db.xlf:plugin_mollie_payment.name
                            description = LLL:EXT:mollie_api/Resources/Private/Language/locallang_db.xlf:plugin_mollie_payment.description
                            tt_content_defValues {
                                CType = list
                                list_type = mollieapi_paymentform
                            }
                        }
                        paymentreturn {
                            iconIdentifier = mollie-payment-return
                            title = LLL:EXT:mollie_api/Resources/Private/Language/locallang_db.xlf:plugin_mollie_paymentreturn.name
                            description = LLL:EXT:mollie_api/Resources/Private/Language/locallang_db.xlf:plugin_mollie_paymentreturn.description
                            tt_content_defValues {
                                CType = list
                                list_type = mollieapi_paymentreturn
                            }
                        }
                    }
                    show = *
                }
           }'
        );

        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'mollie-payment',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:mollie_api/Resources/Public/Icons/Extension.png']
        );
        $iconRegistry->registerIcon(
            'mollie-payment-return',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:mollie_api/Resources/Public/Icons/Extension.png']
        );
    },
    'mollie_api'
);
