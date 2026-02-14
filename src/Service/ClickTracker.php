<?php

namespace App\Service;

use App\Entity\Click;
use App\Entity\Url;
use DeviceDetector\DeviceDetector;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ClickTracker
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function track(Url $url, Request $request): Click
    {
        $userAgent = $request->headers->get('User-Agent', '');
        $deviceDetector = new DeviceDetector($userAgent);
        $deviceDetector->parse();

        $client = $deviceDetector->getClient();
        $os = $deviceDetector->getOs();

        $click = new Click();
        $click->setUrl($url);
        $click->setClickedAt(new \DateTimeImmutable());
        $click->setIpAddress($request->getClientIp());
        $click->setReferrer($request->headers->get('Referer'));
        $click->setUserAgent($userAgent);
        $click->setBrowser(is_array($client) ? ($client['name'] ?? null) : null);
        $click->setBrowserVersion(is_array($client) ? ($client['version'] ?? null) : null);
        $click->setOs(is_array($os) ? ($os['name'] ?? null) : null);
        $click->setDeviceType($deviceDetector->getDeviceName() ?: null);

        $url->incrementClickCount();

        $this->em->persist($click);
        $this->em->flush();

        return $click;
    }
}
