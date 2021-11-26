<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use App\Models\BotNotify;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use App\Http\Resources\Domain as ResourcesDomain;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $metabaseSiteUrl = 'http://localhost:3000';
        $metabaseSecretKey = 'b903298e992030b679b1e87360609e4198e221ba764cfd8b2d8269d40b2b3b37';
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($metabaseSecretKey));

        $token = $config->builder()
            ->withClaim('resource', [
                'dashboard' => 4
            ])
            ->withClaim('params', [
                'params' => null
            ])
            ->getToken($config->signer(), $config->signingKey());

        $token = $token->toString();

        $iframeUrl = "{$metabaseSiteUrl}/embed/dashboard/{$token}#bordered=false&titled=true";

        return Inertia::render('Analytics/Index', [
            'iframeUrl' => $iframeUrl,
        ]);
    }
}
