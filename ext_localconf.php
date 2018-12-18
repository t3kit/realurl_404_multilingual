<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling'] =
        'USER_FUNCTION:' . \WapplerSystems\Realurl404Multilingual\Hooks\FrontendHook::class . '->pageErrorHandler';

    // Caching the 404 pages - default expire 3600 seconds
    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['realurl_404_multilingual'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['realurl_404_multilingual'] = array(
            'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
            'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class
        );
    }

    // Check if request was made from realurl_404_multilingual and session key was pass
    if (\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_realurl404multilingual') == '1'
        && \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('FE_SESSION_KEY')
        && $_SERVER['SERVER_ADDR'] == \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REMOTE_ADDR')
    ) {
        $fe_sParts = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(
            '-',
            \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('FE_SESSION_KEY'),
            true
        );
        // If the session key hash check is OK:
        if (!strcmp(md5(($fe_sParts[0] . '/' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'])), $fe_sParts[1])) {
            //disable IP check
            $GLOBALS['TYPO3_CONF_VARS']['FE']['lockIP'] = '0';
        }
    }
});
