<?php

namespace Http\Client;

use Psr\Http\Message\ResponseInterface;

/**
 * Promise represents a response that may not be available yet, but will be resolved at some point in future.
 * It acts like a proxy to the actual response.
 *
 * This interface is an extension of the promises/a+ specification https://promisesaplus.com/
 * Value is replaced by an object where its class implement a Psr\Http\Message\RequestInterface.
 * Reason is replaced by an object where its class implement a Http\Client\Exception.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
interface Promise
{
    /**
     * Pending state, promise has not been fulfilled or rejected.
     */
    const STATE_PENDING = 'pending';

    /**
     * Fulfilled state, promise has been fulfilled with a ResponseInterface object.
     */
    const STATE_FULFILLED = 'fulfilled';

    /**
     * Rejected state, promise has been rejected with an Exception object.
     */
    const STATE_REJECTED  = 'rejected';

    /**
     * Adds behavior for when the promise is resolved or rejected (response will be available, or error happens).
     *
     * If you do not care about one of the cases, you can set the corresponding callable to null
     * The callback will be called when the response or exception arrived and never more than once.
     *
     * You must always return the Response or throw an Exception.
     *
     * @param callable $onFulfilled Called when a response will be available.
     * @param callable $onRejected  Called when an error happens.
     *
     * @return Promise A new resolved promise with value of the executed callback (onFulfilled / onRejected).
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null);

    /**
     * Returns the state of the promise, one of 'pending', 'fulfilled' or 'rejected'.
     *
     * @return string
     */
    public function getState();

    /**
     * Wait for the promise to be fulfilled or rejected.
     *
     * When this method returns, the request has been resolved and the appropriate callable has terminated.
     *
     * Pass $unwrap as true to unwrap the result of the promise, either
     * returning the resolved value or throwing the rejected exception.
     *
     * @param bool $unwrap
     *
     * @return mixed
     */
    public function wait($unwrap = true);
}
