<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Error;

/**
 * Class EsiError
 *
 * Represents an error response from the EVE Online ESI API.
 * Provides methods to retrieve and set error details such as type, response code, SSO status, timeout, and error message.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Error
 */
class EsiError {
    /**
     * @var string $error
     * The error message.
     */
    protected string $error;

    /**
     * @var int $responseCode
     * The HTTP response code associated with the error.
     */
    protected int $responseCode;

    /**
     * @var int $ssoStatus
     * The status of the Single Sign-On (SSO) process.
     */
    protected int $ssoStatus;

    /**
     * @var int $timeout
     * The timeout duration for the request.
     */
    protected int $timeout;

    /**
     * @var string $type
     * The type of error.
     */
    protected string $type;

    /**
     * Constructor
     *
     * Initializes the error object with a specific error code and sets the corresponding error type.
     *
     * @param int $errorCode The HTTP error code.
     */
    public function __construct(int $errorCode) {
        $errorType = match ($errorCode) {
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            420 => 'Error limited',
            500 => 'Internal server error',
            503 => 'Service unavailable',
            504 => 'Gateway timeout',
            default => 'General error',
        };

        $this->setType($errorType);
        $this->setResponseCode($errorCode);
    }

    /**
     * Get the error message.
     *
     * @return string The error message.
     */
    public function getError(): string {
        return $this->error;
    }

    /**
     * Set the error message.
     *
     * @param string $error The error message to set.
     * @return void
     */
    protected function setError(string $error): void {
        $this->error = $error;
    }

    /**
     * Get the SSO status.
     *
     * @return int The SSO status.
     */
    public function getSsoStatus(): int {
        return $this->ssoStatus;
    }

    /**
     * Set the SSO status.
     *
     * @param int $ssoStatus The SSO status to set.
     * @return void
     */
    protected function setSsoStatus(int $ssoStatus): void {
        $this->ssoStatus = $ssoStatus;
    }

    /**
     * Get the timeout duration.
     *
     * @return int The timeout duration.
     */
    public function getTimeout(): int {
        return $this->timeout;
    }

    /**
     * Set the timeout duration.
     *
     * @param int $timeout The timeout duration to set.
     * @return void
     */
    protected function setTimeout(int $timeout): void {
        $this->timeout = $timeout;
    }

    /**
     * Get the type of error.
     *
     * @return string The error type.
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * Set the type of error.
     *
     * @param string $type The error type to set.
     * @return void
     */
    protected function setType(string $type): void {
        $this->type = $type;
    }

    /**
     * Get the HTTP response code.
     *
     * @return int The HTTP response code.
     */
    public function getResponseCode(): int {
        return $this->responseCode;
    }

    /**
     * Set the HTTP response code.
     *
     * @param int $responseCode The HTTP response code to set.
     * @return void
     */
    protected function setResponseCode(int $responseCode): void {
        $this->responseCode = $responseCode;
    }
}
