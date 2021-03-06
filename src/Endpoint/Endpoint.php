<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation).
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\Endpoint;

use Amp\Promise;
use ServiceBus\Common\Endpoint\DeliveryOptions;
use ServiceBus\Transport\Common\DeliveryDestination;

/**
 * Destination when sending a message.
 */
interface Endpoint
{
    /**
     * Receive endpoint name.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Create a new endpoint object with the same transport but different delivery routes.
     *
     * @param DeliveryDestination $destination
     *
     * @return MessageDeliveryEndpoint
     */
    public function withNewDeliveryDestination(DeliveryDestination $destination): Endpoint;

    /**
     * Send message to endpoint.
     *
     * @param object          $message
     * @param DeliveryOptions $options
     *
     * @throws \ServiceBus\MessageSerializer\Exceptions\EncodeMessageFailed
     * @throws \ServiceBus\Transport\Common\Exceptions\SendMessageFailed Failed to send message
     *
     * @return Promise It does not return any result
     */
    public function delivery(object $message, DeliveryOptions $options): Promise;
}
