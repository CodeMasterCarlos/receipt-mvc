<?php

namespace Codemastercarlos\Receipt\Bootstrap;

trait FlasherMessage
{
    private function getFlasherMessageSession(): array|null
    {
        return $_SESSION['receipt']['message'];
    }

    private function setFlasherMessageSession(array $value): void
    {
        $_SESSION['receipt']['message'] = $value;
    }

    private function destroyFlasherMessage(): void
    {
        unset($_SESSION['receipt']['message']);
    }

    protected function getFlasherMessage(): array
    {
        $message = $this->getFlasherMessageSession();
        $this->destroyFlasherMessage();
        return $message ?? [];
    }

    protected function flasherCreate(string $status, string $message, int $time = 3000): void
    {
        $this->setFlasherMessageSession([
            'status' => $status,
            'message' => $message,
            'time' => $time,
        ]);
    }
}