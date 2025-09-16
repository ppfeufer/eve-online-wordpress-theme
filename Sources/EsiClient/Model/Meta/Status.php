<?php

namespace Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta;

/**
 * Class Status
 *
 * Represents the status of an ESI route, including its endpoint, method, route, status, and tags.
 * Provides getter and setter methods for each property.
 *
 * @package Ppfeufer\Theme\EVEOnline\EsiClient\Model\Meta
 */
class Status {
    /**
     * The ESI endpoint cluster advertising this route.
     *
     * @var string
     */
    protected string $endpoint;

    /**
     * The Swagger-defined method for this route.
     *
     * @var string
     */
    protected string $method;

    /**
     * The Swagger-defined route, not including the version prefix.
     *
     * @var string
     */
    protected string $route;

    /**
     * The vague status of the route.
     * Green: Good.
     * Yellow: Degraded (slow or potentially dropping requests).
     * Red: Most requests are failing or very slow (5s+ on average).
     *
     * @var string
     */
    protected string $status;

    /**
     * The Swagger tags applicable to this route.
     *
     * @var array
     */
    protected array $tags;

    /**
     * Get the ESI endpoint cluster advertising this route.
     *
     * @return string The endpoint.
     */
    public function getEndpoint(): string {
        return $this->endpoint;
    }

    /**
     * Set the ESI endpoint cluster advertising this route.
     *
     * @param string $endpoint The endpoint to set.
     */
    protected function setEndpoint(string $endpoint): void {
        $this->endpoint = $endpoint;
    }

    /**
     * Get the Swagger-defined method for this route.
     *
     * @return string The method.
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * Set the Swagger-defined method for this route.
     *
     * @param string $method The method to set.
     */
    protected function setMethod(string $method): void {
        $this->method = $method;
    }

    /**
     * Get the Swagger-defined route, not including the version prefix.
     *
     * @return string The route.
     */
    public function getRoute(): string {
        return $this->route;
    }

    /**
     * Set the Swagger-defined route, not including the version prefix.
     *
     * @param string $route The route to set.
     */
    protected function setRoute(string $route): void {
        $this->route = $route;
    }

    /**
     * Get the vague status of the route.
     *
     * @return string The status.
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * Set the vague status of the route.
     *
     * @param string $status The status to set.
     */
    public function setStatus(string $status): void {
        $this->status = $status;
    }

    /**
     * Get the Swagger tags applicable to this route.
     *
     * @return array The tags.
     */
    public function getTags(): array {
        return $this->tags;
    }

    /**
     * Set the Swagger tags applicable to this route.
     *
     * @param array $tags The tags to set.
     */
    public function setTags(array $tags): void {
        $this->tags = $tags;
    }
}
