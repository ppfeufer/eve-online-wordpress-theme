<?php

namespace Ppfeufer\Theme\EVEOnline\Singletons;

use RuntimeException;

/**
 * General Singleton
 *
 * @package Ppfeufer\Theme\EVEOnline\Singletons
 */
class GenericSingleton {
    /**
     * Holds the instances of this class or subclasses.
     *
     * @var array<class-string<static>, static> $instances The instances of this class or subclasses.
     * @scope protected
     */
    protected static array $instances = [];

    /**
     * Constructor
     *
     * A protected constructor to prevent creating a new instance of the
     * Singleton via the `new` operator from outside of this class.
     *
     * @return void
     * @scope protected
     */
    protected function __construct() {
    }

    /**
     * Get instance.
     *
     * Returns the *Singleton* instance of this class.
     *
     * @return static The *Singleton* instance.
     * @scope public
     */
    public static function getInstance(): static {
        $calledClass = static::class;

        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new $calledClass();
        }

        return self::$instances[$calledClass];
    }

    /**
     * Prevent the instance from being cloned.
     *
     * @return void
     * @scope private
     */
    private function __clone() {
    }

    /**
     * Prevent from being deserialized.
     *
     * This method is public to comply with the PHP internals handling of deserialization.
     *
     * @return void
     * @throws RuntimeException
     * @scope public
     */
    public function __wakeup() {
        throw new RuntimeException(message: 'Cannot deserialize a singleton.');
    }
}
