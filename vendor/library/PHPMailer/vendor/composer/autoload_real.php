<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit376f7735b2ab07cf7411c6a61257efe1
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit376f7735b2ab07cf7411c6a61257efe1', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit376f7735b2ab07cf7411c6a61257efe1', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit376f7735b2ab07cf7411c6a61257efe1::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
