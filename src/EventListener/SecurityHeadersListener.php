<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class SecurityHeadersListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();

        // Headers de sécurité essentiels
        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        ];

        // Ajouter les headers s'ils ne sont pas déjà définis
        foreach ($headers as $name => $value) {
            if (!$response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        // CSP (Content Security Policy) - configuration basique
        // À adapter selon les besoins de l'application
        $cspHeader = "default-src 'self'; " .
                     "script-src 'self' 'unsafe-inline' https:; " .
                     "style-src 'self' 'unsafe-inline' https:; " .
                     "img-src 'self' data: https:; " .
                     "font-src 'self' https:; " .
                     "connect-src 'self' https:; " .
                     "frame-ancestors 'none'; " .
                     "base-uri 'self'; " .
                     "form-action 'self';";

        if (!$response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', $cspHeader);
            // Header pour les anciens navigateurs
            $response->headers->set('X-Content-Security-Policy', $cspHeader);
        }
    }
}
