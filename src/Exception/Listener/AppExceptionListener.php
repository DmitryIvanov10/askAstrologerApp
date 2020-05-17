<?php
declare(strict_types=1);

namespace App\Exception\Listener;

use App\Exception\AppException;
use App\Exception\NotFoundException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class AppExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof AppException) {
            return;
        }

        if ($exception instanceof NotFoundException) {
            $event->setThrowable(new NotFoundHttpException(
                $exception->getMessage(),
                $exception
            ));
        }
    }
}
