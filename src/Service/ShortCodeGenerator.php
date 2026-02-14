<?php

namespace App\Service;

use App\Repository\UrlRepository;

class ShortCodeGenerator
{
    private const CHARACTERS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    private const CODE_LENGTH = 6;

    public function __construct(private UrlRepository $urlRepository)
    {
    }

    public function generate(): string
    {
        do {
            $code = $this->generateRandomCode();
        } while ($this->urlRepository->shortCodeExists($code));

        return $code;
    }

    private function generateRandomCode(): string
    {
        $code = '';
        $max = strlen(self::CHARACTERS) - 1;

        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            $code .= self::CHARACTERS[random_int(0, $max)];
        }

        return $code;
    }
}
