<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Keeps the user's chosen locale sticky across requests.
 *
 * The locale is stored in the session (see LocaleController) and re-applied on
 * every subsequent request. When nothing is stored yet, the request keeps the
 * framework default locale (bg — the primary language for pilence).
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly string $defaultLocale = 'bg')
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }

        // If a locale was explicitly set as a routing parameter, remember it...
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);

            return;
        }

        // ...otherwise reuse the one stored in the session (falling back to default).
        $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Must run before Symfony's default LocaleListener (priority 16).
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
