<?php

namespace Shopsys\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Transport\TransportData;
use Shopsys\FrameworkBundle\Model\Transport\TransportDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Transport\TransportFacade;
use Shopsys\ShopBundle\Model\Transport\Transport;

class TransportDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface
{
    const TRANSPORT_CZECH_POST = 'transport_cp';
    const TRANSPORT_PPL = 'transport_ppl';
    const TRANSPORT_PERSONAL = 'transport_personal';
    const TRANSPORT_ZASILKOVNA = 'transport_zasilkovna';

    /** @var \Shopsys\FrameworkBundle\Model\Transport\TransportFacade */
    protected $transportFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Transport\TransportDataFactoryInterface
     */
    protected $transportDataFactory;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\TransportFacade $transportFacade
     * @param \Shopsys\FrameworkBundle\Model\Transport\TransportDataFactoryInterface $transportDataFactory
     */
    public function __construct(
        TransportFacade $transportFacade,
        TransportDataFactoryInterface $transportDataFactory
    ) {
        $this->transportFacade = $transportFacade;
        $this->transportDataFactory = $transportDataFactory;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $transportData = $this->transportDataFactory->create();
        $transportData->name = [
            'cs' => 'Česká pošta - balík do ruky',
            'en' => 'Czech post',
        ];
        $transportData->pricesByCurrencyId = [
            $this->getReference(CurrencyDataFixture::CURRENCY_CZK)->getId() => Money::create('99.95'),
            $this->getReference(CurrencyDataFixture::CURRENCY_EUR)->getId() => Money::create('3.95'),
        ];
        $transportData->vat = $this->getReference(VatDataFixture::VAT_HIGH);
        $this->createTransport(self::TRANSPORT_CZECH_POST, $transportData);

        $transportData = $this->transportDataFactory->create();
        $transportData->name = [
            'cs' => 'PPL',
            'en' => 'PPL',
        ];
        $transportData->pricesByCurrencyId = [
            $this->getReference(CurrencyDataFixture::CURRENCY_CZK)->getId() => Money::create('199.95'),
            $this->getReference(CurrencyDataFixture::CURRENCY_EUR)->getId() => Money::create('6.95'),
        ];
        $transportData->vat = $this->getReference(VatDataFixture::VAT_HIGH);
        $this->createTransport(self::TRANSPORT_PPL, $transportData);

        $transportData = $this->transportDataFactory->create();
        $transportData->name = [
            'cs' => 'Osobní převzetí',
            'en' => 'Personal collection',
        ];
        $transportData->pricesByCurrencyId = [
            $this->getReference(CurrencyDataFixture::CURRENCY_CZK)->getId() => Money::zero(),
            $this->getReference(CurrencyDataFixture::CURRENCY_EUR)->getId() => Money::zero(),
        ];
        $transportData->description = [
            'cs' => 'Uvítá Vás milý personál!',
            'en' => 'You will be welcomed by friendly staff!',
        ];
        $transportData->instructions = [
            'cs' => 'Těšíme se na Vaši návštěvu.',
            'en' => 'We are looking forward to your visit.',
        ];
        $transportData->vat = $this->getReference(VatDataFixture::VAT_ZERO);
        $this->createTransport(self::TRANSPORT_PERSONAL, $transportData);

        $transportData = $this->transportDataFactory->create();
        $transportData->name = [
            'cs' => 'Zásilkovna',
            'en' => 'Zásilkovna',
        ];
        $transportData->pricesByCurrencyId = [
            $this->getReference(CurrencyDataFixture::CURRENCY_CZK)->getId() => Money::zero(),
            $this->getReference(CurrencyDataFixture::CURRENCY_EUR)->getId() => Money::zero(),
        ];

        $transportData->type = Transport::TYPE_ZASILKOVNA;

        $transportData->vat = $this->getReference(VatDataFixture::VAT_ZERO);
        $this->createTransport(self::TRANSPORT_ZASILKOVNA, $transportData);
    }

    /**
     * @param string $referenceName
     * @param \Shopsys\FrameworkBundle\Model\Transport\TransportData $transportData
     */
    protected function createTransport($referenceName, TransportData $transportData)
    {
        $transport = $this->transportFacade->create($transportData);
        $this->addReference($referenceName, $transport);
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            VatDataFixture::class,
            CurrencyDataFixture::class,
        ];
    }
}
