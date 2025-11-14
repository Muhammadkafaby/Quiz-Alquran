<?php

namespace Config;

class Paths
{
    /**
     * System Directory
     */
    public string $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system';

    /**
     * Application Directory
     */
    public string $appDirectory = __DIR__ . '/..';

    /**
     * Writable Directory
     */
    public string $writableDirectory = __DIR__ . '/../../writable';

    /**
     * Tests Directory
     */
    public string $testsDirectory = __DIR__ . '/../../tests';

    /**
     * View Directory
     */
    public string $viewDirectory = __DIR__ . '/../Views';
}
