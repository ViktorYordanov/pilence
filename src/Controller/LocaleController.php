<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LocaleController extends AbstractController
{
    /**
     * Switches the active language and returns the user to where they came from.
     *
     * The `_locale` requirement is limited to the enabled locales, so unknown
     * values 404 instead of poisoning the session.
     */
    #[Route('/locale/{_locale}', name: 'app_locale_switch', requirements: ['_locale' => 'bg|en'])]
    public function switch(string $_locale, Request $request): RedirectResponse
    {
        if ($request->hasSession()) {
            $request->getSession()->set('_locale', $_locale);
        }

        // Only follow the referer when it points back into this site.
        $referer = $request->headers->get('referer');
        $base = $request->getSchemeAndHttpHost();

        if ($referer && str_starts_with($referer, $base)) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('app_home');
    }
}
