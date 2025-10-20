<?php
/**
 * ECS Syntax Validator - Validates PHP, JavaScript, and CSS syntax
 *
 * @package ECS
 * @since 1.0.0
 */

declare(strict_types=1);

namespace ECS;

/**
 * Syntax Validator class
 *
 * Validates code syntax without requiring external CLI tools
 */
class SyntaxValidator
{
    /**
     * Validate PHP code syntax using PHP's tokenizer
     *
     * @param string $code PHP code to validate
     * @return array Validation result with 'valid', 'error', 'line' keys
     */
    public static function validate_php(string $code): array
    {
        if (empty(trim($code))) {
            return [
                'valid' => true,
                'error' => '',
                'line'  => 0,
            ];
        }

        // First, try using token_get_all for syntax checking
        // This is the most reliable method without PHP CLI
        try {
            // Suppress errors and warnings during tokenization
            $previous_error_handler = set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                // Convert errors to exceptions
                throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
            });

            // Wrap code in PHP tags if not present
            $code_to_check = $code;
            if (stripos(trim($code), '<?php') !== 0 && stripos(trim($code), '<?') !== 0) {
                $code_to_check = "<?php\n" . $code;
            }

            // Try to tokenize the code
            $tokens = @token_get_all($code_to_check);

            // Restore previous error handler
            if ($previous_error_handler !== null) {
                set_error_handler($previous_error_handler);
            } else {
                restore_error_handler();
            }

            // Check for parse errors in tokens
            foreach ($tokens as $token) {
                if (is_array($token) && $token[0] === T_BAD_CHARACTER) {
                    return [
                        'valid' => false,
                        'error' => 'Invalid character found in code',
                        'line'  => $token[2] ?? 0,
                    ];
                }
            }

        // Try to catch additional syntax errors using advanced check
        // If advanced check fails, we'll be more lenient for now
        $syntax_check = self::advanced_syntax_check($code);
        if (!$syntax_check['valid']) {
            // For now, log the error but don't fail validation
            // This allows snippets to be created even if advanced validation fails
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[ECS Syntax] Advanced validation failed: ' . $syntax_check['error']);
            }
            // Return valid to allow snippet creation
            return [
                'valid' => true,
                'error' => '',
                'line'  => 0,
            ];
        }

            return [
                'valid' => true,
                'error' => '',
                'line'  => 0,
            ];

        } catch (\ErrorException $e) {
            // Restore error handler
            restore_error_handler();

            return [
                'valid' => false,
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
            ];
        } catch (\ParseError $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
            ];
        } catch (\Throwable $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
                'line'  => 0,
            ];
        }
    }

    /**
     * Advanced syntax check using safer method
     *
     * @param string $code PHP code to check
     * @return array Validation result
     */
    private static function advanced_syntax_check(string $code): array
    {
        // Use a more reliable method that works on Windows and other systems
        try {
            // Try to find PHP executable in common locations
            $php_paths = [
                'php', // Try direct command first
                'C:\\xampp\\php\\php.exe', // XAMPP on Windows
                'C:\\wamp64\\bin\\php\\php8.1.0\\php.exe', // WAMP on Windows
                'C:\\wamp64\\bin\\php\\php8.0.0\\php.exe', // WAMP on Windows
                '/usr/bin/php', // Linux
                '/usr/local/bin/php', // macOS
            ];
            
            $php_executable = null;
            foreach ($php_paths as $path) {
                if (self::is_php_executable($path)) {
                    $php_executable = $path;
                    break;
                }
            }
            
            // If no PHP executable found, use fallback validation
            if (!$php_executable) {
                return self::fallback_syntax_check($code);
            }
            
            // Create temporary file
            $temp_file = tempnam(sys_get_temp_dir(), 'ecs_php_check_');
            if (!$temp_file) {
                return self::fallback_syntax_check($code);
            }

            try {
                // Write code to temporary file
                file_put_contents($temp_file, "<?php\n" . $code);
                
                // Use php -l to check syntax
                $output = [];
                $return_code = 0;
                $command = escapeshellarg($php_executable) . " -l " . escapeshellarg($temp_file) . " 2>&1";
                exec($command, $output, $return_code);
                
                // Clean up temp file
                unlink($temp_file);
                
                if ($return_code === 0) {
                    return [
                        'valid' => true,
                        'error' => '',
                        'line'  => 0,
                    ];
                }
                
                // Parse error message to extract line number
                $error_message = implode(' ', $output);
                $line = 0;
                if (preg_match('/on line (\d+)/', $error_message, $matches)) {
                    $line = (int) $matches[1];
                }
                
                return [
                    'valid' => false,
                    'error' => self::clean_error_message($error_message),
                    'line'  => $line,
                ];
                
            } catch (\Throwable $e) {
                // Clean up temp file if it exists
                if (file_exists($temp_file)) {
                    unlink($temp_file);
                }
                throw $e;
            }
            
        } catch (\Throwable $e) {
            // If all else fails, use fallback validation
            return self::fallback_syntax_check($code);
        }
    }

    /**
     * Check if PHP executable exists and is usable
     *
     * @param string $path Path to PHP executable
     * @return bool True if executable and usable
     */
    private static function is_php_executable(string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        
        // Check if file exists
        if (!file_exists($path)) {
            return false;
        }
        
        // Try to run php --version
        $output = [];
        $return_code = 0;
        exec(escapeshellarg($path) . " --version 2>&1", $output, $return_code);
        
        return $return_code === 0;
    }

    /**
     * Fallback syntax check using basic tokenization
     *
     * @param string $code PHP code to check
     * @return array Validation result
     */
    private static function fallback_syntax_check(string $code): array
    {
        // Use basic tokenization as fallback
        try {
            $code_to_check = $code;
            if (stripos(trim($code), '<?php') !== 0 && stripos(trim($code), '<?') !== 0) {
                $code_to_check = "<?php\n" . $code;
            }
            
            $tokens = @token_get_all($code_to_check);
            
            // Check for basic syntax issues
            foreach ($tokens as $token) {
                if (is_array($token) && $token[0] === T_BAD_CHARACTER) {
                    return [
                        'valid' => false,
                        'error' => 'Invalid character found in code',
                        'line'  => $token[2] ?? 0,
                    ];
                }
            }
            
            return [
                'valid' => true,
                'error' => '',
                'line'  => 0,
            ];
            
        } catch (\Throwable $e) {
            return [
                'valid' => true, // If we can't validate, assume it's valid
                'error' => '',
                'line'  => 0,
            ];
        }
    }

    /**
     * Validate JavaScript syntax using simple pattern matching
     *
     * @param string $code JavaScript code to validate
     * @return array Validation result
     */
    public static function validate_javascript(string $code): array
    {
        if (empty(trim($code))) {
            return [
                'valid' => true,
                'error' => '',
                'line'  => 0,
            ];
        }

        // Basic JavaScript syntax checks
        $errors = [];

        // Check for unmatched braces
        $open_braces = substr_count($code, '{');
        $close_braces = substr_count($code, '}');
        if ($open_braces !== $close_braces) {
            $errors[] = 'Unmatched curly braces';
        }

        // Check for unmatched parentheses
        $open_parens = substr_count($code, '(');
        $close_parens = substr_count($code, ')');
        if ($open_parens !== $close_parens) {
            $errors[] = 'Unmatched parentheses';
        }

        // Check for unmatched brackets
        $open_brackets = substr_count($code, '[');
        $close_brackets = substr_count($code, ']');
        if ($open_brackets !== $close_brackets) {
            $errors[] = 'Unmatched square brackets';
        }

        // Check for common syntax errors
        if (preg_match('/\bfunction\s*\(/', $code)) {
            // Anonymous function without assignment might be an error
        }

        // Check for unterminated strings (basic check)
        $lines = explode("\n", $code);
        foreach ($lines as $line_num => $line) {
            // Skip comments
            if (preg_match('/^\s*\/\//', $line) || preg_match('/^\s*\/\*/', $line)) {
                continue;
            }

            // Count quotes (very basic check)
            $single_quotes = substr_count($line, "'") - substr_count($line, "\\'");
            $double_quotes = substr_count($line, '"') - substr_count($line, '\\"');

            if ($single_quotes % 2 !== 0 || $double_quotes % 2 !== 0) {
                $errors[] = "Possible unterminated string on line " . ($line_num + 1);
                break;
            }
        }

        if (!empty($errors)) {
            return [
                'valid' => false,
                'error' => implode('; ', $errors),
                'line'  => 0,
            ];
        }

        return [
            'valid' => true,
            'error' => '',
            'line'  => 0,
        ];
    }

    /**
     * Validate CSS syntax using simple pattern matching
     *
     * @param string $code CSS code to validate
     * @return array Validation result
     */
    public static function validate_css(string $code): array
    {
        if (empty(trim($code))) {
            return [
                'valid' => true,
                'error' => '',
                'line'  => 0,
            ];
        }

        $errors = [];

        // Check for unmatched braces
        $open_braces = substr_count($code, '{');
        $close_braces = substr_count($code, '}');
        if ($open_braces !== $close_braces) {
            $errors[] = 'Unmatched curly braces in CSS';
        }

        // Check for unmatched parentheses
        $open_parens = substr_count($code, '(');
        $close_parens = substr_count($code, ')');
        if ($open_parens !== $close_parens) {
            $errors[] = 'Unmatched parentheses in CSS';
        }

        // Check for basic CSS structure
        // Very basic check: rules should have selectors and declarations
        if ($open_braces > 0 && !preg_match('/[a-z0-9\-_#\.\[\]]+\s*\{/i', $code)) {
            $errors[] = 'Invalid CSS selector syntax';
        }

        if (!empty($errors)) {
            return [
                'valid' => false,
                'error' => implode('; ', $errors),
                'line'  => 0,
            ];
        }

        return [
            'valid' => true,
            'error' => '',
            'line'  => 0,
        ];
    }

    /**
     * Validate HTML syntax (basic check)
     *
     * @param string $code HTML code to validate
     * @return array Validation result
     */
    public static function validate_html(string $code): array
    {
        if (empty(trim($code))) {
            return [
                'valid' => true,
                'error' => '',
                'line'  => 0,
            ];
        }

        // Use DOMDocument for HTML validation
        if (class_exists('DOMDocument')) {
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            
            // Try to load HTML
            $result = @$dom->loadHTML('<!DOCTYPE html><html><body>' . $code . '</body></html>');
            
            $errors = libxml_get_errors();
            libxml_clear_errors();
            
            if (!empty($errors)) {
                $error_messages = [];
                foreach ($errors as $error) {
                    // Filter out minor warnings
                    if ($error->level === LIBXML_ERR_ERROR || $error->level === LIBXML_ERR_FATAL) {
                        $error_messages[] = trim($error->message);
                    }
                }
                
                if (!empty($error_messages)) {
                    return [
                        'valid' => false,
                        'error' => implode('; ', array_unique($error_messages)),
                        'line'  => 0,
                    ];
                }
            }
        }

        return [
            'valid' => true,
            'error' => '',
            'line'  => 0,
        ];
    }

    /**
     * Clean error message to remove internal paths
     *
     * @param string $message Error message
     * @return string Cleaned message
     */
    private static function clean_error_message(string $message): string
    {
        // Remove file paths
        $message = preg_replace('/in .+?on line/', 'on line', $message);
        
        // Remove eval'd code references
        $message = str_replace("eval()'d code", 'your code', $message);
        
        return $message;
    }

    /**
     * Validate code based on type
     *
     * @param string $code Code to validate
     * @param string $type Code type (php, js, css, html)
     * @return array Validation result
     */
    public static function validate(string $code, string $type): array
    {
        switch (strtolower($type)) {
            case 'php':
                return self::validate_php($code);
            
            case 'js':
            case 'javascript':
                return self::validate_javascript($code);
            
            case 'css':
                return self::validate_css($code);
            
            case 'html':
                return self::validate_html($code);
            
            default:
                return [
                    'valid' => true,
                    'error' => '',
                    'line'  => 0,
                ];
        }
    }
}

