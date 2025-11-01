يبي<?php
/**
 * Environment Configuration Loader
 *
 * Loads environment variables from .env file
 */

class EnvLoader {

    protected static $loaded = false;

    /**
     * Load environment variables from .env file
     */
    public static function load($path) {
        if (self::$loaded) {
            return;
        }

        $envFile = $path . '/.env';

        if (!file_exists($envFile)) {
            throw new Exception('.env file not found at: ' . $envFile);
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove quotes if present
                $value = trim($value, '"\'');

                // Handle variable substitution ${VAR_NAME}
                $value = preg_replace_callback('/\$\{([A-Z_]+)\}/', function($matches) {
                    return getenv($matches[1]) ?: '';
                }, $value);

                // Set environment variable
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }

        self::$loaded = true;
    }
}

/**
 * Get environment variable value
 *
 * @param string $key Variable name
 * @param mixed $default Default value if not found
 * @return mixed
 */
function env($key, $default = null) {
    $value = getenv($key);

    if ($value === false) {
        $value = $_ENV[$key] ?? $default;
    }

    // Convert string booleans
    if (is_string($value)) {
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
            case 'empty':
            case '(empty)':
                return '';
        }
    }

    return $value;
}

// Auto-load .env file
$rootPath = dirname(__DIR__);
try {
    EnvLoader::load($rootPath);
} catch (Exception $e) {
    die('Error loading .env file: ' . $e->getMessage());
}
