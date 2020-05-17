<?php
declare(strict_types=1);

namespace App\Controller\Response;

use Throwable;

final class AppJsonResponse
{
    private bool $success = true;

    /**
     * @var mixed
     */
    private $data;

    private ?Throwable $exception = null;

    private string $message = '';

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getException(): ?Throwable
    {
        return $this->exception;
    }

    public function setException(Throwable $exception): void
    {
        $this->exception = $exception;
    }
}
