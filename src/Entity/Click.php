<?php

namespace App\Entity;

use App\Repository\ClickRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClickRepository::class)]
#[ORM\Table(name: 'click')]
#[ORM\Index(columns: ['url_id', 'clicked_at'], name: 'idx_click_url_date')]
class Click
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Url::class, inversedBy: 'clicks')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Url $url = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $clickedAt = null;

    #[ORM\Column(type: Types::STRING, length: 45, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(type: Types::STRING, length: 2048, nullable: true)]
    private ?string $referrer = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $browser = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $browserVersion = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $os = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true)]
    private ?string $deviceType = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUrl(): ?Url
    {
        return $this->url;
    }

    public function setUrl(?Url $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function getClickedAt(): ?\DateTimeImmutable
    {
        return $this->clickedAt;
    }

    public function setClickedAt(\DateTimeImmutable $clickedAt): static
    {
        $this->clickedAt = $clickedAt;
        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getReferrer(): ?string
    {
        return $this->referrer;
    }

    public function setReferrer(?string $referrer): static
    {
        $this->referrer = $referrer;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): static
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function setBrowser(?string $browser): static
    {
        $this->browser = $browser;
        return $this;
    }

    public function getBrowserVersion(): ?string
    {
        return $this->browserVersion;
    }

    public function setBrowserVersion(?string $browserVersion): static
    {
        $this->browserVersion = $browserVersion;
        return $this;
    }

    public function getOs(): ?string
    {
        return $this->os;
    }

    public function setOs(?string $os): static
    {
        $this->os = $os;
        return $this;
    }

    public function getDeviceType(): ?string
    {
        return $this->deviceType;
    }

    public function setDeviceType(?string $deviceType): static
    {
        $this->deviceType = $deviceType;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'clicked_at' => $this->clickedAt?->format('c'),
            'ip_address' => $this->ipAddress,
            'referrer' => $this->referrer,
            'browser' => $this->browser,
            'browser_version' => $this->browserVersion,
            'os' => $this->os,
            'device_type' => $this->deviceType,
        ];
    }
}
