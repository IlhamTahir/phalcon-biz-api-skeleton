<?php
namespace Codeages\PhalconBiz\Event;

use Phalcon\Http\ResponseInterface;
use Phalcon\Http\RequestInterface;


/**
 * Allows to create a response for a thrown exception.
 *
 * Call setResponse() to set the response that will be returned for the
 * current request. The propagation of this event is stopped as soon as a
 * response is set.
 *
 * You can also call setException() to replace the thrown exception. This
 * exception will be thrown if no response is set during processing of this
 * event.
 */
class GetResponseForExceptionEvent extends GetResponseEvent
{
    /**
     * The exception object.
     *
     * @var \Exception
     */
    private $exception;

    /**
     * @var bool
     */
    private $allowCustomResponseCode = false;

    public function __construct($app, RequestInterface $request, \Exception $e)
    {
        parent::__construct($app, $request);

        $this->setException($e);
    }

    /**
     * Returns the thrown exception.
     *
     * @return \Exception The thrown exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Replaces the thrown exception.
     *
     * This exception will be thrown if no response is set in the event.
     *
     * @param \Exception $exception The thrown exception
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Mark the event as allowing a custom response code.
     */
    public function allowCustomResponseCode()
    {
        $this->allowCustomResponseCode = true;
    }

    /**
     * Returns true if the event allows a custom response code.
     *
     * @return bool
     */
    public function isAllowingCustomResponseCode()
    {
        return $this->allowCustomResponseCode;
    }
}