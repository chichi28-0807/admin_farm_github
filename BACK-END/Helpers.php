<?php
declare(strict_types=1);

final class Response
{
    public static function send(array $payload, int $code = 200): never
    {
        http_response_code($code);
        echo json_encode(['success' => $code < 400] + $payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function error(string $message, int $code, array $details = []): never
    {
        $payload = ['error' => $message];
        if ($details) {
            $payload['details'] = $details;
        }
        self::send($payload, $code);
    }

    public static function headers(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key');
    }
}

final class Auth
{
    public static function check(string $apiKey): void
    {
        $sent = $_SERVER['HTTP_X_API_KEY'] ?? '';
        if (!hash_equals($apiKey, $sent)) {
            Response::error('Unauthorized: invalid or missing API key', 401);
        }
    }
}

final class Validator
{
    /**
     * @return array{0: array, 1: array} [cleanData, errors]
     */
    public static function validate(array $rules, array $input, bool $partial = false): array
    {
        $clean = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            $present = array_key_exists($field, $input)
                && $input[$field] !== ''
                && $input[$field] !== null;

            if (!$present) {
                if (($rule['required'] ?? false) && !$partial) {
                    $errors[$field] = 'is required';
                }
                continue;
            }

            $value = $input[$field];

            switch ($rule['type']) {
                case 'int':
                    if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                        $errors[$field] = 'must be an integer';
                        break;
                    }
                    $value = (int)$value;
                    if ($value < ($rule['min'] ?? 0)) {
                        $errors[$field] = 'must be at least ' . ($rule['min'] ?? 0);
                    }
                    break;

                case 'decimal':
                    if (!is_numeric($value) || (float)$value < 0) {
                        $errors[$field] = 'must be a non-negative number';
                        break;
                    }
                    $value = (float)$value;
                    break;

                case 'date':
                    $d = DateTime::createFromFormat('Y-m-d', (string)$value);
                    if (!$d || $d->format('Y-m-d') !== (string)$value) {
                        $errors[$field] = 'must be a valid date (YYYY-MM-DD)';
                    }
                    break;

                case 'enum':
                    if (!in_array($value, $rule['values'], true)) {
                        $errors[$field] = 'must be one of: ' . implode(', ', $rule['values']);
                    }
                    break;

                case 'string':
                    $value = trim((string)$value);
                    if (mb_strlen($value) > ($rule['max'] ?? 255)) {
                        $errors[$field] = 'is too long (max ' . ($rule['max'] ?? 255) . ')';
                    }
                    break;
            }

            if (!isset($errors[$field])) {
                $clean[$field] = $value;
            }
        }

        return [$clean, $errors];
    }
}