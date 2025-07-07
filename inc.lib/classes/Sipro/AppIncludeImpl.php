<?php

namespace Sipro;
use MagicApp\AppInclude;

class AppIncludeImpl extends AppInclude
{
    /**
     * AppIncludeImpl constructor.
     *
     * Initializes the AppIncludeImpl instance with the given application configuration
     * and the current module. Also sets the root path for includes resolution.
     *
     * @param SecretObject $appConfig     The application configuration object.
     * @param PicoModule   $currentModule The current module instance in use.
     */
    public function __construct($appConfig, $currentModule)
    {
        parent::__construct($appConfig, $currentModule, dirname(dirname(dirname(__DIR__))));
    }

    /**
     * Get the path to the main header file.
     *
     * Retrieves the file path for the main header. If a custom header path is defined in the configuration,
     * it is used, otherwise, a default path is returned.
     *
     * @param string $dir Base directory for includes.
     * @return string Path to the header file.
     */
    public function mainAppHeader($dir)
    {
        return parent::mainAppHeader(dirname($dir));
    }

    /**
     * Get the path to the main footer file.
     *
     * Retrieves the file path for the main footer. If a custom footer path is defined in the configuration,
     * it is used, otherwise, a default path is returned.
     *
     * @param string $dir Base directory for includes.
     * @return string Path to the footer file.
     */
    public function mainAppFooter($dir)
    {
        return parent::mainAppFooter(dirname($dir));
    }

    /**
     * Get the path to the forbidden access page.
     *
     * Retrieves the file path for the forbidden access page (403). If a custom path is defined in the configuration,
     * it is used, otherwise, a default path is returned.
     *
     * @param string $dir Base directory for includes.
     * @return string Path to the forbidden page (403).
     */
    public function appForbiddenPage($dir)
    {
        return parent::appForbiddenPage(dirname($dir));
    }

    /**
     * Get the path to the not found page.
     *
     * Retrieves the file path for the not found page (404). If a custom path is defined in the configuration,
     * it is used, otherwise, a default path is returned.
     *
     * @param string $dir Base directory for includes.
     * @return string Path to the not found page (404).
     */
    public function appNotFoundPage($dir)
    {
        return parent::appNotFoundPage(dirname($dir));
    }
}